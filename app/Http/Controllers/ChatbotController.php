<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Appointment;
use App\Models\FileMonitoring;
use App\Models\MunicipalityYearlySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private array $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

    /**
     * Handle a chat message.
     * Messages go through Groq with live system data injected.
     * PHP instant responses serve as fallback only when API is rate-limited (429).
     */
    public function reply(Request $request)
    {
        $request->validate([
            'message'        => 'required|string|max:2000',
            'history'        => 'nullable|array|max:20',
            'history.*.role' => 'required|string|in:user,model',
            'history.*.text' => 'required|string|max:2000',
        ]);

        $message = trim($request->input('message'));
        $user    = Auth::user();
        $lang    = $this->detectLanguage($message);
        $isPersonalAppQuestion = $this->isPersonalApplicationQuestion($message);

        // ── Personal application status: require login (enforced server-side) ──
        if ($isPersonalAppQuestion && !$user) {
            $programLabel = $this->detectProgramLabel($message);
            return response()->json([
                'reply' => $this->loginRequiredMessage($lang, $programLabel),
            ]);
        }

        $userAppContext = $user ? $this->buildUserApplicationContext($user) : '';

        if ($isPersonalAppQuestion && $user && $userAppContext === '') {
            return response()->json([
                'reply' => $this->noApplicationMessage($lang),
            ]);
        }

        $apiKey = config('services.groq.key');
        $model  = config('services.groq.model') ?: 'openai/gpt-oss-120b';

        if (!$apiKey) {
            return response()->json([
                'reply' => '⚠️ Chatbot is not configured. Please contact the administrator. (GROQ_API_KEY missing.)'
            ]);
        }

        // ── Build compact live-data context ───────────────────────────────────
        $liveData     = $this->getLatestData();
        $dataTable    = $this->buildCompactTable($liveData);
        $systemPrompt = $this->buildSystemPrompt(
            $dataTable,
            (bool) $user,
            $userAppContext,
            $isPersonalAppQuestion
        );

        // ── Build messages array (OpenAI-compatible format) ────────────────────
        // GroqCloud uses: [system, ...history turns as user/assistant, current user]
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        // Map past history: Gemini uses 'model' role, Groq uses 'assistant'
        $history = collect($request->input('history', []))
            ->slice(-10)
            ->each(function ($h) use (&$messages) {
                $messages[] = [
                    'role'    => $h['role'] === 'model' ? 'assistant' : 'user',
                    'content' => $h['text'],
                ];
            });

        $messages[] = ['role' => 'user', 'content' => $message];

        // ── Call GroqCloud ────────────────────────────────────────────────────
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => $model,
                    'messages'    => $messages,
                    'temperature' => 0.65,
                    'max_tokens'  => 800,
                ]);

            // ── Success ────────────────────────────────────────────────────────
            if ($response->successful()) {
                $text = $response->json('choices.0.message.content')
                    ?? 'Paumanhin, hindi ako makasagot ngayon. Subukan ulit.';
                return response()->json(['reply' => $text]);
            }

            // ── Handle API errors ──────────────────────────────────────────────
            $status = $response->status();
            \Log::error('Groq API error', ['status' => $status, 'model' => $model, 'body' => $response->body()]);

            if ($status === 429) {
                // Rate-limited: serve instant PHP fallback if keyword matches
                $fallback = $this->instantFallback($message, $liveData);
                if ($fallback) {
                    return response()->json([
                        'reply' => $fallback . "\n\n_ℹ️ (AI busy — showing pre-loaded data. Subukan ulit ang AI mamaya.)_"
                    ]);
                }
                return response()->json([
                    'reply' => "⏳ **Busy ang AI ngayon.** Pakihintay ng 30–60 segundo bago subukan ulit.\n\n" .
                               "Samantala, i-click ang mga button sa itaas (Population, Age, 4Ps, atbp.) para sa instant na sagot."
                ]);
            }

            return response()->json(['reply' => '⚠️ Nakaranas ng error. Subukan ulit mamaya.']);

        } catch (\Exception $e) {
            \Log::error('Groq chatbot exception: ' . $e->getMessage());
            return response()->json(['reply' => '⚠️ Hindi makonekta sa AI. Suriin ang internet connection at subukan ulit.']);
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SYSTEM PROMPT — kept short to minimize token usage
    // ──────────────────────────────────────────────────────────────────────────
    private function buildSystemPrompt(
        string $dataTable,
        bool $isLoggedIn,
        string $userAppContext,
        bool $isPersonalAppQuestion = false
    ): string {
        $authBlock = $isLoggedIn
            ? 'USER AUTH STATE: LOGGED IN — You may reference this user\'s application data below for personal status questions.'
            : 'USER AUTH STATE: NOT LOGGED IN — Do NOT provide any personal application status. Direct the user to /login for personal application questions.';

        $userDataBlock = $userAppContext !== ''
            ? "=== CURRENT USER'S APPLICATION DATA (this logged-in user ONLY — never share with others) ===\n{$userAppContext}\n=== END USER APPLICATION DATA ==="
            : ($isLoggedIn
                ? "=== CURRENT USER'S APPLICATION DATA ===\nNo applications or appointments found for this account.\n=== END USER APPLICATION DATA ==="
                : '');

        return <<<PROMPT
You are the official AI assistant for the MSWDO (Municipal Social Welfare and Development Office) system for Liliw, Majayjay, and Magdalena, Laguna, Philippines.

=== PRIMARY RULE ===
ONLY ASSIST WITH THIS SYSTEM. Do not act as a general-purpose chatbot. Your knowledge and responses must remain focused on this system and its purpose.

{$authBlock}

=== LIVE SYSTEM DATA (source of truth for demographic/statistical questions) ===
{$dataTable}
=== END DATA ===

{$userDataBlock}

=== APPLICATION STATUS & PROGRAM PROCESS RULE ===
Distinguish TWO types of questions:

A) GENERAL program information (requirements, eligibility, how to apply, steps) — may be answered WITHOUT login.

B) PERSONAL application status/process/progress ("my application", "check my status", "aplikasyon ko") — ONLY when user is LOGGED IN and data exists in USER APPLICATION DATA above.

Rules for personal application questions:
• If NOT logged in → say: "Please log in to your account first so I can help you check the current status and process of your application for this program." (Tagalog equivalent if user writes in Tagalog)
• If logged in → use ONLY statuses from USER APPLICATION DATA. Valid statuses include: pending, under review / in_review, approved, rejected, completed, confirmed, validated, processing, ready_for_pickup, released, cancelled — ONLY if they appear in the data.
• If logged in but no application found → say: "I couldn't find an application associated with your account. Please make sure you are using the correct account or contact the appropriate system administrator for assistance."
• NEVER guess application status, approval, rejection, or dates
• NEVER reveal another user's application information
• NEVER invent processing or approval dates
• Explain the current status and logical next step based on actual data only

=== SYSTEM STRUCTURE & NAVIGATION ===
NAVBAR sections:
1. HOME — Dashboard / landing page
2. ANALYSIS — Programs Analysis (descriptive stats, trends, ANOVA, correlation, insights) and Demographic Analysis (population, map, gender, age, households, beneficiaries)
3. PROGRAMS — 4Ps, PWD, AICS (Medical, Burial, Emergency Shelter), Solo Parent, Senior Citizen, SLP
4. APPLY — Program applications
5. ABOUT / CONTACT
6. LOGIN / REGISTER

User application flow:
• Register at /register (18+, valid municipality/barangay) → OTP via email → verify OTP → set password → login at /login
• Logged-in users apply via program wizards (book appointment → interview → upload requirements where applicable)
• User roles: regular user, admin, superadmin — each with different dashboards and permissions

=== 1. SCOPE OF KNOWLEDGE ===
Only answer questions directly related to this system:
• How to use the system, features, modules, dashboards
• Data entry, applications, beneficiaries, municipalities, barangays
• Social welfare programs (4Ps, PWD, AICS, Solo Parent, Senior, SLP)
• Demographic data, charts, statistics, filters, analysis, reports in the system
• User roles, permissions, navigation, troubleshooting system issues
• Information displayed on system pages

If the user asks something UNRELATED (programming, math, current events, personal advice, entertainment, general knowledge, cooking, sports, etc.), respond EXACTLY with this (adapt language to match user):
"I can only assist with questions related to this system. Please ask me about the system's features, data, analysis, reports, or how to use it."
(In Tagalog if user wrote in Tagalog: "Maaari lang akong tumulong sa mga tanong tungkol sa system na ito. Magtanong po tungkol sa features, data, analysis, reports, o kung paano gamitin ang system.")

=== 2. NEVER MAKE UP INFORMATION ===
• Do NOT invent features, records, statistics, programs, municipalities, users, or functionality.
• Use ONLY the LIVE SYSTEM DATA and structure above.
• If information is not available, say: "I don't have enough information from the system to answer that accurately." (Tagalog: "Wala akong sapat na impormasyon mula sa system para masagot iyan nang tama.")
• NEVER guess. Clearly distinguish actual system data from general explanations.

=== 3. DATA & ANALYSIS RESPONSES ===
When answering about population, age, gender, households, beneficiaries:
• Use only numbers from LIVE SYSTEM DATA
• Mention all three municipalities when comparing
• Format numbers with commas (e.g., 39,977)
• If a record does not exist in the data, say it is not available in the system

=== 4. SECURITY & PRIVACY ===
NEVER reveal: API keys, passwords, tokens, environment variables, database credentials, internal secrets, or sensitive backend details.
If asked for secrets, refuse: "I cannot provide credentials or internal system secrets."

=== 5. CONVERSATION BEHAVIOR ===
• Be helpful, concise, and professional
• Use conversation history — treat follow-ups in context; do not restart unnecessarily
• If user asks in English → reply in English; Tagalog → Tagalog; Taglish → Taglish
• Use bullet points (•) when listing
• Keep answers concise unless the user asks for detail

=== 6. PROGRAM INFO (for eligibility/requirements questions) ===
• 4Ps: Cash transfer for poor families. Requirements: Certificate of Indigency, Birth Certificate of child, Valid ID.
• PWD: For persons with disabilities. Requirements: Medical Certificate, Valid ID, 1x1 photo, Barangay Certificate.
• AICS Medical: Hospital/medicine aid. Requirements: Medical Certificate, Hospital Bill, Barangay Certificate of Indigency, Valid ID.
• AICS Burial: Funeral/burial aid. Requirements: Death Certificate, Marriage/Birth Cert, Valid ID, Authorization Letter.
• Solo Parent: Single parents, children below 18. Requirements: Birth Cert, Barangay Cert, CENOMAR/Death Cert as applicable, Valid ID, 2x2 photo.
• SLP: Livelihood training. Requirements: Barangay Certificate, Valid ID, Proof of low income.

For "how to apply": ask which program first, then give steps (book appointment if required, attend interview, upload documents after validation).

=== 7. LOGIN / REGISTER HELP ===
Login: /login — username or email + password. Unverified email → OTP page. Inactive account → contact administrator.
Register: /register — full name, username, email, mobile, gender, birthdate, municipality, barangay (18+).
PROMPT;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Build a compact one-line-per-municipality data table (saves ~80% tokens)
    // ──────────────────────────────────────────────────────────────────────────
    private function buildCompactTable(array $data): string
    {
        if (empty($data)) {
            return 'No demographic data available yet in the system.';
        }

        $lines = [];
        foreach ($data as $muni => $row) {
            $benef = $row['pwd'] + $row['aics'] + $row['solo_parent'] + $row['four_ps'] + $row['senior'];
            $avgHH = $row['households'] > 0 ? round($row['population'] / $row['households'], 1) : 0;

            $lines[] = "{$muni} | Year:{$row['year']} | Pop:" . number_format($row['population']) .
                " | Male:" . number_format($row['male']) .
                " | Female:" . number_format($row['female']) .
                " | Age0-19:" . number_format($row['age_0_19']) .
                " | Age20-59:" . number_format($row['age_20_59']) .
                " | Age60+:" . number_format($row['age_60']) .
                " | HH:" . number_format($row['households']) .
                " | AvgHH:{$avgHH}" .
                " | 4Ps:" . number_format($row['four_ps']) .
                " | PWD:" . number_format($row['pwd']) .
                " | AICS:" . number_format($row['aics']) .
                " | SoloParent:" . number_format($row['solo_parent']) .
                " | Senior:" . number_format($row['senior']) .
                " | TotalBenef:" . number_format($benef);
        }

        // Also append all available years for trend questions
        $trendLines = $this->buildTrendData();
        if ($trendLines) {
            $lines[] = '';
            $lines[] = 'HISTORICAL TREND DATA (all years):';
            foreach ($trendLines as $line) {
                $lines[] = $line;
            }
        }

        return implode("\n", $lines);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Instant PHP fallback (only used when Gemini is rate-limited)
    // ──────────────────────────────────────────────────────────────────────────
    private function instantFallback(string $msg, array $data): ?string
    {
        $key = strtolower(trim($msg));
        $lang = $this->detectLanguage($msg);

        if (str_contains($key, 'population') || str_contains($key, 'populasyon')) {
            $lines = ["📊 **Population (Latest Year)**\n"];
            foreach ($data as $muni => $row) {
                $lines[] = $lang === 'en'
                    ? "• **{$muni}** ({$row['year']}): " . number_format($row['population']) . " people"
                    : "• **{$muni}** ({$row['year']}): " . number_format($row['population']) . " katao";
            }
            return implode("\n", $lines);
        }

        if (str_contains($key, 'male') || str_contains($key, 'lalaki')) {
            $lines = ["👨 **Male Population**\n"];
            foreach ($data as $muni => $row) {
                $pct = $row['population'] > 0 ? round($row['male'] / $row['population'] * 100, 1) : 0;
                $lines[] = "• **{$muni}**: " . number_format($row['male']) . " ({$pct}%)";
            }
            return implode("\n", $lines);
        }

        if (str_contains($key, 'female') || str_contains($key, 'babae')) {
            $lines = ["👩 **Female Population**\n"];
            foreach ($data as $muni => $row) {
                $pct = $row['population'] > 0 ? round($row['female'] / $row['population'] * 100, 1) : 0;
                $lines[] = "• **{$muni}**: " . number_format($row['female']) . " ({$pct}%)";
            }
            return implode("\n", $lines);
        }

        if (str_contains($key, 'household') || str_contains($key, 'kabahayan')) {
            $lines = ["🏠 **Households**\n"];
            foreach ($data as $muni => $row) {
                $avg = $row['households'] > 0 ? round($row['population'] / $row['households'], 1) : 0;
                $lines[] = $lang === 'en'
                    ? "• **{$muni}**: " . number_format($row['households']) . " households (avg {$avg} persons/household)"
                    : "• **{$muni}**: " . number_format($row['households']) . " kabahayan (avg {$avg} tao/bahay)";
            }
            return implode("\n", $lines);
        }

        if (str_contains($key, 'age') || str_contains($key, 'edad')) {
            $lines = ["📅 **Age Structure**\n"];
            foreach ($data as $muni => $row) {
                $lines[] = $lang === 'en'
                    ? "• **{$muni}**: Youth(0-19)=" . number_format($row['age_0_19']) .
                        " | Working Age(20-59)=" . number_format($row['age_20_59']) .
                        " | Senior(60+)=" . number_format($row['age_60'])
                    : "• **{$muni}**: Youth(0-19)=" . number_format($row['age_0_19']) .
                        " | Working(20-59)=" . number_format($row['age_20_59']) .
                        " | Senior(60+)=" . number_format($row['age_60']);
            }
            return implode("\n", $lines);
        }

        if (in_array($key, ['4ps', 'pwd', 'aics', 'solo parent', 'slp'])) {
            return $this->programInfo($key);
        }

        if (
            str_contains($key, 'login') || str_contains($key, 'log in') ||
            str_contains($key, 'register') || str_contains($key, 'sign up') ||
            str_contains($key, 'otp') || str_contains($key, 'verify') ||
            str_contains($key, 'password') || str_contains($key, 'mag login') ||
            str_contains($key, 'mag-register') || str_contains($key, 'mag register')
        ) {
            return $this->systemInfo($key, $lang);
        }

        return null;
    }

    private function programInfo(string $key): string
    {
        $info = [
            '4ps'         => "📋 **4Ps** — Cash transfer para sa mahirap na pamilya.\n✅ Eligibility: Indigent, may anak below 18.\n📎 Kailangan: Cert of Indigency, Birth Cert, Valid ID.",
            'pwd'         => "♿ **PWD Assistance** — Para sa may kapansanan.\n✅ Eligibility: May dokumentadong disability.\n📎 Kailangan: Medical Cert, Valid ID, 1x1 photo, Barangay Cert.",
            'aics'        => "🏥 **AICS** — Para sa medical/burial emergencies.\n📎 Medical: Med Cert, Hospital Bill, Barangay Cert, Valid ID.\n📎 Burial: Death Cert, Funeral Receipt, Barangay Cert, Valid ID.",
            'solo parent' => "👩 **Solo Parent** — Para sa nag-iisang magulang.\n✅ Eligibility: Anak below 18, income below ₱250,000.\n📎 Kailangan: Birth Cert, Barangay Cert, Proof of Income, Valid ID.",
            'slp'         => "💼 **SLP** — Livelihood at skills training.\n✅ Eligibility: Low-income Filipino.\n📎 Kailangan: Barangay Cert, Valid ID, Proof of income.",
        ];
        return $info[$key] ?? "Pakitanong sa MSWDO office para sa karagdagang impormasyon.";
    }

    private function systemInfo(string $key, string $lang = 'tl'): string
    {
        if (str_contains($key, 'register') || str_contains($key, 'sign up') || str_contains($key, 'mag-register') || str_contains($key, 'mag register')) {
            if ($lang === 'en') {
                return "📝 **How to register in the MSWDO system**\n" .
                    "• Go to the **/register** page.\n" .
                    "• Fill out: Full Name, Username, Email, Mobile Number, Gender, Birthdate, Municipality, and Barangay.\n" .
                    "• You must be **18 years old and above** and choose a valid municipality/barangay.\n" .
                    "• After submitting, you will receive an **OTP by email**.\n" .
                    "• Enter the OTP on the verification page.\n" .
                    "• Once verified, you will set a **new password** before fully accessing your account.";
            }
            return "📝 **Paano mag-register sa MSWDO system**\n" .
                "• Pumunta sa **/register** page.\n" .
                "• Punan ang: Full Name, Username, Email, Mobile Number, Gender, Birthdate, Municipality, at Barangay.\n" .
                "• Dapat **18 years old pataas** at valid ang municipality/barangay.\n" .
                "• Pag-submit, makakatanggap ka ng **OTP sa email**.\n" .
                "• I-enter ang OTP sa verification page.\n" .
                "• Kapag verified na, iseset mo ang **new password** bago tuluyang makapasok sa account.";
        }

        if (str_contains($key, 'otp') || str_contains($key, 'verify')) {
            if ($lang === 'en') {
                return "🔐 **OTP Verification Process**\n" .
                    "• After registration, a **6-digit OTP** will be sent to your email.\n" .
                    "• Enter the OTP on the verification page.\n" .
                    "• If the OTP is correct, your account setup will continue.\n" .
                    "• If it expires, you can request a **resend OTP**.\n" .
                    "• In the current registration flow, after OTP verification you will set a new password.";
            }
            return "🔐 **OTP Verification Process**\n" .
                "• Pagkatapos ng registration, may **6-digit OTP** na ipapadala sa email.\n" .
                "• I-enter ang OTP sa verification page.\n" .
                "• Kapag tama ang OTP, mafo-finalize ang account.\n" .
                "• Kapag expired, puwedeng mag-request ng **resend OTP**.\n" .
                "• Sa bagong registration flow, pagkatapos ma-verify ang OTP ay magse-set ka muna ng bagong password.";
        }

        if (str_contains($key, 'password')) {
            if ($lang === 'en') {
                return "🔑 **Password Help**\n" .
                    "• In the registration flow, there is a password setup step after OTP verification.\n" .
                    "• Once the account is verified, you need to set a **new password** before fully logging in.\n" .
                    "• If login fails, double-check the username/email and password you entered.";
            }
            return "🔑 **Password Help**\n" .
                "• Sa registration flow, may temporary/password setup step pagkatapos ng OTP verification.\n" .
                "• Kapag verified na ang account, kailangan mong mag-set ng **bagong password** bago tuluyang makapasok.\n" .
                "• Kung mali ang login password, i-check muna kung tama ang username/email at password na ginagamit mo.";
        }

        if ($lang === 'en') {
            return "🔓 **How to log in to the MSWDO system**\n" .
                "• Go to the **/login** page.\n" .
                "• Enter your **username or email** in the login field.\n" .
                "• Enter your password, then submit.\n" .
                "• If you are a regular user and your email is not yet verified, you will be redirected to the **OTP verification** page.\n" .
                "• If your account is inactive, you need to **contact the administrator**.\n" .
                "• After successful login, you will be redirected to the correct dashboard based on your role.";
        }
        return "🔓 **Paano mag-login sa MSWDO system**\n" .
            "• Pumunta sa **/login** page.\n" .
            "• Ilagay ang **username o email** sa login field.\n" .
            "• Ilagay ang iyong password, then i-submit.\n" .
            "• Kapag regular user at hindi pa verified ang email, ire-redirect ka sa **OTP verification** page.\n" .
            "• Kapag inactive ang account, kailangan mong **makipag-contact sa administrator**.\n" .
            "• Pag successful ang login, automatic kang dadalhin sa tamang dashboard depende sa role mo.";
    }

    private function detectLanguage(string $text): string
    {
        $t = strtolower($text);
        $tagalogHits = 0;
        $englishHits = 0;

        foreach (['paano', 'ano', 'bakit', 'saan', 'kailan', 'pwede', 'gusto', 'kailangan', 'tulong', 'ako', 'ko', 'mag ', ' ba '] as $word) {
            if (str_contains($t, $word)) $tagalogHits++;
        }

        foreach (['how', 'what', 'why', 'where', 'when', 'can i', 'please', 'system', 'process', 'requirements', 'apply', 'login', 'register'] as $word) {
            if (str_contains($t, $word)) $englishHits++;
        }

        return $englishHits > $tagalogHits ? 'en' : 'tl';
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Personal application status helpers
    // ──────────────────────────────────────────────────────────────────────────

    private function isPersonalApplicationQuestion(string $message): bool
    {
        $t = strtolower(trim($message));

        $patterns = [
            '/\bmy\s+(application|status|progress|pwd|4ps|solo\s*parent|aics|burial|medical|slp|senior)\b/',
            '/\b(check|track|monitor)\s+my\b/',
            '/\b(application|aplikasyon)\s+(status|progress|process)\s+(ko|mine)\b/',
            '/\b(aplikasyon|status|proseso|progress)\s+ko\b/',
            '/\baking\s+(aplikasyon|application)\b/',
            '/\bprocess\s+of\s+my\b/',
            '/\bstatus\s+of\s+my\b/',
            '/\bprogress\s+of\s+my\b/',
            '/\bcan\s+(you|i)\s+check\s+my\b/',
            '/\bmy\s+\w+\s+application\b/',
            '/\bpaano\s+na\s+(ang\s+)?aplikasyon\s+ko\b/',
            '/\bsaan\s+na\s+(ang\s+)?aplikasyon\s+ko\b/',
            '/\bproseso\s+ng\s+(aking\s+)?aplikasyon\b/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $t)) {
                return true;
            }
        }

        if (preg_match('/\b(check|track|monitor)\b/', $t)
            && preg_match('/\b(application|aplikasyon|status)\b/', $t)
            && preg_match('/\b(my|ko|mine|aking)\b/', $t)) {
            return true;
        }

        return false;
    }

    private function detectProgramLabel(string $message): ?string
    {
        $t = strtolower($message);

        return match (true) {
            str_contains($t, '4ps') || str_contains($t, 'pantawid') => '4Ps',
            str_contains($t, 'solo parent') || str_contains($t, 'solo-parent') || str_contains($t, 'solo parent') => 'Solo Parent',
            str_contains($t, 'burial') || str_contains($t, 'libing') => 'AICS Burial',
            str_contains($t, 'medical') || str_contains($t, 'medikal') => 'AICS Medical',
            str_contains($t, 'aics') => 'AICS',
            str_contains($t, 'pwd') || str_contains($t, 'person with disability') => 'PWD',
            str_contains($t, 'slp') || str_contains($t, 'livelihood') => 'SLP',
            str_contains($t, 'senior') => 'Senior Citizen',
            default => null,
        };
    }

    private function loginRequiredMessage(string $lang, ?string $programLabel = null): string
    {
        $program = $programLabel ? " for this {$programLabel} program" : '';
        $programTl = $programLabel ? " para sa {$programLabel} program" : '';

        if ($lang === 'en') {
            return "Please log in to your account first so I can help you check the current status and process of your application{$program}.";
        }

        return "Pakilog-in muna sa inyong account para matulungan ko kayong suriin ang kasalukuyang status at proseso ng inyong aplikasyon{$programTl}.";
    }

    private function noApplicationMessage(string $lang): string
    {
        if ($lang === 'en') {
            return "I couldn't find an application associated with your account. Please make sure you are using the correct account or contact the appropriate system administrator for assistance.";
        }

        return "Wala akong makitang aplikasyon na naka-link sa account na ito. Siguraduhing tama ang account na ginagamit ninyo o makipag-ugnayan sa system administrator para sa tulong.";
    }

    private function buildUserApplicationContext($user): string
    {
        $lines = [];

        $applications = Application::where('user_id', $user->id)
            ->orderByDesc('application_date')
            ->get();

        foreach ($applications as $app) {
            $fm = FileMonitoring::where('application_id', $app->id)->first();
            $docStatus = $fm?->overall_status ?? 'none';
            $date = $app->application_date
                ? $app->application_date->format('Y-m-d')
                : 'n/a';

            $parts = [
                "Program:{$app->program_type}",
                "AppStatus:{$app->status}",
                "Stage:" . ($app->stage ?? 'none'),
                "IdStatus:" . ($app->id_status ?? 'none'),
                "Documents:{$docStatus}",
                "Applied:{$date}",
            ];

            if ($app->completed_at) {
                $parts[] = 'Completed:' . $app->completed_at->format('Y-m-d');
            }
            if ($app->id_ready_at) {
                $parts[] = 'IdReady:' . $app->id_ready_at->format('Y-m-d');
            }
            if ($app->admin_remarks) {
                $parts[] = 'AdminNote:' . str_replace(["\n", '|'], ' ', $app->admin_remarks);
            }

            $lines[] = 'Application | ' . implode(' | ', $parts);
        }

        $appointments = Appointment::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled'])
            ->orderByDesc('appointment_date')
            ->get()
            ->unique(fn ($a) => $a->program_type);

        foreach ($appointments as $appt) {
            $parts = [
                "Program:{$appt->program_type}",
                "ApptStatus:{$appt->status}",
                "Date:{$appt->formatted_date}",
                "Time:{$appt->formatted_time}",
                "Interview:{$appt->interview_label}",
            ];

            if ($appt->reschedule_status === 'pending') {
                $parts[] = 'Reschedule:pending';
            }
            if ($appt->cancellation_status === 'pending') {
                $parts[] = 'Cancellation:pending';
            }
            if ($appt->validated_at) {
                $parts[] = 'Validated:' . $appt->validated_at->format('Y-m-d');
            }
            if ($appt->admin_notes) {
                $parts[] = 'AdminNote:' . str_replace(["\n", '|'], ' ', $appt->admin_notes);
            }

            $lines[] = 'Appointment | ' . implode(' | ', $parts);
        }

        return implode("\n", $lines);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Database helpers
    // ──────────────────────────────────────────────────────────────────────────

    private function getLatestData(): array
    {
        $result = [];
        foreach ($this->coreNames as $name) {
            $row = MunicipalityYearlySummary::where('municipality', $name)
                ->orderByDesc('year')
                ->first();
            if ($row) {
                $result[$name] = [
                    'year'        => $row->year,
                    'population'  => (int) $row->total_population,
                    'male'        => (int) $row->male_population,
                    'female'      => (int) $row->female_population,
                    'age_0_19'    => (int) $row->population_0_19,
                    'age_20_59'   => (int) $row->population_20_59,
                    'age_60'      => (int) $row->population_60_100,
                    'households'  => (int) $row->total_households,
                    'pwd'         => (int) $row->total_pwd,
                    'aics'        => (int) $row->total_aics,
                    'solo_parent' => (int) $row->total_solo_parent,
                    'four_ps'     => (int) $row->total_4ps,
                    'senior'      => (int) $row->total_senior,
                ];
            }
        }
        return $result;
    }

    private function buildTrendData(): array
    {
        $lines = [];
        foreach ($this->coreNames as $name) {
            $rows = MunicipalityYearlySummary::where('municipality', $name)
                ->orderBy('year')
                ->get(['year', 'total_population', 'total_households',
                       'total_pwd', 'total_aics', 'total_solo_parent',
                       'total_4ps', 'total_senior']);

            foreach ($rows as $row) {
                $benef = (int)$row->total_pwd + (int)$row->total_aics +
                         (int)$row->total_solo_parent + (int)$row->total_4ps + (int)$row->total_senior;
                $lines[] = "{$name}|{$row->year}|Pop:" . number_format($row->total_population) .
                    "|HH:" . number_format($row->total_households) .
                    "|Benef:" . number_format($benef);
            }
        }
        return $lines;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MunicipalityYearlySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private array $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

    /**
     * Handle a chat message.
     * ALL messages go through Gemini with live data injected.
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

        $apiKey = config('services.groq.key');
        $model  = config('services.groq.model', 'llama-3.3-70b-versatile');

        if (!$apiKey) {
            return response()->json([
                'reply' => '⚠️ Chatbot is not configured. Please contact the administrator. (GROQ_API_KEY missing.)'
            ]);
        }

        // ── Build compact live-data context ───────────────────────────────────
        $liveData     = $this->getLatestData();
        $dataTable    = $this->buildCompactTable($liveData);
        $systemPrompt = $this->buildSystemPrompt($dataTable);

        // ── Build messages array (OpenAI-compatible format) ────────────────────
        // GroqCloud uses: [system, ...history turns as user/assistant, current user]
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        // Map past history: Gemini uses 'model' role, Groq uses 'assistant'
        $history = collect($request->input('history', []))
            ->slice(-6)
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
            \Log::error('Groq API error', ['status' => $status, 'body' => $response->body()]);

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
    private function buildSystemPrompt(string $dataTable): string
    {
        return <<<PROMPT
You are the MSWDO AI Assistant for Liliw, Majayjay, and Magdalena, Laguna, Philippines. Powered by GroqCloud AI.

=== LIVE SYSTEM DATA (use this to answer data-related questions) ===
{$dataTable}
=== END DATA ===

=== SYSTEM STRUCTURE & NAVIGATION ===
The MSWDO system has the following sections in the NAVBAR:

1. HOME - Dashboard/landing page
2. ANALYSIS - Dropdown menu with:
   • Programs Analysis - Statistical analysis of all programs
   • Demographic Analysis - Population, age, gender, households analysis
3. PROGRAMS - Dropdown menu for:
   • 4Ps Program
   • PWD Program
   • AICS Program (Medical Assistance, Burial Assistance, Emergency Shelter)
   • Solo Parent Program
   • Senior Citizen Program
   • SLP (Sustainable Livelihood Program)
4. APPLY - To apply for programs
5. ABOUT - Information about MSWDO
6. CONTACT - Contact information
7. LOGIN/REGISTER - For user authentication

The ANALYSIS section has two pages:
• Programs Analysis - Contains 10 sections: Descriptive Analysis, Population Growth Trend, Gender Distribution Trend, Age Group Distribution, Household vs Population, Program Beneficiaries Comparison, ANOVA Test Results, Correlation Analysis, Key Insights, and Recommendations
• Demographic Analysis - Contains 12 sections: Population Overview, Geographic Distribution Map, Gender Distribution, Age Structure, Household Analysis, Beneficiaries by Program, and Detailed Data Table

The APPLICATION PROCESS:
• Users must register first (18+ years old)
• After registration, receive OTP via email
• Verify OTP and set password
• Once logged in, can apply for programs
• Requirements depend on the program
=== END SYSTEM STRUCTURE ===

RULES:
1. Use ONLY the data above. Do not invent numbers.
2. If no data available, say: "No data available in the system for this."
3. ⚠️ CRITICAL LANGUAGE RULE - STRICTLY FOLLOW:
   - ALWAYS detect the user's message language FIRST before responding
   - If user writes in ENGLISH → Reply ONLY in ENGLISH (entire response)
   - If user writes in TAGALOG → Reply ONLY in TAGALOG (entire response)
   - If user writes in MIXED/TAGLISH → Reply in MIXED/TAGLISH
   - NEVER switch languages mid-conversation unless the user switches first
   - Language examples:
     * User: "What is in the analysis navbar?" → Reply fully in ENGLISH
     * User: "Ano ang laman ng analysis sa navbar?" → Reply fully in TAGALOG
     * User: "How to login?" → Reply fully in ENGLISH
     * User: "Paano mag-login?" → Reply fully in TAGALOG
4. Use bullet points (•) when enumerating.
5. Your scope covers: MSWDO system (navigation, features, pages, structure), MSWDO programs, application process, login/register/OTP flow, and system analysis data.
6. For questions about system navigation or features - answer using the SYSTEM STRUCTURE information above.
7. For questions COMPLETELY unrelated to MSWDO (e.g., cooking recipes, sports, entertainment, personal advice) - politely say you can only answer system-related questions.
8. For questions about SYSTEM NAVIGATION or FEATURES:
   - Explain what can be found on that page/section
   - Mention available options or sub-menus
   - If there's data visualization, mention what insights can be seen
9. For questions about DATA (population, age, gender, households, beneficiaries):
   - Mention all three municipalities
   - Compare the data
   - Use number_format (example: 39,977)
10. For questions about PROGRAMS (4Ps, PWD, AICS, Solo Parent, SLP):
   - Explain the program
   - Provide eligibility and requirements
   - Invite to apply
11. For "how to apply" or "want to apply":
   - Ask which program (one question first)
   - Follow with eligibility check, step-by-step
12. For questions about LOGIN:
   - Say to go to /login
   - Can use username or email
   - Enter password
   - If user and email not verified, redirect to OTP verification
   - If account inactive, say to contact administrator
13. For questions about REGISTER:
   - Say to go to /register
   - Fill out: full name, username, email, mobile number, gender, birthdate, municipality, barangay
   - Must be 18+ years old and valid municipality/barangay
   - Will receive OTP via email
   - After correct OTP, set new password before fully accessing account
14. Be concise but complete. Responses should not be too long unless necessary.

PROGRAM INFO:
• 4Ps: Cash transfer for poor families. Requirements: Certificate of Indigency, Birth Certificate of child, Valid ID.
• PWD: For persons with disabilities. Requirements: Medical Certificate, Valid ID, 1x1 photo, Barangay Certificate.
• AICS Medical: For hospital/medicine. Requirements: Medical Certificate, Hospital Bill, Barangay Certificate of Indigency, Valid ID.
• AICS Burial: For burial expenses. Requirements: Death Certificate, Funeral Receipt, Barangay Certificate, Valid ID.
• Solo Parent: For single parents with children below 18, income below ₱250,000. Requirements: Birth Certificate, Barangay Certificate, Proof of Income, Valid ID, 2x2 photo.
• SLP: Livelihood training. Requirements: Barangay Certificate, Valid ID, Proof of low income.
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

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
Ikaw ay ang MSWDO AI Assistant para sa Liliw, Majayjay, at Magdalena, Laguna, Philippines. Pinapagana ka ng GroqCloud AI.

=== LIVE SYSTEM DATA (gamitin ito para sumagot tungkol sa datos) ===
{$dataTable}
=== END DATA ===

MGA PATAKARAN:
1. Gamitin LAMANG ang datos sa itaas. Huwag mag-imbento ng numero.
2. Kung walang datos, sabihin: "Walang available na data sa system para dito."
3. Sumagot sa Taglish (halo ng Tagalog at English). Maging magalang at malinaw.
4. Gamitin ang bullet points (•) kapag nag-eenumerate.
5. Para sa tanong tungkol sa DATOS (population, age, gender, households, beneficiaries):
   - Banggitin ang lahat ng tatlong munisipyo
   - Ihambing ang mga datos
   - Gumamit ng number_format (halimbawa: 39,977)
6. Para sa tanong tungkol sa MGA PROGRAMA (4Ps, PWD, AICS, Solo Parent, SLP):
   - Ipaliwanag ang programa
   - Ibigay ang eligibility at requirements
   - Anyayahan na mag-apply
7. Para sa "paano mag-apply" o "gusto mag-apply":
   - Tanungin kung anong programa (isang tanong lang muna)
   - Sundan ang eligibility check, hakbang-hakbang
8. Maging maikli ngunit kumpleto. Hindi dapat mahaba ang sagot maliban kung kinakailangan.

PROGRAMA INFO:
• 4Ps: Cash transfer para sa mahirap na pamilya. Kailangan: Cert of Indigency, Birth Cert ng anak, Valid ID.
• PWD: Para sa may kapansanan. Kailangan: Medical Cert, Valid ID, 1x1 photo, Barangay Cert.
• AICS Medical: Para sa ospital/gamot. Kailangan: Medical Cert, Hospital Bill, Barangay Cert of Indigency, Valid ID.
• AICS Burial: Para sa libing. Kailangan: Death Cert, Funeral Receipt, Barangay Cert, Valid ID.
• Solo Parent: Para sa nag-iisang magulang na may anak below 18, income below ₱250,000. Kailangan: Birth Cert, Barangay Cert, Proof of Income, Valid ID, 2x2 photo.
• SLP: Livelihood training. Kailangan: Barangay Cert, Valid ID, Proof of low income.
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

        if (str_contains($key, 'population') || str_contains($key, 'populasyon')) {
            $lines = ["📊 **Population (Latest Year)**\n"];
            foreach ($data as $muni => $row) {
                $lines[] = "• **{$muni}** ({$row['year']}): " . number_format($row['population']) . " katao";
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
                $lines[] = "• **{$muni}**: " . number_format($row['households']) . " kabahayan (avg {$avg} tao/bahay)";
            }
            return implode("\n", $lines);
        }

        if (str_contains($key, 'age') || str_contains($key, 'edad')) {
            $lines = ["📅 **Age Structure**\n"];
            foreach ($data as $muni => $row) {
                $lines[] = "• **{$muni}**: Youth(0-19)=" . number_format($row['age_0_19']) .
                    " | Working(20-59)=" . number_format($row['age_20_59']) .
                    " | Senior(60+)=" . number_format($row['age_60']);
            }
            return implode("\n", $lines);
        }

        if (in_array($key, ['4ps', 'pwd', 'aics', 'solo parent', 'slp'])) {
            return $this->programInfo($key);
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

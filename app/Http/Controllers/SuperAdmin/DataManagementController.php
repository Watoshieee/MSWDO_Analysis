<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
use App\Models\MunicipalityMonthlySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DataManagementController extends Controller
{
    /**
     * Display data management dashboard
     */
    public function dashboard()
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();

        $totalPrograms = SocialWelfareProgram::count();
        $totalBeneficiaries = SocialWelfareProgram::sum('beneficiary_count');
        $totalBarangays = Barangay::count();

        $recentUpdates = SocialWelfareProgram::orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('superadmin.data.dashboard', compact(
            'municipalities',
            'totalPrograms',
            'totalBeneficiaries',
            'totalBarangays',
            'recentUpdates'
        ));
    }

    /**
     * Display municipality data management — per-year using municipality_yearly_summary
     */
    public function municipalities(Request $request)
    {
        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

        // Summary records grouped by municipality + year
        $summaries = MunicipalityYearlySummary::whereIn('municipality', $coreNames)
            ->orderBy('municipality')
            ->orderBy('year', 'desc')
            ->get()
            ->groupBy('municipality');

        $years = array_merge([2015, 2020], range(date('Y') - 3, date('Y') + 1));
        $years = array_unique($years);
        rsort($years);

        // Chart data: population per year per municipality
        $chartData = [];
        foreach ($coreNames as $name) {
            $rows = MunicipalityYearlySummary::where('municipality', $name)
                ->orderBy('year')
                ->get();
            $chartData[$name] = [
                'years' => $rows->pluck('year')->toArray(),
                'population' => $rows->pluck('total_population')->toArray(),
                'households' => $rows->pluck('total_households')->toArray(),
            ];
        }

        // Monthly summaries grouped by municipality
        $monthlySummaries = MunicipalityMonthlySummary::whereIn('municipality', $coreNames)
            ->orderBy('municipality')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy('municipality');

        // Monthly chart data: PWD + AICS + Solo per month for each municipality
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $selectedYear = $request->get('chart_year', date('Y'));
        $monthlyChartData = [];
        foreach ($coreNames as $muni) {
            $rows = MunicipalityMonthlySummary::where('municipality', $muni)
                ->where('year', $selectedYear)
                ->orderBy('month')
                ->get()
                ->keyBy('month');
            $monthlyChartData[$muni] = [
                'pwd' => array_map(fn($m) => $rows->get($m)?->total_pwd ?? 0, range(1, 12)),
                'aics' => array_map(fn($m) => $rows->get($m)?->total_aics ?? 0, range(1, 12)),
                'solo_parent' => array_map(fn($m) => $rows->get($m)?->total_solo_parent ?? 0, range(1, 12)),
            ];
        }

        $availableYears = MunicipalityMonthlySummary::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        if (empty($availableYears))
            $availableYears = [date('Y')];

        return view('superadmin.data.municipalities', compact(
            'summaries',
            'coreNames',
            'years',
            'chartData',
            'monthlySummaries',
            'monthlyChartData',
            'monthNames',
            'selectedYear',
            'availableYears'
        ));
    }

    /**
     * Upsert municipality yearly summary (create or update)
     */
    public function saveMunicipalitySummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality'     => 'required|in:Magdalena,Liliw,Majayjay',
            'year'             => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'total_population' => 'required|integer|min:0',
            'population_0_19'  => 'nullable|integer|min:0',
            'population_20_59' => 'nullable|integer|min:0',
            'population_60_100'=> 'nullable|integer|min:0',
            'total_households' => 'required|integer|min:0',
            'total_4ps'        => 'nullable|integer|min:0',
            'total_pwd'        => 'nullable|integer|min:0',
            'total_senior'     => 'nullable|integer|min:0',
            'total_aics'       => 'nullable|integer|min:0',
            'total_esa'        => 'nullable|integer|min:0',
            'total_slp'        => 'nullable|integer|min:0',
            'total_solo_parent'=> 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        MunicipalityYearlySummary::updateOrCreate(
            [
                'municipality' => $request->municipality,
                'year'         => $request->year,
            ],
            [
                'total_population'  => $request->total_population,
                'population_0_19'   => $request->population_0_19 ?? 0,
                'population_20_59'  => $request->population_20_59 ?? 0,
                'population_60_100' => $request->population_60_100 ?? 0,
                'total_households'  => $request->total_households,
                'total_4ps'         => $request->total_4ps ?? 0,
                'total_pwd'         => $request->total_pwd ?? 0,
                'total_senior'      => $request->total_senior ?? 0,
                'total_aics'        => $request->total_aics ?? 0,
                'total_esa'         => $request->total_esa ?? 0,
                'total_slp'         => $request->total_slp ?? 0,
                'total_solo_parent' => $request->total_solo_parent ?? 0,
                'created_at'        => now(),
            ]
        );

        return redirect()->route('superadmin.data.municipalities')
            ->with('success', "Data for {$request->municipality} ({$request->year}) saved successfully!");
    }




    /**
     * Display barangay data management page
     */
    public function barangays(Request $request)
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->pluck('name');

        $query = Barangay::query();
        if ($request->filled('municipality')) {
            $query->where('municipality', $request->municipality);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $barangays = $query
            ->orderBy('municipality')
            ->orderBy('name')
            ->orderBy('year', 'desc')
            ->get();

        $years = array_merge([2015, 2020], range(date('Y') - 3, date('Y') + 1));
        $years = array_unique($years);
        rsort($years);

        // Available years per municipality for the empty-state hint
        $availableYears = [];
        foreach (['Magdalena', 'Liliw', 'Majayjay'] as $mun) {
            $availableYears[$mun] = Barangay::where('municipality', $mun)
                ->distinct()->orderByDesc('year')->pluck('year')->toArray();
        }

        return view('superadmin.data.barangays', compact('barangays', 'municipalities', 'years', 'availableYears'));
    }

    /**
     * Update barangay data
     */
    public function updateBarangay(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'total_population' => 'required|integer|min:0',
            'population_0_19' => 'nullable|integer|min:0',
            'population_20_59' => 'nullable|integer|min:0',
            'population_60_100' => 'nullable|integer|min:0',
            'pwd_count' => 'nullable|integer|min:0',
            'aics_count' => 'nullable|integer|min:0',
            'year' => 'nullable|integer|min:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $barangay = Barangay::findOrFail($id);
        $barangay->update([
            'male_population' => $request->total_population,
            'female_population' => 0,
            'population_0_19' => $request->population_0_19 ?? 0,
            'population_20_59' => $request->population_20_59 ?? 0,
            'population_60_100' => $request->population_60_100 ?? 0,
            'single_parent_count' => $request->single_parent_count ?? 0,
            'pwd_count' => $request->pwd_count ?? 0,
            'aics_count' => $request->aics_count ?? 0,
            'year' => $request->year ?? date('Y'),
        ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Barangay updated.']);
        }
        return redirect()->back()->with('success', 'Barangay data updated successfully!');
    }

    /**
     * Store new barangay data (or update if exists)
     */
    public function storeBarangay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'required|in:Magdalena,Liliw,Majayjay',
            'name' => 'required|string|max:100',
            'year' => 'required|integer|min:2000',
            'total_population' => 'required|integer|min:0',
            'population_0_19' => 'nullable|integer|min:0',
            'population_20_59' => 'nullable|integer|min:0',
            'population_60_100' => 'nullable|integer|min:0',
            'pwd_count' => 'nullable|integer|min:0',
            'aics_count' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Use updateOrCreate to avoid duplicates
        Barangay::updateOrCreate(
            [
                'municipality' => $request->municipality,
                'name' => $request->name,
                'year' => $request->year,
            ],
            [
                'male_population' => $request->total_population,
                'female_population' => 0,
                'population_0_19' => $request->population_0_19 ?? 0,
                'population_20_59' => $request->population_20_59 ?? 0,
                'population_60_100' => $request->population_60_100 ?? 0,
                'single_parent_count' => $request->single_parent_count ?? 0,
                'pwd_count' => $request->pwd_count ?? 0,
                'aics_count' => $request->aics_count ?? 0,
                'total_households' => 0,
                'total_approved_applications' => 0,
            ]
        );

        return redirect()->route('superadmin.data.barangays')
            ->with('success', "Barangay data for {$request->name} ({$request->year}) saved successfully!");
    }

    /**
     * Bulk-add all barangays for a municipality at once
     */
    public function bulkStoreBarangays(Request $request)
    {
        $request->validate([
            'municipality' => 'required|string',
            'year'         => 'required|integer',
            'barangays'    => 'required|array|min:1',
            'barangays.*'  => 'required|string',
        ]);

        $created = 0;
        $restored = 0;

        foreach ($request->barangays as $barangayName) {
            $name = trim($barangayName);

            // Look up any existing record, including soft-deleted ones
            $existing = Barangay::withTrashed()
                ->where('municipality', $request->municipality)
                ->where('name', $name)
                ->where('year', $request->year)
                ->first();

            if ($existing) {
                // If it was archived, restore it
                if ($existing->trashed()) {
                    $existing->restore();
                    $restored++;
                }
                // Already exists and not trashed — skip (don't overwrite real data)
            } else {
                // Fresh create
                Barangay::create([
                    'municipality'               => $request->municipality,
                    'name'                       => $name,
                    'year'                       => $request->year,
                    'male_population'            => 0,
                    'female_population'          => 0,
                    'population_0_19'            => 0,
                    'population_20_59'           => 0,
                    'population_60_100'          => 0,
                    'single_parent_count'        => 0,
                    'pwd_count'                  => 0,
                    'aics_count'                 => 0,
                    'total_households'           => 0,
                    'total_approved_applications'=> 0,
                ]);
                $created++;
            }
        }

        $msg = "{$created} barangay record(s) added";
        if ($restored) $msg .= ", {$restored} archived record(s) restored";
        $msg .= " for {$request->municipality} ({$request->year}).";

        return response()->json(['success' => true, 'created' => $created, 'restored' => $restored, 'message' => $msg]);
    }

    /**
     * Bulk update multiple barangay rows (used by inline-edit "Update All")
     */
    public function bulkUpdateBarangays(Request $request)
    {
        $rows = $request->input('rows', []);
        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'No data received.'], 422);
        }

        $updated = 0;
        foreach ($rows as $row) {
            $barangay = Barangay::find($row['id'] ?? null);
            if (!$barangay)
                continue;
            $barangay->update([
                'male_population'     => intval($row['total_population'] ?? 0),
                'female_population'   => 0,
                'single_parent_count' => intval($row['single_parent_count'] ?? 0),
                'pwd_count' => intval($row['pwd_count'] ?? 0),
                'aics_count' => intval($row['aics_count'] ?? 0),
                'year' => intval($row['year'] ?? date('Y')),
            ]);
            $updated++;
        }

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'message' => "{$updated} barangay record(s) updated successfully!"
        ]);
    }

    /**
     * Delete barangay data
     */
    public function deleteBarangay($id)
    {
        $barangay = Barangay::findOrFail($id);
        $name = $barangay->name;
        $year = $barangay->year;
        $barangay->delete();

        return response()->json([
            'success' => true,
            'message' => "Barangay data for {$name} ({$year}) deleted successfully!"
        ]);
    }

    /**
     * Bulk delete barangay data by municipality and year
     */
    public function bulkDeleteBarangays(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'required|in:Magdalena,Liliw,Majayjay',
            'year' => 'required|integer|min:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid municipality or year.'
            ], 422);
        }

        $count = Barangay::where('municipality', $request->municipality)
            ->where('year', $request->year)
            ->delete();

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "Deleted {$count} barangay records for {$request->municipality} ({$request->year})."
        ]);
    }

    /**
     * Display social welfare programs data management with per-year chart
     */
    public function programs(Request $request)
    {
        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];
        $municipalities = Municipality::whereIn('name', $coreNames)->pluck('name');

        $programTypes = [
            '4Ps' => '4Ps',
            'Senior_Citizen_Pension' => 'Senior Citizen Pension',
            'PWD_Assistance' => 'PWD Assistance',
            'AICS' => 'AICS',
            'SLP' => 'SLP',
            'ESA' => 'ESA',
            'Solo_Parent' => 'Solo Parent',
        ];

        $years = array_merge([2015, 2020], range(date('Y') - 3, date('Y') + 1));
        $years = array_unique($years);
        rsort($years);

        $query = SocialWelfareProgram::query();
        if ($request->filled('municipality')) {
            $query->where('municipality', $request->municipality);
        }
        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        $programs = $query->orderBy('year', 'desc')->orderBy('municipality')->paginate(20);

        // ── Compute live barangay aggregates for AICS, PWD, Solo Parent ──
        $barangayAggregates = Barangay::whereIn('municipality', $coreNames)
            ->select(
                'municipality',
                'year',
                DB::raw('SUM(aics_count)          AS total_aics'),
                DB::raw('SUM(pwd_count)            AS total_pwd'),
                DB::raw('SUM(single_parent_count)  AS total_solo_parent')
            )
            ->groupBy('municipality', 'year')
            ->orderBy('municipality')
            ->orderBy('year', 'desc')
            ->get();

        // Build lookup: municipality|year => ['AICS'=>x, 'PWD'=>y, 'Solo'=>z]
        $barangayLookup = [];
        foreach ($barangayAggregates as $row) {
            $barangayLookup["{$row->municipality}|{$row->year}"] = [
                'AICS' => (int) $row->total_aics,
                'PWD' => (int) $row->total_pwd,
                'Solo_Parent' => (int) $row->total_solo_parent,
            ];
        }

        // Chart: beneficiaries per year per municipality (all programs combined)
        $allYears = SocialWelfareProgram::select('year')
            ->distinct()
            ->whereIn('municipality', $coreNames)
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        $chartColors = ['#2C3E8F', '#FDB913', '#C41E24'];
        $chartDatasets = [];
        foreach ($coreNames as $i => $muni) {
            $totals = [];
            foreach ($allYears as $y) {
                $totals[] = (int) SocialWelfareProgram::where('municipality', $muni)
                    ->where('year', $y)
                    ->sum('beneficiary_count');
            }
            $chartDatasets[] = [
                'label' => $muni,
                'data' => $totals,
                'backgroundColor' => $chartColors[$i],
            ];
        }

        $programChartData = [];
        foreach ($programTypes as $key => $label) {
            $row = ['label' => $label, 'data' => []];
            foreach ($allYears as $y) {
                $q = SocialWelfareProgram::where('program_type', $key)->where('year', $y);
                if ($request->filled('municipality')) {
                    $q->where('municipality', $request->municipality);
                }
                $row['data'][] = (int) $q->sum('beneficiary_count');
            }
            $programChartData[] = $row;
        }

        return view('superadmin.data.programs', compact(
            'programs',
            'municipalities',
            'programTypes',
            'years',
            'allYears',
            'chartDatasets',
            'programChartData',
            'barangayAggregates',
            'barangayLookup'
        ));
    }

    /**
     * Sync barangay totals (AICS, PWD, Solo Parent) → social_welfare_programs
     */
    public function syncFromBarangays()
    {
        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

        $aggregates = Barangay::whereIn('municipality', $coreNames)
            ->select(
                'municipality',
                'year',
                DB::raw('SUM(aics_count)         AS total_aics'),
                DB::raw('SUM(pwd_count)           AS total_pwd'),
                DB::raw('SUM(single_parent_count) AS total_solo_parent')
            )
            ->groupBy('municipality', 'year')
            ->get();

        $synced = 0;
        foreach ($aggregates as $row) {
            $map = [
                'AICS' => (int) $row->total_aics,
                'PWD_Assistance' => (int) $row->total_pwd,
                'Solo_Parent' => (int) $row->total_solo_parent,
            ];
            foreach ($map as $programType => $count) {
                SocialWelfareProgram::updateOrCreate(
                    [
                        'municipality' => $row->municipality,
                        'program_type' => $programType,
                        'year' => $row->year,
                    ],
                    [
                        'beneficiary_count' => $count,
                        'barangay' => null,
                    ]
                );
                $synced++;
            }
        }

        return redirect()->route('superadmin.data.programs')
            ->with('success', "✅ Synced {$synced} program records from barangay data!");
    }

    /**
     * Update social welfare program data
     */
    public function updateProgram(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'beneficiary_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $program = SocialWelfareProgram::findOrFail($id);
        $program->update([
            'beneficiary_count' => $request->beneficiary_count,
            'year' => $request->year,
        ]);

        return redirect()->back()->with('success', 'Program data updated successfully!');
    }

    /**
     * Create new program data
     */
    public function createProgram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'required|in:Magdalena,Liliw,Majayjay',
            'program_type' => 'required|in:4Ps,Senior_Citizen_Pension,PWD_Assistance,AICS,SLP,ESA,Solo_Parent',
            'beneficiary_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Upsert — update if exists, create if not
        SocialWelfareProgram::updateOrCreate(
            [
                'municipality' => $request->municipality,
                'program_type' => $request->program_type,
                'year' => $request->year,
            ],
            [
                'beneficiary_count' => $request->beneficiary_count,
                'barangay' => null,
            ]
        );

        return redirect()->back()->with('success', 'Program data saved successfully!');
    }

    /**
     * Archive program data (soft delete) — AJAX
     */
    public function archiveProgram($id)
    {
        $program = SocialWelfareProgram::findOrFail($id);
        $program->delete();
        return response()->json(['success' => true, 'message' => 'Program record archived.']);
    }

    /**
     * Get archived programs as JSON
     */
    public function getArchivedPrograms()
    {
        $archived = SocialWelfareProgram::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return response()->json($archived);
    }

    /**
     * Restore an archived program
     */
    public function restoreProgram($id)
    {
        $program = SocialWelfareProgram::onlyTrashed()->findOrFail($id);
        $program->restore();
        return response()->json(['success' => true, 'message' => 'Program record restored.']);
    }

    /**
     * Permanently delete a program from the database
     */
    public function forceDeleteProgram($id)
    {
        $program = SocialWelfareProgram::onlyTrashed()->findOrFail($id);
        $program->forceDelete();
        return response()->json(['success' => true, 'message' => 'Program record permanently deleted.']);
    }

    /**
     * Delete program data (legacy form-submit — now soft-deletes)
     */
    public function deleteProgram($id)
    {
        $program = SocialWelfareProgram::findOrFail($id);
        $program->delete();
        return redirect()->back()->with('success', 'Program data archived successfully.');
    }

    /**
     * Archive municipality yearly summary (soft delete) — AJAX
     */
    public function archiveMunicipalitySummary($id)
    {
        $summary = \App\Models\MunicipalityYearlySummary::findOrFail($id);
        $summary->delete();
        return response()->json(['success' => true, 'message' => 'Yearly record archived.']);
    }

    /**
     * Get archived municipality summaries as JSON
     */
    public function getArchivedSummaries()
    {
        $archived = \App\Models\MunicipalityYearlySummary::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return response()->json($archived);
    }

    /**
     * Restore an archived municipality summary
     */
    public function restoreMunicipalitySummary($id)
    {
        $summary = \App\Models\MunicipalityYearlySummary::onlyTrashed()->findOrFail($id);
        $summary->restore();
        return response()->json(['success' => true, 'message' => 'Yearly record restored.']);
    }

    /**
     * Permanently delete a municipality summary
     */
    public function forceDeleteMunicipalitySummary($id)
    {
        $summary = \App\Models\MunicipalityYearlySummary::onlyTrashed()->findOrFail($id);
        $summary->forceDelete();
        return response()->json(['success' => true, 'message' => 'Yearly record permanently deleted.']);
    }

    /**
     * Delete municipality summary (legacy form-submit — now soft-deletes)
     */
    public function deleteMunicipalitySummary($id)
    {
        $summary = \App\Models\MunicipalityYearlySummary::findOrFail($id);
        $summary->delete();
        return redirect()->back()->with('success', 'Record archived successfully.');
    }

    // ══════════════════════════════════════════════════════════════
    //  MONTHLY SUMMARY METHODS
    // ══════════════════════════════════════════════════════════════

    /**
     * Save (upsert) a monthly summary record
     */
    public function saveMonthlySummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'required|in:Magdalena,Liliw,Majayjay',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'month' => 'required|integer|min:1|max:12',
            'total_pwd' => 'nullable|integer|min:0',
            'total_aics' => 'nullable|integer|min:0',
            'total_solo_parent' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        MunicipalityMonthlySummary::updateOrCreate(
            [
                'municipality' => $request->municipality,
                'year' => $request->year,
                'month' => $request->month,
            ],
            [
                'total_pwd' => $request->total_pwd ?? 0,
                'total_aics' => $request->total_aics ?? 0,
                'total_solo_parent' => $request->total_solo_parent ?? 0,
                'notes' => $request->notes,
            ]
        );

        $monthName = \Carbon\Carbon::createFromDate(null, $request->month, 1)->format('F');
        return redirect()->route('superadmin.data.municipalities')
            ->with('success', "{$request->municipality} — {$monthName} {$request->year} monthly data saved!");
    }

    /**
     * Edit (update) a specific monthly record
     */
    public function editMonthlySummary(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'total_pwd' => 'nullable|integer|min:0',
            'total_aics' => 'nullable|integer|min:0',
            'total_solo_parent' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $record = MunicipalityMonthlySummary::findOrFail($id);
        $record->update([
            'total_pwd' => $request->total_pwd ?? 0,
            'total_aics' => $request->total_aics ?? 0,
            'total_solo_parent' => $request->total_solo_parent ?? 0,
            'notes' => $request->notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Monthly record updated.', 'record' => $record]);
    }

    /**
     * Archive a monthly record (soft delete)
     */
    public function archiveMonthlySummary($id)
    {
        $record = MunicipalityMonthlySummary::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Monthly record archived.']);
    }

    /**
     * Restore an archived monthly record
     */
    public function restoreMonthlySummary($id)
    {
        $record = MunicipalityMonthlySummary::onlyTrashed()->findOrFail($id);
        $record->restore();
        return response()->json(['success' => true, 'message' => 'Monthly record restored.']);
    }

    /**
     * Permanently delete a monthly record
     */
    public function forceDeleteMonthlySummary($id)
    {
        $record = MunicipalityMonthlySummary::onlyTrashed()->findOrFail($id);
        $record->forceDelete();
        return response()->json(['success' => true, 'message' => 'Monthly record permanently deleted.']);
    }

    /**
     * Get archived monthly records as JSON
     */
    public function getArchivedMonthly()
    {
        $archived = MunicipalityMonthlySummary::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return response()->json($archived);
    }

    // ─── Barangay Archive / Restore / Force-Delete ──────────────────────────

    /**
     * Archive a barangay record (soft delete) - AJAX
     */
    public function archiveBarangay($id)
    {
        $barangay = Barangay::findOrFail($id);
        $barangay->delete();
        return response()->json(['success' => true, 'message' => "Barangay record for \"{$barangay->name}\" ({$barangay->year}) archived."]);
    }

    /**
     * Get all archived barangay records as JSON
     */
    public function getArchivedBarangays()
    {
        $archived = Barangay::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get([
                'id',
                'municipality',
                'name',
                'year',
                'male_population',
                'female_population',
                'pwd_count',
                'single_parent_count',
                'aics_count',
                'deleted_at'
            ]);
        return response()->json($archived);
    }

    /**
     * Restore an archived barangay record
     */
    public function restoreBarangay($id)
    {
        $barangay = Barangay::onlyTrashed()->findOrFail($id);
        $barangay->restore();
        return response()->json(['success' => true, 'message' => "Barangay record for \"{$barangay->name}\" ({$barangay->year}) restored."]);
    }

    /**
     * Permanently delete an archived barangay record
     */
    public function forceDeleteBarangay($id)
    {
        $barangay = Barangay::onlyTrashed()->findOrFail($id);
        $barangay->forceDelete();
        return response()->json(['success' => true, 'message' => "Barangay record permanently deleted."]);
    }

    /**
     * Bulk archive barangay records by municipality + year (replaces bulk hard-delete)
     */
    public function bulkArchiveBarangays(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'required|in:Magdalena,Liliw,Majayjay',
            'year' => 'required|integer|min:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid municipality or year.'], 422);
        }

        $count = Barangay::where('municipality', $request->municipality)
            ->where('year', $request->year)
            ->delete();   // soft delete

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "Archived {$count} barangay records for {$request->municipality} ({$request->year}).",
        ]);
    }

    /**
     * Permanently delete ALL archived barangay records at once
     */
    public function forceDeleteAllArchivedBarangays()
    {
        $count = Barangay::onlyTrashed()->forceDelete();
        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "Permanently deleted {$count} archived barangay record(s).",
        ]);
    }
}
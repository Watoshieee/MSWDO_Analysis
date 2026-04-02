<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
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

        $years = range(date('Y') - 3, date('Y') + 1);

        // Chart data: population per year per municipality
        $chartData = [];
        foreach ($coreNames as $name) {
            $rows = MunicipalityYearlySummary::where('municipality', $name)
                ->orderBy('year')
                ->get();
            $chartData[$name] = [
                'years'      => $rows->pluck('year')->toArray(),
                'population' => $rows->pluck('total_population')->toArray(),
                'households' => $rows->pluck('total_households')->toArray(),
            ];
        }

        return view('superadmin.data.municipalities', compact('summaries', 'coreNames', 'years', 'chartData'));
    }

    /**
     * Upsert municipality yearly summary (create or update)
     */
    public function saveMunicipalitySummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality'    => 'required|in:Magdalena,Liliw,Majayjay',
            'year'            => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'total_population' => 'required|integer|min:0',
            'total_households' => 'required|integer|min:0',
            'total_4ps'       => 'nullable|integer|min:0',
            'total_pwd'       => 'nullable|integer|min:0',
            'total_senior'    => 'nullable|integer|min:0',
            'total_aics'      => 'nullable|integer|min:0',
            'total_esa'       => 'nullable|integer|min:0',
            'total_slp'       => 'nullable|integer|min:0',
            'total_solo_parent' => 'nullable|integer|min:0',
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
                'total_population' => $request->total_population,
                'total_households' => $request->total_households,
                'total_4ps'        => $request->total_4ps ?? 0,
                'total_pwd'        => $request->total_pwd ?? 0,
                'total_senior'     => $request->total_senior ?? 0,
                'total_aics'       => $request->total_aics ?? 0,
                'total_esa'        => $request->total_esa ?? 0,
                'total_slp'        => $request->total_slp ?? 0,
                'total_solo_parent' => $request->total_solo_parent ?? 0,
                'created_at'       => now(),
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
        $barangays = $query->orderBy('municipality')->orderBy('name')->paginate(20);
        $years = range(date('Y') - 3, date('Y') + 1);

        return view('superadmin.data.barangays', compact('barangays', 'municipalities', 'years'));
    }

    /**
     * Update barangay data
     */
    public function updateBarangay(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'male_population'     => 'required|integer|min:0',
            'female_population'   => 'required|integer|min:0',
            'population_0_19'    => 'required|integer|min:0',
            'population_20_59'   => 'required|integer|min:0',
            'population_60_100'  => 'required|integer|min:0',
            'single_parent_count' => 'required|integer|min:0',
            'pwd_count'           => 'nullable|integer|min:0',
            'aics_count'          => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $barangay = Barangay::findOrFail($id);
        $barangay->update([
            'male_population'     => $request->male_population,
            'female_population'   => $request->female_population,
            'population_0_19'    => $request->population_0_19,
            'population_20_59'   => $request->population_20_59,
            'population_60_100'  => $request->population_60_100,
            'single_parent_count' => $request->single_parent_count,
            'pwd_count'           => $request->pwd_count ?? 0,
            'aics_count'          => $request->aics_count ?? 0,
        ]);

        return redirect()->back()->with('success', 'Barangay data updated successfully!');
    }

    /**
     * Display social welfare programs data management with per-year chart
     */
    public function programs(Request $request)
    {
        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];
        $municipalities = Municipality::whereIn('name', $coreNames)->pluck('name');

        $programTypes = [
            '4Ps'                  => '4Ps',
            'Senior_Citizen_Pension' => 'Senior Citizen Pension',
            'PWD_Assistance'       => 'PWD Assistance',
            'AICS'                 => 'AICS',
            'SLP'                  => 'SLP',
            'ESA'                  => 'ESA',
            'Solo_Parent'          => 'Solo Parent',
        ];

        $years = range(date('Y') - 3, date('Y') + 1);

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
            ->select('municipality', 'year',
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
                'AICS'        => (int) $row->total_aics,
                'PWD'         => (int) $row->total_pwd,
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
                'label'           => $muni,
                'data'            => $totals,
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
            'programs', 'municipalities', 'programTypes', 'years',
            'allYears', 'chartDatasets', 'programChartData',
            'barangayAggregates', 'barangayLookup'
        ));
    }

    /**
     * Sync barangay totals (AICS, PWD, Solo Parent) → social_welfare_programs
     */
    public function syncFromBarangays()
    {
        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

        $aggregates = Barangay::whereIn('municipality', $coreNames)
            ->select('municipality', 'year',
                DB::raw('SUM(aics_count)         AS total_aics'),
                DB::raw('SUM(pwd_count)           AS total_pwd'),
                DB::raw('SUM(single_parent_count) AS total_solo_parent')
            )
            ->groupBy('municipality', 'year')
            ->get();

        $synced = 0;
        foreach ($aggregates as $row) {
            $map = [
                'AICS'        => (int) $row->total_aics,
                'PWD_Assistance' => (int) $row->total_pwd,
                'Solo_Parent' => (int) $row->total_solo_parent,
            ];
            foreach ($map as $programType => $count) {
                SocialWelfareProgram::updateOrCreate(
                    [
                        'municipality' => $row->municipality,
                        'program_type' => $programType,
                        'year'         => $row->year,
                    ],
                    [
                        'beneficiary_count' => $count,
                        'barangay'          => null,
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
            'year'              => $request->year,
        ]);

        return redirect()->back()->with('success', 'Program data updated successfully!');
    }

    /**
     * Create new program data
     */
    public function createProgram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality'      => 'required|in:Magdalena,Liliw,Majayjay',
            'program_type'      => 'required|in:4Ps,Senior_Citizen_Pension,PWD_Assistance,AICS,SLP,ESA,Solo_Parent',
            'beneficiary_count' => 'required|integer|min:0',
            'year'              => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Upsert — update if exists, create if not
        SocialWelfareProgram::updateOrCreate(
            [
                'municipality' => $request->municipality,
                'program_type' => $request->program_type,
                'year'         => $request->year,
            ],
            [
                'beneficiary_count' => $request->beneficiary_count,
                'barangay'          => null,
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
}
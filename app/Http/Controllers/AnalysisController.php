<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
use App\Models\MunicipalityMonthlySummary;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    /**
     * Main public page at /analysis — About / Programs info.
     * Labelled "Programs" in the navbar (1st nav item).
     */
    public function index(Request $request)
    {
        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

        $allYears = SocialWelfareProgram::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year')->pluck('year')->toArray();

        $yearlyByMuni = [];
        foreach ($coreNames as $muni) {
            $yearlyByMuni[$muni] = [];
            foreach ($allYears as $yr) {
                $yearlyByMuni[$muni][$yr] = (int) SocialWelfareProgram::where('municipality', $muni)
                    ->where('year', $yr)->sum('beneficiary_count');
            }
        }

        $programTypes = SocialWelfareProgram::distinct()->pluck('program_type')->toArray();
        $yearlyByProgram = [];
        foreach ($programTypes as $pt) {
            $yearlyByProgram[$pt] = [];
            foreach ($allYears as $yr) {
                $yearlyByProgram[$pt][$yr] = (int) SocialWelfareProgram::whereIn('municipality', $coreNames)
                    ->where('program_type', $pt)->where('year', $yr)->sum('beneficiary_count');
            }
        }

        $summaryYears = MunicipalityYearlySummary::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year')->pluck('year')->toArray();

        $yearlyPopulation = [];
        foreach ($coreNames as $muni) {
            $yearlyPopulation[$muni] = [];
            foreach ($summaryYears as $yr) {
                $row = MunicipalityYearlySummary::where('municipality', $muni)->where('year', $yr)->first();
                $yearlyPopulation[$muni][$yr] = $row ? $row->total_population : 0;
            }
        }

        return view('analysis.programs', compact(
            'allYears', 'yearlyByMuni', 'yearlyByProgram', 'programTypes',
            'summaryYears', 'yearlyPopulation', 'coreNames'
        ));
    }

    /**
     * Municipality detail page at /analysis/municipality/{name}
     */
    public function municipality($name)
    {
        $municipality = Municipality::where('name', $name)->firstOrFail();
        $barangays    = Barangay::where('municipality', $name)->get();
        $programs     = SocialWelfareProgram::where('municipality', $name)->get();

        $barangayData = [];
        foreach ($barangays as $barangay) {
            $barangayData[$barangay->name] = [
                'population'     => $barangay->male_population + $barangay->female_population,
                'male'           => $barangay->male_population,
                'female'         => $barangay->female_population,
                'single_parents' => $barangay->single_parent_count,
                'households'     => $barangay->total_households,
                'approved_apps'  => $barangay->total_approved_applications,
                'age_0_19'       => $barangay->population_0_19,
                'age_20_59'      => $barangay->population_20_59,
                'age_60_100'     => $barangay->population_60_100,
            ];
        }

        return view('analysis.municipality', compact('municipality', 'barangays', 'programs', 'barangayData'));
    }

    /**
     * Demographic page at /analysis/demographic.
     * Labelled "Demographic" in the navbar (2nd nav item).
     */
    public function demographic()
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();

        $demographicData = [];
        foreach ($municipalities as $m) {
            $totalPop = $m->male_population + $m->female_population;
            $demographicData[$m->name] = [
                'total'          => $totalPop,
                'male'           => $m->male_population,
                'female'         => $m->female_population,
                'male_pct'       => $totalPop > 0 ? round(($m->male_population / $totalPop) * 100, 1) : 0,
                'female_pct'     => $totalPop > 0 ? round(($m->female_population / $totalPop) * 100, 1) : 0,
                'age_0_19'       => $m->population_0_19,
                'age_20_59'      => $m->population_20_59,
                'age_60_100'     => $m->population_60_100,
                'age_0_19_pct'   => $totalPop > 0 ? round(($m->population_0_19 / $totalPop) * 100, 1) : 0,
                'age_20_59_pct'  => $totalPop > 0 ? round(($m->population_20_59 / $totalPop) * 100, 1) : 0,
                'age_60_100_pct' => $totalPop > 0 ? round(($m->population_60_100 / $totalPop) * 100, 1) : 0,
            ];
        }

        return view('analysis.demographic', compact('demographicData'));
    }

    /**
     * Comparative Analysis page at /analysis/programs.
     * Labelled "Analysis" in the navbar (3rd nav item).
     */
    public function programs(Request $request)
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();

        $comparisonData = [];
        $programTypes   = [];

        foreach ($municipalities as $municipality) {
            $programs        = SocialWelfareProgram::where('municipality', $municipality->name)->get();
            $totalPopulation = $municipality->male_population + $municipality->female_population;

            $comparisonData[$municipality->name] = [
                'total_population'  => $totalPopulation,
                'male'              => $municipality->male_population,
                'female'            => $municipality->female_population,
                'population_0_19'   => $municipality->population_0_19,
                'population_20_59'  => $municipality->population_20_59,
                'population_60_100' => $municipality->population_60_100,
                'single_parents'    => $municipality->single_parent_count,
                'households'        => $municipality->total_households,
                'pending_apps'      => Application::where('municipality', $municipality->name)->where('status', 'pending')->count(),
                'approved_apps'     => Application::where('municipality', $municipality->name)->where('status', 'approved')->count(),
                'rejected_apps'     => Application::where('municipality', $municipality->name)->where('status', 'rejected')->count(),
                'programs'          => $programs->groupBy('program_type')->map->sum('beneficiary_count'),
                'age_groups'        => [
                    'Youth (0-19)'    => $municipality->population_0_19,
                    'Adult (20-59)'   => $municipality->population_20_59,
                    'Senior (60-100)' => $municipality->population_60_100,
                ],
            ];

            foreach ($programs->pluck('program_type') as $type) {
                if (!in_array($type, $programTypes)) {
                    $programTypes[] = $type;
                }
            }
        }

        $programComparison = [];
        $allProgramTypes   = SocialWelfareProgram::distinct()->pluck('program_type');

        foreach ($allProgramTypes as $type) {
            $magdalena = SocialWelfareProgram::where('municipality', 'Magdalena')->where('program_type', $type)->sum('beneficiary_count');
            $liliw     = SocialWelfareProgram::where('municipality', 'Liliw')->where('program_type', $type)->sum('beneficiary_count');
            $majayjay  = SocialWelfareProgram::where('municipality', 'Majayjay')->where('program_type', $type)->sum('beneficiary_count');
            $total     = $magdalena + $liliw + $majayjay;

            $programComparison[$type] = [
                'program_type'         => $type,
                'magdalena'            => $magdalena,
                'liliw'                => $liliw,
                'majayjay'             => $majayjay,
                'total'                => $total,
                'highest'              => max($magdalena, $liliw, $majayjay),
                'highest_municipality' => $magdalena >= $liliw && $magdalena >= $majayjay
                    ? 'Magdalena'
                    : ($liliw >= $magdalena && $liliw >= $majayjay ? 'Liliw' : 'Majayjay'),
            ];
        }

        $municipalityProgramTotals = [
            'Magdalena' => SocialWelfareProgram::where('municipality', 'Magdalena')->sum('beneficiary_count'),
            'Liliw'     => SocialWelfareProgram::where('municipality', 'Liliw')->sum('beneficiary_count'),
            'Majayjay'  => SocialWelfareProgram::where('municipality', 'Majayjay')->sum('beneficiary_count'),
        ];

        $barangays = Barangay::whereIn('municipality', ['Magdalena', 'Liliw', 'Majayjay'])
            ->get()
            ->groupBy('municipality');

        // ── Yearly beneficiary data ────────────────────────────────────────
        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

        $appYears = Application::whereIn('municipality', $coreNames)
            ->whereNotNull('year')
            ->distinct()->orderBy('year')->pluck('year')
            ->filter()->sort()->values()->toArray();

        $summaryYearsRaw = MunicipalityYearlySummary::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year')->pluck('year')->toArray();

        $allYears = collect(array_merge($appYears, $summaryYearsRaw))
            ->unique()->sort()->values()->toArray();

        if (empty($allYears)) {
            $allYears = [(int) date('Y')];
        }

        $progGroupMap = [
            'PWD_Assistance'         => 'PWD Assistance',
            'AICS'                   => 'AICS',
            'AICS_Medical'           => 'AICS',
            'AICS_Burial'            => 'AICS',
            'AICS_Educational'       => 'AICS',
            'Solo_Parent'            => 'Solo Parent',
            'Senior_Citizen_Pension' => 'Senior Citizen Pension',
            '4Ps'                    => '4Ps',
            'ESA'                    => 'ESA',
            'SLP'                    => 'SLP',
        ];

        $approvedApps = Application::whereIn('municipality', $coreNames)
            ->where('status', 'approved')
            ->whereNotNull('year')
            ->select('municipality', 'year', 'program_type', DB::raw('COUNT(*) as cnt'))
            ->groupBy('municipality', 'year', 'program_type')
            ->get();

        $yearlyByMuni = [];
        foreach ($coreNames as $muni) {
            $yearlyByMuni[$muni] = [];
            foreach ($allYears as $yr) {
                $yearlyByMuni[$muni][$yr] = 0;
            }
        }
        foreach ($approvedApps as $row) {
            if (isset($yearlyByMuni[$row->municipality][$row->year])) {
                $yearlyByMuni[$row->municipality][$row->year] += $row->cnt;
            }
        }
        foreach ($coreNames as $muni) {
            foreach ($summaryYearsRaw as $yr) {
                $summary = MunicipalityYearlySummary::where('municipality', $muni)->where('year', $yr)->first();
                if ($summary) {
                    $fromSummary = $summary->total_pwd + $summary->total_aics + $summary->total_solo_parent;
                    if (!isset($yearlyByMuni[$muni][$yr])) $yearlyByMuni[$muni][$yr] = 0;
                    if ($yearlyByMuni[$muni][$yr] == 0) {
                        $yearlyByMuni[$muni][$yr] = $fromSummary;
                    }
                }
            }
        }

        $allProgramTypesList = collect($progGroupMap)->values()->unique()->values()->toArray();
        $yearlyByProgram = [];
        foreach ($allProgramTypesList as $pt) {
            $yearlyByProgram[$pt] = [];
            foreach ($allYears as $yr) {
                $yearlyByProgram[$pt][$yr] = 0;
            }
        }
        foreach ($approvedApps as $row) {
            $group = $progGroupMap[$row->program_type] ?? $row->program_type;
            if (!isset($yearlyByProgram[$group])) $yearlyByProgram[$group] = [];
            if (!isset($yearlyByProgram[$group][$row->year])) $yearlyByProgram[$group][$row->year] = 0;
            $yearlyByProgram[$group][$row->year] += $row->cnt;
        }
        foreach ($coreNames as $muni) {
            foreach ($summaryYearsRaw as $yr) {
                $summary = MunicipalityYearlySummary::where('municipality', $muni)->where('year', $yr)->first();
                if ($summary) {
                    foreach (['PWD Assistance' => 'total_pwd', 'AICS' => 'total_aics', 'Solo Parent' => 'total_solo_parent'] as $pt => $col) {
                        if (!isset($yearlyByProgram[$pt][$yr])) $yearlyByProgram[$pt][$yr] = 0;
                        if ($yearlyByProgram[$pt][$yr] == 0 && $summary->$col > 0) {
                            $yearlyByProgram[$pt][$yr] += $summary->$col;
                        }
                    }
                }
            }
        }

        $summaryYears = $summaryYearsRaw;
        $yearlyPopulation = [];
        foreach ($coreNames as $muni) {
            $yearlyPopulation[$muni] = [];
            foreach ($summaryYears as $yr) {
                $row = MunicipalityYearlySummary::where('municipality', $muni)->where('year', $yr)->first();
                $yearlyPopulation[$muni][$yr] = $row ? $row->total_population : 0;
            }
        }

        // ── Monthly beneficiary trend ──────────────────────────────────────
        $monthNames      = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyAllYears = MunicipalityMonthlySummary::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $selectedMonthYear = $request->get('month_year', $monthlyAllYears[0] ?? date('Y'));

        $monthlyByMuni = [];
        foreach ($coreNames as $muni) {
            $rows = MunicipalityMonthlySummary::where('municipality', $muni)
                ->where('year', $selectedMonthYear)
                ->orderBy('month')->get()->keyBy('month');
            $monthlyByMuni[$muni] = [
                'pwd'         => array_map(fn($m) => $rows->get($m)?->total_pwd ?? 0, range(1, 12)),
                'aics'        => array_map(fn($m) => $rows->get($m)?->total_aics ?? 0, range(1, 12)),
                'solo_parent' => array_map(fn($m) => $rows->get($m)?->total_solo_parent ?? 0, range(1, 12)),
            ];
        }

        // ── Barangay-level analysis data (all years) ──────────────────────
        $barangayAnalysis = [];
        foreach ($coreNames as $muni) {
            $availableYears = Barangay::where('municipality', $muni)
                ->whereNotNull('year')
                ->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();

            if (empty($availableYears)) {
                $availableYears = [(int) date('Y')];
            }

            $byYear = [];
            foreach ($availableYears as $yr) {
                $rows = Barangay::where('municipality', $muni)
                    ->where('year', $yr)->orderBy('name')->get();

                $byYear[$yr] = [
                    'names'      => $rows->pluck('name')->toArray(),
                    'population' => $rows->map(fn($b) => $b->male_population + $b->female_population)->toArray(),
                    'male'       => $rows->pluck('male_population')->toArray(),
                    'female'     => $rows->pluck('female_population')->toArray(),
                    'pwd'        => $rows->pluck('pwd_count')->toArray(),
                    'aics'       => $rows->pluck('aics_count')->toArray(),
                    'solo_parent'=> $rows->pluck('single_parent_count')->toArray(),
                    'households' => $rows->pluck('total_households')->toArray(),
                    'age_0_19'   => $rows->pluck('population_0_19')->toArray(),
                    'age_20_59'  => $rows->pluck('population_20_59')->toArray(),
                    'age_60_100' => $rows->pluck('population_60_100')->toArray(),
                    'totals'     => [
                        'population'  => $rows->sum(fn($b) => $b->male_population + $b->female_population),
                        'pwd'         => $rows->sum('pwd_count'),
                        'aics'        => $rows->sum('aics_count'),
                        'solo_parent' => $rows->sum('single_parent_count'),
                        'households'  => $rows->sum('total_households'),
                    ],
                ];
            }

            $barangayAnalysis[$muni] = [
                'available_years' => $availableYears,
                'by_year'         => $byYear,
            ];
        }

        return view('analysis.index', compact(
            'comparisonData',
            'programTypes',
            'barangays',
            'programComparison',
            'municipalityProgramTotals',
            'coreNames',
            'allYears',
            'yearlyByMuni',
            'yearlyByProgram',
            'allProgramTypesList',
            'summaryYears',
            'yearlyPopulation',
            'monthlyByMuni',
            'monthlyAllYears',
            'selectedMonthYear',
            'monthNames',
            'barangayAnalysis'
        ));
    }
}

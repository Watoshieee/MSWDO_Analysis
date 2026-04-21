<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
use App\Models\MunicipalityMonthlySummary;
use App\Models\Application;
use App\Models\AdminMunicipalityData;
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
            'allYears',
            'yearlyByMuni',
            'yearlyByProgram',
            'programTypes',
            'summaryYears',
            'yearlyPopulation',
            'coreNames'
        ));
    }

    /**
     * Municipality detail page at /analysis/municipality/{name}
     */
    public function municipality(Request $request, $name)
    {
        $municipality = Municipality::where('name', $name)->firstOrFail();

        // Get unique barangays (not counting per year)
        $barangays = Barangay::where('municipality', $name)
            ->select('name')
            ->distinct()
            ->get();

        // Get available years
        $availableYears = Barangay::where('municipality', $name)
            ->whereNotNull('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Get active year for summary statistics (default to municipality year or current year)
        $currentYear = $request->query('year', $municipality->year ?? date('Y'));
        $barangayCurrentYear = Barangay::where('municipality', $name)->where('year', $currentYear)->get();

        // Get all barangay records for "All" years calculation
        $allBarangayRecords = Barangay::where('municipality', $name)->get();

        $programs = SocialWelfareProgram::where('municipality', $name)->get();
        $applications = Application::where('municipality', $name)->get();

        // Calculate totals from active year data only (for top stats)
        // Priority: Use Barangay data if available, fallback to SocialWelfareProgram data
        $socialProgramsCurrentYear = SocialWelfareProgram::where('municipality', $name)->where('year', $currentYear)->get();

        $totalPopulation = $barangayCurrentYear->sum('total_population');
        $totalHouseholds = $barangayCurrentYear->sum('total_households');

        $adminData = AdminMunicipalityData::where('municipality', $name)->where('year', $currentYear)->first();
        if ($adminData) {
            $totalPopulation = $adminData->total_population;
            $totalHouseholds = $adminData->total_households;
        }

        // Check if barangay data exists for active year
        $hasBarangayDataCurrentYear = $barangayCurrentYear->count() > 0;

        if ($hasBarangayDataCurrentYear) {
            // Use barangay data as priority
            $totalSingleParents = $barangayCurrentYear->sum('single_parent_count');
            $totalPWD = $barangayCurrentYear->sum('pwd_count');
            $totalAICS = $barangayCurrentYear->sum('aics_count');
            $total4PS = $barangayCurrentYear->sum('four_ps_count');
            $totalSenior = $barangayCurrentYear->sum('senior_count');
        } else {
            // Fallback to social welfare program data
            $totalSingleParents = $socialProgramsCurrentYear->where('program_type', 'Solo_Parent')->sum('beneficiary_count');
            $totalPWD = $socialProgramsCurrentYear->where('program_type', 'PWD_Assistance')->sum('beneficiary_count');
            $totalAICS = $socialProgramsCurrentYear->whereIn('program_type', ['AICS', 'AICS_Medical', 'AICS_Burial', 'AICS_Educational'])->sum('beneficiary_count');
            $total4PS = $socialProgramsCurrentYear->where('program_type', '4Ps')->sum('beneficiary_count');
            $totalSenior = $socialProgramsCurrentYear->where('program_type', 'Senior_Citizen_Pension')->sum('beneficiary_count');
        }

        $totalApprovedApps = $applications->where('status', 'approved')->count();

        // Calculate totals from ALL years (for "All" filter option in charts)
        // Priority: Use Barangay data if available, fallback to SocialWelfareProgram data
        $allSocialPrograms = SocialWelfareProgram::where('municipality', $name)->get();

        $hasBarangayDataAll = $allBarangayRecords->count() > 0;

        if ($hasBarangayDataAll) {
            // Use barangay data as priority
            $totalPWD_All = $allBarangayRecords->sum('pwd_count');
            $totalAICS_All = $allBarangayRecords->sum('aics_count');
            $total4PS_All = $allBarangayRecords->sum('four_ps_count');
            $totalSenior_All = $allBarangayRecords->sum('senior_count');
            $totalSingleParents_All = $allBarangayRecords->sum('single_parent_count');
        } else {
            // Fallback to social welfare program data
            $totalPWD_All = $allSocialPrograms->where('program_type', 'PWD_Assistance')->sum('beneficiary_count');
            $totalAICS_All = $allSocialPrograms->whereIn('program_type', ['AICS', 'AICS_Medical', 'AICS_Burial', 'AICS_Educational'])->sum('beneficiary_count');
            $total4PS_All = $allSocialPrograms->where('program_type', '4Ps')->sum('beneficiary_count');
            $totalSenior_All = $allSocialPrograms->where('program_type', 'Senior_Citizen_Pension')->sum('beneficiary_count');
            $totalSingleParents_All = $allSocialPrograms->where('program_type', 'Solo_Parent')->sum('beneficiary_count');
        }

        // For barangay data display, use 2024 data per barangay
        $barangayData = [];
        foreach ($barangays as $barangay) {
            $barangayRecords = Barangay::where('municipality', $name)
                ->where('name', $barangay->name)
                ->where('year', $currentYear)
                ->get();

            $barangayData[$barangay->name] = [
                'population' => $barangayRecords->sum('total_population'),
                'households' => $barangayRecords->sum('total_households'),
                'single_parents' => $barangayRecords->sum('single_parent_count'),
                'pwd' => $barangayRecords->sum('pwd_count'),
                'aics' => $barangayRecords->sum('aics_count'),
                'four_ps' => $barangayRecords->sum('four_ps_count'),
                'senior' => $barangayRecords->sum('senior_count'),
                'approved_apps' => $applications->where('barangay', $barangay->name)->where('status', 'approved')->count(),
            ];
        }

        $allBarangayData = [];
        foreach ($barangays as $barangay) {
            $barangayAllRecords = Barangay::where('municipality', $name)
                ->where('name', $barangay->name)
                ->get();

            $allBarangayData[$barangay->name] = [
                'population' => $barangayAllRecords->sum('total_population'),
                'households' => $barangayAllRecords->sum('total_households'),
                'single_parents' => $barangayAllRecords->sum('single_parent_count'),
                'pwd' => $barangayAllRecords->sum('pwd_count'),
                'aics' => $barangayAllRecords->sum('aics_count'),
                'four_ps' => $barangayAllRecords->sum('four_ps_count'),
                'senior' => $barangayAllRecords->sum('senior_count'),
                'approved_apps' => $applications->where('barangay', $barangay->name)->where('status', 'approved')->count(),
            ];
        }

        // Prepare data by year for filtering (use barangay data only)
        $dataByYear = [];
        foreach ($availableYears as $year) {
            $yearRecords = Barangay::where('municipality', $name)->where('year', $year)->get();

            // Use barangay data only
            $dataByYear[$year] = [
                'totalPWD' => $yearRecords->sum('pwd_count'),
                'totalAICS' => $yearRecords->sum('aics_count'),
                'total4PS' => $yearRecords->sum('four_ps_count'),
                'totalSenior' => $yearRecords->sum('senior_count'),
                'totalSingleParents' => $yearRecords->sum('single_parent_count'),
                'barangayData' => []
            ];

            foreach ($barangays as $barangay) {
                $barangayYearRecords = Barangay::where('municipality', $name)
                    ->where('name', $barangay->name)
                    ->where('year', $year)
                    ->get();

                $dataByYear[$year]['barangayData'][$barangay->name] = [
                    'population' => $barangayYearRecords->sum('total_population'),
                    'households' => $barangayYearRecords->sum('total_households'),
                    'single_parents' => $barangayYearRecords->sum('single_parent_count'),
                    'pwd' => $barangayYearRecords->sum('pwd_count'),
                    'aics' => $barangayYearRecords->sum('aics_count'),
                    'four_ps' => $barangayYearRecords->sum('four_ps_count'),
                    'senior' => $barangayYearRecords->sum('senior_count'),
                    'approved_apps' => $applications->where('barangay', $barangay->name)->where('status', 'approved')->count(),
                ];
            }
        }

        // Get all available program years (combine barangay years + social program years)
        $barangayYears = Barangay::where('municipality', $name)
            ->whereNotNull('year')
            ->distinct()
            ->pluck('year')
            ->toArray();

        $socialProgramYears = SocialWelfareProgram::where('municipality', $name)
            ->whereNotNull('year')
            ->distinct()
            ->pluck('year')
            ->toArray();

        $programYears = collect(array_merge($barangayYears, $socialProgramYears))
            ->unique()
            ->sort()
            ->reverse()
            ->values()
            ->toArray();

        // Default to active year or latest year if active doesn't exist
        $defaultProgramYear = in_array($currentYear, $programYears) ? $currentYear : ($programYears[0] ?? date('Y'));

        // Get programs by year - Use Barangay data directly from dashboard
        $programsByYear = [];

        // Calculate per year
        foreach ($programYears as $year) {
            $yearBarangays = Barangay::where('municipality', $name)->where('year', $year)->get();

            $programsByYear[$year] = [
                'PWD' => $yearBarangays->sum('pwd_count'),
                'AICS' => $yearBarangays->sum('aics_count'),
                'Solo Parent' => $yearBarangays->sum('single_parent_count'),
                '4Ps' => $yearBarangays->sum('four_ps_count'),
                'Senior' => $yearBarangays->sum('senior_count'),
            ];

            $programsByYear[$year] = array_filter($programsByYear[$year], fn($v) => $v > 0);
        }

        // Calculate "All" years combined - Use Barangay data
        $programsByYear['all'] = [
            'PWD' => $allBarangayRecords->sum('pwd_count'),
            'AICS' => $allBarangayRecords->sum('aics_count'),
            'Solo Parent' => $allBarangayRecords->sum('single_parent_count'),
            '4Ps' => $allBarangayRecords->sum('four_ps_count'),
            'Senior' => $allBarangayRecords->sum('senior_count'),
        ];

        $programsByYear['all'] = array_filter($programsByYear['all'], fn($v) => $v > 0);

        return view('analysis.municipality', compact(
            'municipality',
            'barangays',
            'programs',
            'barangayData',
            'totalPopulation',
            'totalHouseholds',
            'totalSingleParents',
            'totalPWD',
            'totalAICS',
            'total4PS',
            'totalSenior',
            'totalApprovedApps',
            'totalPWD_All',
            'totalAICS_All',
            'total4PS_All',
            'totalSenior_All',
            'totalSingleParents_All',
            'availableYears',
            'dataByYear',
            'programYears',
            'programsByYear',
            'defaultProgramYear',
            'allBarangayData'
        ));
    }

    /**
     * Demographic page at /analysis/demographic.
     * Labelled "Demographic" in the navbar (2nd nav item).
     */
    public function demographic(Request $request)
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();

        $demographicData = [];

        foreach ($municipalities as $m) {
            $currentYear = $request->query('year', $m->year ?? date('Y'));
            
            // Get active year barangay data for this municipality (same as municipality method)
            $barangayCurrentYear = Barangay::where('municipality', $m->name)
                ->where('year', $currentYear)
                ->get();

            $totalPop = $barangayCurrentYear->sum('total_population');
            $totalHouseholds = $barangayCurrentYear->sum('total_households');

            $adminData = AdminMunicipalityData::where('municipality', $m->name)->where('year', $currentYear)->first();
            if ($adminData) {
                $totalPop = $adminData->total_population;
                $totalHouseholds = $adminData->total_households;
            }

            // Calculate total beneficiaries from active year barangay data only
            $totalBeneficiaries = $barangayCurrentYear->sum('pwd_count')
                + $barangayCurrentYear->sum('aics_count')
                + $barangayCurrentYear->sum('single_parent_count')
                + $barangayCurrentYear->sum('four_ps_count')
                + $barangayCurrentYear->sum('senior_count');

            $demographicData[$m->name] = [
                'total' => $totalPop,
                'households' => $totalHouseholds,
                'beneficiaries' => $totalBeneficiaries,
                'households_pct' => $totalPop > 0 ? round(($totalHouseholds / $totalPop) * 100, 1) : 0,
                'beneficiaries_pct' => $totalPop > 0 ? round(($totalBeneficiaries / $totalPop) * 100, 1) : 0,
                'age_0_19' => $m->population_0_19,
                'age_20_59' => $m->population_20_59,
                'age_60_100' => $m->population_60_100,
                'age_0_19_pct' => $totalPop > 0 ? round(($m->population_0_19 / $totalPop) * 100, 1) : 0,
                'age_20_59_pct' => $totalPop > 0 ? round(($m->population_20_59 / $totalPop) * 100, 1) : 0,
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

        $coreNames = ['Magdalena', 'Liliw', 'Majayjay'];

        // Determine all available years across systems to set up global filter
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

        $defaultYear = in_array(2024, $allYears) ? 2024 : ($allYears[count($allYears)-1] ?? date('Y'));
        $currentYear = $request->query('year', $defaultYear);

        $comparisonData = [];
        $programTypes = [];

        foreach ($municipalities as $municipality) {
            $programs = SocialWelfareProgram::where('municipality', $municipality->name)->where('year', $currentYear)->get();

            // Get active year barangay data for population and households ONLY
            $barangayCurrentYear = Barangay::where('municipality', $municipality->name)->where('year', $currentYear)->get();

            // Use barangay active year data for population and households
            $totalPopulation = $barangayCurrentYear->sum('total_population');
            $totalHouseholds = $barangayCurrentYear->sum('total_households');

            $adminData = AdminMunicipalityData::where('municipality', $municipality->name)->where('year', $currentYear)->first();
            if ($adminData) {
                $totalPopulation = $adminData->total_population;
                $totalHouseholds = $adminData->total_households;
            }

            // Calculate active year beneficiaries ONLY from explicit barangay programs (PWD, AICS, Solo Parent, 4Ps, Senior)
            $socialProgramsCurrentYear = SocialWelfareProgram::where('municipality', $municipality->name)->where('year', $currentYear)->get();
            $beneficiariesCurrentYear = $barangayCurrentYear->sum('pwd_count') +
                                        $barangayCurrentYear->sum('aics_count') +
                                        $barangayCurrentYear->sum('four_ps_count') +
                                        $barangayCurrentYear->sum('senior_count') +
                                        $barangayCurrentYear->sum('single_parent_count');

            $comparisonData[$municipality->name] = [
                'total_population' => $totalPopulation,
                'male' => $municipality->male_population,
                'female' => $municipality->female_population,
                'population_0_19' => $municipality->population_0_19,
                'population_20_59' => $municipality->population_20_59,
                'population_60_100' => $municipality->population_60_100,
                'single_parents' => $municipality->single_parent_count,
                'beneficiaries_current' => $beneficiariesCurrentYear,
                'households' => $totalHouseholds,
                'pending_apps' => Application::where('municipality', $municipality->name)->where('status', 'pending')->count(),
                'approved_apps' => Application::where('municipality', $municipality->name)->where('status', 'approved')->count(),
                'rejected_apps' => Application::where('municipality', $municipality->name)->where('status', 'rejected')->count(),
                'programs' => $socialProgramsCurrentYear->groupBy('program_type')->map->sum('beneficiary_count'),
                'age_groups' => [
                    'Youth (0-19)' => $municipality->population_0_19,
                    'Adult (20-59)' => $municipality->population_20_59,
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
        $allProgramTypes = SocialWelfareProgram::where('year', $currentYear)->distinct()->pluck('program_type');

        foreach ($allProgramTypes as $type) {
            $magdalena = SocialWelfareProgram::where('municipality', 'Magdalena')->where('program_type', $type)->where('year', $currentYear)->sum('beneficiary_count');
            $liliw = SocialWelfareProgram::where('municipality', 'Liliw')->where('program_type', $type)->where('year', $currentYear)->sum('beneficiary_count');
            $majayjay = SocialWelfareProgram::where('municipality', 'Majayjay')->where('program_type', $type)->where('year', $currentYear)->sum('beneficiary_count');
            $total = $magdalena + $liliw + $majayjay;

            $programComparison[$type] = [
                'program_type' => $type,
                'magdalena' => $magdalena,
                'liliw' => $liliw,
                'majayjay' => $majayjay,
                'total' => $total,
                'highest' => max($magdalena, $liliw, $majayjay),
                'highest_municipality' => $magdalena >= $liliw && $magdalena >= $majayjay
                    ? 'Magdalena'
                    : ($liliw >= $magdalena && $liliw >= $majayjay ? 'Liliw' : 'Majayjay'),
            ];
        }

        $municipalityProgramTotals = [];
        foreach (['Magdalena', 'Liliw', 'Majayjay'] as $mun) {
            $munBgy = Barangay::where('municipality', $mun)->where('year', $currentYear)->get();
            $municipalityProgramTotals[$mun] = $munBgy->sum('pwd_count') + 
                                               $munBgy->sum('aics_count') + 
                                               $munBgy->sum('four_ps_count') + 
                                               $munBgy->sum('senior_count') + 
                                               $munBgy->sum('single_parent_count');
        }

        $barangays = Barangay::whereIn('municipality', ['Magdalena', 'Liliw', 'Majayjay'])
            ->get()
            ->groupBy('municipality');

        // ── Yearly beneficiary data ────────────────────────────────────────

        $progGroupMap = [
            'PWD_Assistance' => 'PWD Assistance',
            'AICS' => 'AICS',
            'AICS_Medical' => 'AICS',
            'AICS_Burial' => 'AICS',
            'AICS_Educational' => 'AICS',
            'Solo_Parent' => 'Solo Parent',
            'Senior_Citizen_Pension' => 'Senior Citizen Pension',
            '4Ps' => '4Ps',
            'ESA' => 'ESA',
            'SLP' => 'SLP',
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
                    if (!isset($yearlyByMuni[$muni][$yr]))
                        $yearlyByMuni[$muni][$yr] = 0;
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
            if (!isset($yearlyByProgram[$group]))
                $yearlyByProgram[$group] = [];
            if (!isset($yearlyByProgram[$group][$row->year]))
                $yearlyByProgram[$group][$row->year] = 0;
            $yearlyByProgram[$group][$row->year] += $row->cnt;
        }
        foreach ($coreNames as $muni) {
            foreach ($summaryYearsRaw as $yr) {
                $summary = MunicipalityYearlySummary::where('municipality', $muni)->where('year', $yr)->first();
                if ($summary) {
                    foreach (['PWD Assistance' => 'total_pwd', 'AICS' => 'total_aics', 'Solo Parent' => 'total_solo_parent'] as $pt => $col) {
                        if (!isset($yearlyByProgram[$pt][$yr]))
                            $yearlyByProgram[$pt][$yr] = 0;
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
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyAllYears = MunicipalityMonthlySummary::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $selectedMonthYear = $request->get('month_year', $monthlyAllYears[0] ?? date('Y'));

        $monthlyByMuni = [];
        foreach ($coreNames as $muni) {
            $rows = MunicipalityMonthlySummary::where('municipality', $muni)
                ->where('year', $selectedMonthYear)
                ->orderBy('month')->get()->keyBy('month');
            $monthlyByMuni[$muni] = [
                'pwd' => array_map(fn($m) => $rows->get($m)?->total_pwd ?? 0, range(1, 12)),
                'aics' => array_map(fn($m) => $rows->get($m)?->total_aics ?? 0, range(1, 12)),
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
                    'names' => $rows->pluck('name')->toArray(),
                    'population' => $rows->map(fn($b) => $b->male_population + $b->female_population)->toArray(),
                    'male' => $rows->pluck('male_population')->toArray(),
                    'female' => $rows->pluck('female_population')->toArray(),
                    'pwd' => $rows->pluck('pwd_count')->toArray(),
                    'aics' => $rows->pluck('aics_count')->toArray(),
                    'solo_parent' => $rows->pluck('single_parent_count')->toArray(),
                    'households' => $rows->pluck('total_households')->toArray(),
                    'age_0_19' => $rows->pluck('population_0_19')->toArray(),
                    'age_20_59' => $rows->pluck('population_20_59')->toArray(),
                    'age_60_100' => $rows->pluck('population_60_100')->toArray(),
                    'totals' => [
                        'population' => $rows->sum(fn($b) => $b->male_population + $b->female_population),
                        'pwd' => $rows->sum('pwd_count'),
                        'aics' => $rows->sum('aics_count'),
                        'solo_parent' => $rows->sum('single_parent_count'),
                        'households' => $rows->sum('total_households'),
                    ],
                ];
            }

            $barangayAnalysis[$muni] = [
                'available_years' => $availableYears,
                'by_year' => $byYear,
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

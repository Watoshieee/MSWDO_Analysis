<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
use App\Models\MunicipalityMonthlySummary;
use App\Models\Application;
use App\Models\AdminMunicipalityData;
use App\Models\MunicipalityVision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    /**
     * Main public page at /analysis - About / Programs info.
     * Labelled "Programs" in the navbar (1st nav item).
     */
    public function index(Request $request)
    {
        // Only show data for these three municipalities
        $coreNames = Municipality::orderBy('name')->pluck('name')->toArray();

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
                $yearlyPopulation[$muni][$yr] = $row ? $row->total_population : null;
            }
        }

        // Municipality color palette
        $palette = ['#2C3E8F', '#FDB913', '#C41E24', '#16a34a', '#7c3aed', '#0891b2'];
        $colors = [];
        foreach (array_values($coreNames) as $i => $n) {
            $colors[$n] = $palette[$i % count($palette)];
        }

        // Vision / Mission / Goals per municipality
        $visionRows = MunicipalityVision::whereIn('municipality_name', $coreNames)->get()->keyBy('municipality_name');
        $visionData = [];
        foreach ($coreNames as $n) {
            $row = $visionRows[$n] ?? null;
            $visionData[$n] = [
                'vision' => $row?->vision ?? '',
                'mission' => $row?->mission ?? '',
                'goals' => $row?->goals ?? '',
                'strategic_goals' => $row?->strategic_goals ?? [],
            ];
        }

        return view('analysis.programs', compact(
            'allYears',
            'yearlyByMuni',
            'yearlyByProgram',
            'programTypes',
            'summaryYears',
            'yearlyPopulation',
            'coreNames',
            'colors',
            'visionData'
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
        // Only show data for these three municipalities
        $coreNames = Municipality::orderBy('name')->pluck('name')->toArray();
        $palette = ['#2C3E8F', '#FDB913', '#6366f1', '#16a34a', '#9333ea', '#0891b2', '#ea580c', '#db2777', '#65a30d', '#d97706'];
        $colors = [];
        foreach ($coreNames as $i => $n) {
            $colors[$n] = $palette[$i % count($palette)];
        }

        // Ã¢â€â‚¬Ã¢â€â‚¬ All unique years across summaries Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
        $allYears = MunicipalityYearlySummary::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year')->pluck('year')->toArray();
        if (empty($allYears))
            $allYears = [(int) date('Y')];

        $latestYear = end($allYears);
        $selectedYear = (int) $request->input('year', $latestYear);

        // Ã¢â€â‚¬Ã¢â€â‚¬ Per-year, per-municipality data Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
        $summariesByMuni = [];
        foreach ($coreNames as $name) {
            $rows = MunicipalityYearlySummary::where('municipality', $name)
                ->orderBy('year')->get();
            $summariesByMuni[$name] = $rows->keyBy('year');
        }

        // Ã¢â€â‚¬Ã¢â€â‚¬ Trend arrays (indexed by $allYears) Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
        $populationTrend = [];  // [muni => [yr => pop]]
        $householdsTrend = [];  // [muni => [yr => hh]]
        $benefTrend = [];  // [muni => [yr => total_benef]]
        foreach ($coreNames as $name) {
            $populationTrend[$name] = [];
            $householdsTrend[$name] = [];
            $benefTrend[$name] = [];
            foreach ($allYears as $yr) {
                $row = $summariesByMuni[$name][$yr] ?? null;
                $populationTrend[$name][$yr] = $row ? (int) $row->total_population : 0;
                $householdsTrend[$name][$yr] = $row ? (int) $row->total_households : 0;
                $benefTrend[$name][$yr] = $row
                    ? ((int) $row->total_pwd + (int) $row->total_aics + (int) $row->total_solo_parent
                        + (int) $row->total_4ps + (int) $row->total_senior)
                    : 0;
            }
        }

        // Ã¢â€â‚¬Ã¢â€â‚¬ Selected-year demographic data Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
        $demographicData = [];
        foreach ($coreNames as $name) {
            $row = $summariesByMuni[$name][$selectedYear] ?? null;
            $pop = $row ? (int) $row->total_population : 0;
            $male = $row ? (int) $row->male_population : 0;
            $female = $row ? (int) $row->female_population : 0;
            $hh = $row ? (int) $row->total_households : 0;
            $pwd = $row ? (int) $row->total_pwd : 0;
            $aics = $row ? (int) $row->total_aics : 0;
            $solo = $row ? (int) $row->total_solo_parent : 0;
            $fps = $row ? (int) $row->total_4ps : 0;
            $sen = $row ? (int) $row->total_senior : 0;
            $age0 = $row ? (int) $row->population_0_19 : 0;
            $age20 = $row ? (int) $row->population_20_59 : 0;
            $age60 = $row ? (int) $row->population_60_100 : 0;
            $totalBenef = $pwd + $aics + $solo + $fps + $sen;

            $demographicData[$name] = [
                'total' => $pop,
                'male' => $male,
                'female' => $female,
                'households' => $hh,
                'avg_hh_size' => ($hh > 0 && $pop > 0) ? round($pop / $hh, 1) : 0,
                'beneficiaries' => $totalBenef,
                'pwd' => $pwd,
                'aics' => $aics,
                'solo_parent' => $solo,
                'four_ps' => $fps,
                'senior' => $sen,
                'age_0_19' => $age0,
                'age_20_59' => $age20,
                'age_60_100' => $age60,
                'age_0_19_pct' => $pop > 0 ? round($age0 / $pop * 100, 1) : 0,
                'age_20_59_pct' => $pop > 0 ? round($age20 / $pop * 100, 1) : 0,
                'age_60_100_pct' => $pop > 0 ? round($age60 / $pop * 100, 1) : 0,
                'beneficiaries_pct' => $pop > 0 ? round($totalBenef / $pop * 100, 1) : 0,
                'households_pct' => $pop > 0 ? round($hh / $pop * 100, 1) : 0,
            ];
        }

        // Ã¢â€â‚¬Ã¢â€â‚¬ Auto-generated key insights Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
        // Use array_map (not array_column) to preserve municipality name keys
        $pops = array_map(fn($d) => $d['total'], $demographicData);
        $bens = array_map(fn($d) => $d['beneficiaries'], $demographicData);

        // Highest population
        arsort($pops);
        $highPop = key($pops);
        // Fastest growing (biggest absolute increase last 2 years)
        $growthMap = [];
        foreach ($coreNames as $n) {
            $yrs = array_keys($populationTrend[$n]);
            if (count($yrs) >= 2) {
                $last = $populationTrend[$n][end($yrs)];
                $prev = $populationTrend[$n][$yrs[count($yrs) - 2]];
                $growthMap[$n] = $last - $prev;
            } else {
                $growthMap[$n] = 0;
            }
        }
        arsort($growthMap);
        $fastestGrowing = key($growthMap);
        // Highest beneficiaries
        arsort($bens);
        $highBen = key($bens);
        // Dominant age group (across all 3)
        $totAge0 = array_sum(array_map(fn($d) => $d['age_0_19'], $demographicData));
        $totAge20 = array_sum(array_map(fn($d) => $d['age_20_59'], $demographicData));
        $totAge60 = array_sum(array_map(fn($d) => $d['age_60_100'], $demographicData));
        $domAgeGroup = $totAge0 >= $totAge20 && $totAge0 >= $totAge60
            ? 'Youth (0-19)'
            : ($totAge20 >= $totAge0 && $totAge20 >= $totAge60 ? 'Working Age (20-59)' : 'Senior (60+)');
        // Gender imbalance
        $totalMale = array_sum(array_map(fn($d) => $d['male'], $demographicData));
        $totalFemale = array_sum(array_map(fn($d) => $d['female'], $demographicData));
        $genderNote = ($totalMale + $totalFemale) > 0
            ? ($totalMale > $totalFemale ? 'Male-dominant' : ($totalFemale > $totalMale ? 'Female-dominant' : 'Balanced'))
            : 'No gender data';

        $insights = [
            "$highPop has the largest population at " . number_format($demographicData[$highPop]['total']) . " in {$selectedYear}, making it the most populous municipality in this dataset.",
            "$fastestGrowing shows the highest population growth between available years indicating strong community expansion and increasing demand for social services.",
            "$highBen leads in registered beneficiaries with " . number_format($demographicData[$highBen]['beneficiaries']) . " representing " . $demographicData[$highBen]['beneficiaries_pct'] . "% of its total population.",
            "The $domAgeGroup age bracket is the dominant age segment across all municipalities, which should guide targeted program prioritization.",
            "Overall gender distribution is $genderNote. Male: " . number_format($totalMale) . ", Female: " . number_format($totalFemale) . " a gap of " . number_format(abs($totalMale - $totalFemale)) . " persons.",
            "Average household sizes: " . implode(', ', array_map(fn($n) => "$n: {$demographicData[$n]['avg_hh_size']}", $coreNames)) . " persons per household.",
        ];

        return view('analysis.demographic', compact(
            'demographicData',
            'allYears',
            'selectedYear',
            'populationTrend',
            'householdsTrend',
            'benefTrend',
            'colors',
            'coreNames',
            'insights'
        ));
    }

    /**
     * Comprehensive Statistical Analysis page at /analysis/programs.
     * Demographic data  â†’ municipality_yearly_summary  (same source as /superadmin/data/municipalities)
     * Program data      â†’ social_welfare_programs       (same source as /superadmin/data/programs)
     */
    public function programs(Request $request)
    {
        // Only show data for these three municipalities
        $coreNames = Municipality::orderBy('name')->pluck('name')->toArray();
        $palette = ['#2C3E8F', '#FDB913', '#6366f1', '#16a34a', '#9333ea', '#0891b2', '#ea580c', '#db2777', '#65a30d', '#d97706'];
        $colors = [];
        foreach ($coreNames as $i => $n) {
            $colors[$n] = $palette[$i % count($palette)];
        }

        // â”€â”€ Demographic data: municipality_yearly_summary â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $allSummaries = MunicipalityYearlySummary::whereIn('municipality', $coreNames)
            ->orderBy('year')->get();

        $this->summariesByMuni = [];
        foreach ($coreNames as $name) {
            $this->summariesByMuni[$name] = $allSummaries->where('municipality', $name)->keyBy('year');
        }

        // â”€â”€ Program data: social_welfare_programs â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $allPrograms = SocialWelfareProgram::whereIn('municipality', $coreNames)->get();
        $this->programLookup = [];
        foreach ($allPrograms as $p) {
            $this->programLookup[$p->getRawOriginal('municipality')][$p->year][$p->program_type]
                = $p->beneficiary_count === null ? null : (int) $p->beneficiary_count;
        }

        // Program types are discovered from the data itself (no fixed list)
        $this->programTypes = $allPrograms->pluck('program_type')->filter()->unique()->sort()->values()->toArray();
        $programLabels = [];
        foreach ($this->programTypes as $type) {
            $programLabels[$type] = $this->programLabel($type);
        }
        $programTypes = $this->programTypes;

        // â”€â”€ All unique years: union of both sources â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $summaryYears = $allSummaries->pluck('year')->unique()->sort()->values()->toArray();
        $programYears = $allPrograms->pluck('year')->unique()->sort()->values()->toArray();
        $allYears = collect(array_merge($summaryYears, $programYears))
            ->unique()->sort()->values()->toArray();
        if (empty($allYears))
            $allYears = [(int) date('Y')];

        // --- Category filter ---
        $selectedCategory = $request->input('category', 'demography');
        if (!in_array($selectedCategory, ['demography', 'programs', 'all'])) {
            $selectedCategory = 'demography';
        }
        if ($selectedCategory === 'programs') {
            $categoryYears = $programYears;
        } elseif ($selectedCategory === 'all') {
            $categoryYears = $allYears;
        } else {
            $categoryYears = $summaryYears;
        }
        if (empty($categoryYears)) {
            $categoryYears = $allYears; // fallback if one source has no records yet
        }

        $latestCategoryYear = !empty($categoryYears) ? end($categoryYears) : (int) date('Y');
        $selectedYear = (int) $request->input('year', $latestCategoryYear);
        // Clamp: if the requested year is not valid for the active category, use latest
        if (!in_array($selectedYear, $categoryYears)) {
            $selectedYear = $latestCategoryYear;
        }

        $snapshot = $this->buildSnapshot($coreNames, $selectedYear);

        [
            $populationTrend,
            $maleTrend,
            $femaleTrend,
            $householdsTrend,
            $benefTrend,
            $programTrend,
            $growthRates
        ]
            = $this->buildTrends($coreNames, $allYears);

        // â”€â”€ ANOVA: removed (statistically invalid with n=3 time-series) â”€â”€
        $anovaPopResult = null;
        $anovaBenefResult = null;

        // â”€â”€ Correlation: removed (ecological fallacy, nâ‰ˆ9 too small) â”€â”€â”€â”€â”€
        $corrPopBenef = null;
        $corrAge60Senior = null;
        $corrHhAics = null;
        $correlations = [];

        // â”€â”€ Determine which municipalities have program data for the selected year â”€â”€
        $programDataAvailable = [];
        foreach ($coreNames as $name) {
            $programDataAvailable[$name] = isset($this->programLookup[$name][$selectedYear]);
        }

        // â”€â”€ Key Insights (population/household/program only) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $popMap = array_map(fn($n) => $snapshot[$n]['population'], $coreNames);
        $benefMap = array_map(fn($n) => $snapshot[$n]['beneficiaries'], $coreNames);
        // Only municipalities with a KNOWN beneficiary total are considered (null = no data, not 0).
        // An actual 0 is a known value and stays in the list.
        $benefKnown = array_filter($benefMap, fn($v) => $v !== null);
        if (count($benefKnown) > 0) {
            arsort($benefKnown);
            $highestBenef = $coreNames[key($benefKnown)];
        } else {
            $highestBenef = null;
        }

        $popKnown = array_filter($popMap, fn($v) => $v !== null);
        $hasDemogData = count($popKnown) > 0;
        if ($hasDemogData) {
            arsort($popKnown);
            $highestPop = $coreNames[key($popKnown)];
            asort($popKnown);
            $lowestPop = $coreNames[key($popKnown)];
        } else {
            $highestPop = null;
            $lowestPop = null;
        }

        $avgGrowth = [];
        foreach ($coreNames as $i => $name) {
            $rates = array_filter($growthRates[$name], fn($v) => $v !== null);
            if (count($rates) > 0) {
                $avgGrowth[$i] = array_sum($rates) / count($rates);
            }
        }
        if (count($avgGrowth) > 0) {
            arsort($avgGrowth);
            $fastestIdx = key($avgGrowth);
            $fastest = $coreNames[$fastestIdx];
            $fastestGrowth = round($avgGrowth[$fastestIdx], 2);
        } else {
            $fastest = null;
            $fastestGrowth = null;
        }

        // Program totals for the selected year (only from municipalities that have data)
        $progTotals = [];
        foreach ($this->programTypes as $type) {
            $typeTotal = null;
            foreach ($coreNames as $n) {
                $v = $snapshot[$n]['programs'][$type] ?? null;
                if ($programDataAvailable[$n] && $v !== null) {
                    $typeTotal = ($typeTotal ?? 0) + $v;
                }
            }
            if ($typeTotal !== null) {
                $progTotals[$programLabels[$type]] = $typeTotal;
            }
        }
        // Actual 0 totals are kept as 0. A program only gets an entry when at least one
        // municipality has a known value for it, so missing data never becomes 0 here.
        arsort($progTotals);
        // Highest-demand program: only when at least one known total is greater than 0.
        // If all known totals are 0, or no totals are known at all => null.
        // ($progTotals itself is untouched, so actual 0 totals remain 0.)
        $topProgram = (count($progTotals) > 0 && max($progTotals) > 0) ? array_key_first($progTotals) : null;

        // Age/gender variables - set to null/safe defaults (removed from analysis)
        $domAge = null;
        $totalMale = null;
        $totalFemale = null;
        $genderGap = null;

        // -- Dynamic year descriptors (derived from DB, never hardcoded) --
        $censusYearList = !empty($summaryYears) ? implode(', ', $summaryYears) : 'N/A';
        $censusYearRange = !empty($summaryYears) ? (count($summaryYears) > 1 ? min($summaryYears) . ' - ' . max($summaryYears) : (string) reset($summaryYears)) : 'available census years';
        $progYearRange = !empty($programYears) ? (count($programYears) > 1 ? min($programYears) . ' - ' . max($programYears) : (string) reset($programYears)) : 'N/A';

        // Check if any municipality has program data for the selected year
        $anyProgramData = in_array(true, $programDataAvailable, true);

        $insights = [];
        if ($hasDemogData) {
            $insights[] = "$highestPop has the highest population (" . number_format($snapshot[$highestPop]['population']) . ") while $lowestPop has the lowest.";
            if ($fastest !== null) {
                $insights[] = "$fastest shows the highest average population growth rate among the municipalities.";
            }
        } else {
            $insights[] = "No official census population or household data is available for year $selectedYear. Census records are available for: $censusYearList.";
            if ($fastest !== null) {
                $insights[] = "$fastest shows the highest average population growth rate across historical census years ($censusYearRange).";
            }
        }

        // Only add beneficiary insight if program data exists for the selected year
        $highestBenefVal = $highestBenef !== null ? $snapshot[$highestBenef]['beneficiaries'] : null;
        if ($anyProgramData && $highestBenefVal !== null && $highestBenefVal > 0) {
            $pctNote = $snapshot[$highestBenef]['benef_pct'] > 0 ? " - " . $snapshot[$highestBenef]['benef_pct'] . "% of its population" : "";
            $insights[] = "$highestBenef has the most registered beneficiaries (" . number_format($highestBenefVal) . ")$pctNote.";
        } elseif ($anyProgramData && $highestBenefVal === 0) {
            $insights[] = "Program records for year $selectedYear report 0 beneficiaries for the municipalities with data.";
        } else {
            $insights[] = "No program beneficiary data is available for year $selectedYear. Program beneficiary records cover: $progYearRange.";
        }

        if ($topProgram !== null) {
            $insights[] = "The $topProgram program has the highest total beneficiaries (" . number_format($progTotals[$topProgram]) . ") across municipalities with data for $selectedYear.";
        }

        if ($hasDemogData) {
            $insights[] = "Average household sizes: " . implode(', ', array_map(fn($n) => "$n: " . ($snapshot[$n]['avg_hh_size'] ?? 'N/A'), $coreNames)) . " persons per household.";
        }

        // â”€â”€ Vision / Mission / Goals per municipality â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $visionRows = MunicipalityVision::whereIn('municipality_name', $coreNames)->get()->keyBy('municipality_name');
        $visionData = [];
        foreach ($coreNames as $n) {
            $row = $visionRows[$n] ?? null;
            $visionData[$n] = [
                'vision' => $row?->vision ?? '',
                'mission' => $row?->mission ?? '',
                'goals' => $row?->goals ?? '',
                'strategic_goals' => $row?->strategic_goals ?? [],
            ];
        }

        return view('analysis.index', compact(
            'coreNames',
            'colors',
            'allYears',
            'selectedYear',
            'summaryYears',
            'programYears',
            'selectedCategory',
            'categoryYears',
            'snapshot',
            'populationTrend',
            'maleTrend',
            'femaleTrend',
            'householdsTrend',
            'benefTrend',
            'growthRates',
            'programTrend',
            'programTypes',
            'programLabels',
            'anovaPopResult',
            'anovaBenefResult',
            'correlations',
            'corrPopBenef',
            'corrAge60Senior',
            'corrHhAics',
            'insights',
            'highestPop',
            'lowestPop',
            'highestBenef',
            'fastest',
            'fastestGrowth',
            'domAge',
            'topProgram',
            'progTotals',
            'visionData'
        ));
    }


    // â”€â”€ Statistical Helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    /** Keyed summary rows: [name][year] => MunicipalityYearlySummary */
    private array $summariesByMuni = [];

    /** Program lookup: [municipality][year][program_type] => count (null = record exists but count is null) */
    private array $programLookup = [];

    /** Distinct program_type values discovered from the data (set in programs()) */
    private array $programTypes = [];

    /**
     * Existing record value (including 0) => int; no record / null value => null (N/A).
     */
    private function getDemog(string $name, int $yr, string $field): ?int
    {
        $row = $this->summariesByMuni[$name][$yr] ?? null;
        if ($row === null) {
            return null;
        }
        $value = $row->$field ?? null;
        return $value === null ? null : (int) $value;
    }

    /**
     * Existing record value (including 0) => int; no record => null (N/A).
     */
    private function getProg(string $name, int $yr, string $type): ?int
    {
        $value = $this->programLookup[$name][$yr][$type] ?? null;
        return $value === null ? null : (int) $value;
    }

    /** Sum of the non-null values; null when no value is known (so "no data" never becomes 0). */
    private function sumKnown(array $values): ?int
    {
        $known = array_filter($values, fn($v) => $v !== null);
        return count($known) > 0 ? (int) array_sum($known) : null;
    }

    /** Display label derived from the raw program_type value (no fixed program list). */
    private function programLabel(string $type): string
    {
        return trim(str_replace('_', ' ', $type));
    }

    private function buildSnapshot(array $coreNames, int $selectedYear): array
    {
        $snapshot = [];
        foreach ($coreNames as $name) {
            $pop = $this->getDemog($name, $selectedYear, 'total_population');
            $hh = $this->getDemog($name, $selectedYear, 'total_households');
            $male = $this->getDemog($name, $selectedYear, 'male_population');
            $female = $this->getDemog($name, $selectedYear, 'female_population');
            $a0 = $this->getDemog($name, $selectedYear, 'population_0_19');
            $a20 = $this->getDemog($name, $selectedYear, 'population_20_59');
            $a60 = $this->getDemog($name, $selectedYear, 'population_60_100');

            // One entry per program_type found in the data
            $programs = [];
            foreach ($this->programTypes as $type) {
                $programs[$type] = $this->getProg($name, $selectedYear, $type);
            }
            $benef = $this->sumKnown($programs);

            $snapshot[$name] = [
                'population' => $pop,
                'households' => $hh,
                'beneficiaries' => $benef,
                'programs' => $programs,
                'male' => $male,
                'female' => $female,
                'age_0_19' => $a0,
                'age_20_59' => $a20,
                'age_60_100' => $a60,
                'avg_hh_size' => ($hh !== null && $pop !== null && $hh > 0 && $pop > 0) ? round($pop / $hh, 2) : null,
                'dependency_ratio' => ($a0 !== null && $a20 !== null && $a60 !== null && $a20 > 0) ? round(($a0 + $a60) / $a20 * 100, 1) : null,
                'benef_pct' => ($benef !== null && $pop !== null && $pop > 0) ? round($benef / $pop * 100, 1) : null,
            ];
        }
        return $snapshot;
    }

    private function buildTrends(array $coreNames, array $allYears): array
    {
        $populationTrend = [];
        $maleTrend = [];
        $femaleTrend = [];
        $householdsTrend = [];
        $benefTrend = [];
        $programTrend = [];   // [program_type][municipality][year] => count|null
        $growthRates = [];

        foreach ($this->programTypes as $type) {
            $programTrend[$type] = [];
        }

        foreach ($coreNames as $name) {
            $prevPop = null;
            foreach ($allYears as $yr) {
                $pop = $this->getDemog($name, $yr, 'total_population');
                $hh = $this->getDemog($name, $yr, 'total_households');
                $male = $this->getDemog($name, $yr, 'male_population');
                $female = $this->getDemog($name, $yr, 'female_population');

                $progVals = [];
                foreach ($this->programTypes as $type) {
                    $progVals[$type] = $this->getProg($name, $yr, $type);
                    $programTrend[$type][$name][$yr] = $progVals[$type];
                }

                $populationTrend[$name][$yr] = $pop;
                $householdsTrend[$name][$yr] = $hh;
                $maleTrend[$name][$yr] = $male;
                $femaleTrend[$name][$yr] = $female;
                $benefTrend[$name][$yr] = $this->sumKnown($progVals);

                // Growth formula unchanged; only computed when both years have actual records
                if ($prevPop !== null && $prevPop > 0 && $pop !== null) {
                    $growthRates[$name][$yr] = round(($pop - $prevPop) / $prevPop * 100, 2);
                } else {
                    $growthRates[$name][$yr] = null;
                }
                $prevPop = $pop;
            }
        }

        return [
            $populationTrend,
            $maleTrend,
            $femaleTrend,
            $householdsTrend,
            $benefTrend,
            $programTrend,
            $growthRates
        ];
    }

    private function oneWayAnova(array $groups): ?array
    {
        $groups = array_values($groups);
        $k = count($groups);
        $allValues = array_merge(...$groups);
        $n_total = count($allValues);

        if ($n_total < $k + 1 || $k < 2)
            return null;

        $grandMean = array_sum($allValues) / $n_total;
        $ssBetween = 0;
        $groupMeans = [];

        foreach ($groups as $group) {
            $n = count($group);
            if ($n === 0)
                return null;
            $mean = array_sum($group) / $n;
            $groupMeans[] = round($mean, 2);
            $ssBetween += $n * (($mean - $grandMean) ** 2);
        }

        $ssWithin = 0;
        foreach ($groups as $i => $group) {
            foreach ($group as $val) {
                $ssWithin += ($val - $groupMeans[$i]) ** 2;
            }
        }

        $dfBetween = $k - 1;
        $dfWithin = $n_total - $k;

        if ($dfWithin <= 0 || $ssWithin == 0) {
            return ['F' => 0, 'significant' => false, 'dfBetween' => $dfBetween, 'dfWithin' => $dfWithin, 'groupMeans' => $groupMeans];
        }

        $F = round(($ssBetween / $dfBetween) / ($ssWithin / $dfWithin), 4);

        // Critical F (alpha=0.05, df1=2) by df2 lookup
        $criticalF = $dfWithin >= 20 ? 3.49 : ($dfWithin >= 10 ? 4.10 : ($dfWithin >= 6 ? 5.14 : ($dfWithin >= 3 ? 9.55 : 19.0)));

        return [
            'F' => $F,
            'significant' => $F > $criticalF,
            'dfBetween' => $dfBetween,
            'dfWithin' => $dfWithin,
            'groupMeans' => $groupMeans,
        ];
    }

    private function pearsonCorr(array $x, array $y): ?float
    {
        $n = count($x);
        if ($n < 2 || count($y) !== $n)
            return null;

        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = $sumX2 = $sumY2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] ** 2;
            $sumY2 += $y[$i] ** 2;
        }

        $num = $n * $sumXY - $sumX * $sumY;
        $den = sqrt(($n * $sumX2 - $sumX ** 2) * ($n * $sumY2 - $sumY ** 2));

        return $den == 0 ? null : round($num / $den, 4);
    }
}
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

        // -- Programs report: modal view in the page, plus download via ?download=report&format=docx|csv|html --
        $programsReport = $this->buildProgramsReport(
            $coreNames,
            $snapshot,
            $programTypes,
            $programLabels,
            $benefTrend,
            $programYears,
            $summaryYears,
            $selectedYear,
            $selectedCategory
        );
        if ($request->query('download') === 'report' && in_array($selectedCategory, ['programs', 'all'], true)) {
            return $this->downloadProgramsReport($programsReport, strtolower((string) $request->query('format', 'html')));
        }
        $reportHtml = $this->renderProgramsReportHtml($programsReport);

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
            'visionData',
            'programsReport',
            'reportHtml'
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

    // -- Programs report: page panel + HTML download -------------------------------

    /**
     * Builds the programs report from data already computed in programs().
     * Descriptive figures only. AHP and WSM are reported as "not computed" and the
     * exact missing inputs are listed; no criteria, weights or scores are invented.
     */
    private function buildProgramsReport(
        array $coreNames,
        array $snapshot,
        array $programTypes,
        array $programLabels,
        array $benefTrend,
        array $programYears,
        array $summaryYears,
        int $selectedYear,
        string $selectedCategory
    ): array {
        $categoryLabel = $selectedCategory === 'all' ? 'All Data (programs report)' : 'Social Welfare Programs';

        // Per-municipality rows (null = no data, never 0)
        $muniRows = [];
        $withData = [];
        foreach ($coreNames as $name) {
            $row = $snapshot[$name] ?? [];
            $programs = $row['programs'] ?? [];
            $benef = $row['beneficiaries'] ?? null;
            $missingTypes = [];
            foreach ($programTypes as $type) {
                if (($programs[$type] ?? null) === null) {
                    $missingTypes[] = $programLabels[$type] ?? $type;
                }
            }
            if ($benef !== null) {
                $withData[] = $name;
            }
            $muniRows[] = [
                'name' => $name,
                'population' => $row['population'] ?? null,
                'households' => $row['households'] ?? null,
                'beneficiaries' => $benef,
                'benef_pct' => $row['benef_pct'] ?? null,
                'missing_types' => $benef !== null ? $missingTypes : [],
            ];
        }

        // Program x municipality rows
        $programRows = [];
        $grandTotal = null;
        foreach ($programTypes as $type) {
            $values = [];
            foreach ($coreNames as $name) {
                $values[$name] = $snapshot[$name]['programs'][$type] ?? null;
            }
            $known = array_filter($values, fn($v) => $v !== null);
            $total = count($known) > 0 ? (int) array_sum($known) : null;
            if ($total !== null) {
                $grandTotal = ($grandTotal ?? 0) + $total;
            }
            $programRows[] = [
                'label' => $programLabels[$type] ?? $type,
                'values' => $values,
                'total' => $total,
                'share' => null,
            ];
        }
        $maxTotal = 0;
        foreach ($programRows as $i => $pr) {
            if ($grandTotal !== null && $grandTotal > 0 && $pr['total'] !== null) {
                $programRows[$i]['share'] = round($pr['total'] / $grandTotal * 100, 1);
            }
            if ($pr['total'] !== null && $pr['total'] > $maxTotal) {
                $maxTotal = $pr['total'];
            }
        }
        $largest = [];
        if ($maxTotal > 0) {
            foreach ($programRows as $pr) {
                if ($pr['total'] === $maxTotal) {
                    $largest[] = $pr['label'];
                }
            }
        }

        // Yearly beneficiary totals (all years that have program records)
        $trendRows = [];
        foreach ($programYears as $yr) {
            $vals = [];
            foreach ($coreNames as $name) {
                $vals[$name] = $benefTrend[$name][$yr] ?? null;
            }
            $known = array_filter($vals, fn($v) => $v !== null);
            $trendRows[] = [
                'year' => $yr,
                'values' => $vals,
                'total' => count($known) > 0 ? (int) array_sum($known) : null,
            ];
        }

        // Data limitations that apply to this selection
        $notes = [];
        $notes[] = 'Figures are beneficiary counts as recorded in the program records. No budget, unit cost, eligibility, need or outcome data are used, so the counts describe recorded reach only and do not measure program effectiveness.';
        $notes[] = 'The records do not state whether a person who receives more than one program is counted once or once per program, so the total across programs may not equal the number of unique persons.';
        if ($grandTotal === null) {
            $notes[] = 'No program beneficiary records exist for ' . $selectedYear . '; every program figure is N/A.';
        }
        $censusNote = !empty($summaryYears) ? ' Census records exist for: ' . implode(', ', $summaryYears) . '.' : '';
        foreach ($muniRows as $m) {
            if ($m['beneficiaries'] === null) {
                if ($grandTotal !== null) {
                    $notes[] = $m['name'] . ': no program records for ' . $selectedYear . ' (shown as N/A, not 0).';
                }
                continue;
            }
            if (!empty($m['missing_types'])) {
                $notes[] = $m['name'] . ': no recorded count for ' . implode(', ', $m['missing_types']) . ' in ' . $selectedYear . '; the total covers recorded programs only and may be understated.';
            }
            if ($m['population'] === null) {
                $notes[] = $m['name'] . ': no census population for ' . $selectedYear . ', so beneficiaries as a share of population is not calculated.' . $censusNote;
            }
        }
        if (!empty($programYears)) {
            $notes[] = 'Years without records appear as N/A. Yearly totals may cover different municipalities and program types, so compare years with care.';
        }
        $notes[] = 'This page has no municipality filter; the report covers every municipality listed in the system.';

        $mcdm = [
            'status' => 'Not computed',
            'reason' => 'AHP and WSM need decision criteria, approved weights and a value for every criterion for each option being ranked. The data this page reads hold beneficiary counts by program, municipality and year, plus census population and household figures. Beneficiary counts alone do not show program effectiveness, need or eligibility, so ranking on them and labelling the result AHP or WSM would not be valid. No AHP or WSM score, weight or ranking is shown for that reason.',
            'available' => [
                'Program beneficiary counts by municipality, program type and year.',
                'Census population, household, sex and age-group figures by municipality and census year.',
            ],
            'missing' => [
                'What is being ranked and for what decision (for example programs, municipalities or program-municipality pairs).',
                'The criteria MSWDO approves for that decision, each with the data field that measures it and whether a higher or a lower value is better. This page reads no need, poverty, budget, cost, target-population or outcome field.',
                'For AHP: pairwise comparison judgments between every pair of criteria from MSWDO decision-makers on the 1 to 9 scale (n x (n-1) / 2 judgments for n criteria). A consistency check needs at least 3 criteria.',
                'For WSM: the criterion weights (derived from the AHP result or officially adopted), the value of every criterion for every option in the same reporting year, the normalization method to use, and how missing values are handled.',
                'Written approval of the criteria and weights by the responsible MSWDO officer or committee.',
            ],
            'ahp_steps' => [
                'Build the reciprocal pairwise comparison matrix from the approved judgments.',
                'Normalize each column and average each row to obtain the criterion weights (or use the principal eigenvector).',
                'Compute lambda_max, the consistency index CI = (lambda_max - n) / (n - 1) and the consistency ratio CR = CI / RI, using Saaty random index (RI) values.',
                'Accept the weights only if CR < 0.10; otherwise return the judgments for review.',
            ],
            'wsm_steps' => [
                'Normalize each criterion with the approved method (for example benefit: x / max; cost: min / x).',
                'Score each option as the sum of weight x normalized value across all criteria.',
                'Rank the options by score and publish the weights, normalized values and scores in the report tables.',
            ],
        ];

        $periodText = 'Reporting year ' . $selectedYear;
        if (!empty($programYears)) {
            $periodText .= '; program records available ' . (count($programYears) > 1 ? min($programYears) . ' to ' . max($programYears) : (string) reset($programYears));
        }

        return [
            'meta' => [
                'title' => 'Social Welfare Programs Analysis Report',
                'year' => $selectedYear,
                'category' => $categoryLabel,
                'period' => $periodText,
                'municipalities' => $coreNames,
                'generated' => date('F j, Y, g:i A'),
            ],
            'summary' => [
                'total_beneficiaries' => $grandTotal,
                'municipalities_total' => count($coreNames),
                'municipalities_with_data' => count($withData),
                'program_type_count' => count($programTypes),
                'largest_programs' => $largest,
            ],
            'municipalities' => $muniRows,
            'programs' => $programRows,
            'trend' => $trendRows,
            'notes' => $notes,
            'methodology' => [
                'Beneficiary counts come from the program records (one count per program type, municipality and year).',
                'A municipality total is the sum of its recorded program counts. A program with no record is treated as N/A and is never counted as 0; an actual recorded 0 stays 0.',
                'Beneficiaries as % of population = municipality beneficiaries / census population of the same year x 100. It is calculated only when both values exist.',
                'Share of total = program total / all-program total x 100, using recorded values only.',
                'Population and household figures come from census records, which exist only for certain years.',
                'This report is descriptive. It does not rank, score or recommend programs or municipalities.',
            ],
            'mcdm' => $mcdm,
        ];
    }

    /** Sends the report as a real file download: html (default), csv or docx. */
    private function downloadProgramsReport(array $report, string $format = 'html')
    {
        $base = 'MSWDO_Programs_Report_' . (int) $report['meta']['year'] . '_' . date('Ymd');

        if ($format === 'csv') {
            $body = $this->renderProgramsReportCsv($report);
            $type = 'text/csv; charset=UTF-8';
            $ext = 'csv';
        } elseif ($format === 'docx') {
            $body = $this->renderProgramsReportDocx($report);
            if ($body === null) {
                abort(501, 'Word export needs the PHP zip extension (php_zip). Enable it in php.ini, or use the CSV or HTML download.');
            }
            $type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            $ext = 'docx';
        } elseif ($format === 'html') {
            $body = $this->renderProgramsReportHtml($report);
            $type = 'text/html; charset=UTF-8';
            $ext = 'html';
        } else {
            abort(400, 'Unsupported report format. Use docx, csv or html.');
        }

        return response($body, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'attachment; filename="' . $base . '.' . $ext . '"',
            'Content-Length' => (string) strlen($body),
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /** Renders the report array as one self-contained, print-friendly HTML document. */
    private function renderProgramsReportHtml(array $r): string
    {
        $h = function ($v) {
            return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        };
        $num = function ($v) use ($h) {
            return $v === null ? '<span class="na">N/A</span>' : $h(number_format($v));
        };
        $pct = function ($v) use ($h) {
            return $v === null ? '<span class="na">N/A</span>' : $h(number_format($v, 1)) . '%';
        };
        $list = function (array $items, string $tag = 'ul') use ($h) {
            $out = '<' . $tag . '>';
            foreach ($items as $item) {
                $out .= '<li>' . $h($item) . '</li>';
            }
            return $out . '</' . $tag . '>';
        };

        $meta = $r['meta'];
        $sum = $r['summary'];
        $mcdm = $r['mcdm'];
        $names = $meta['municipalities'];

        $css = implode('', [
            'body{margin:0;background:#f0f4f8;color:#1e293b;font:15px/1.6 "Segoe UI",Arial,Helvetica,sans-serif;}',
            'main{max-width:960px;margin:0 auto;padding:24px 16px 48px;}',
            'header.rep-head{background:#2C3E8F;color:#fff;border-radius:12px;padding:24px 28px;border-bottom:5px solid #FDB913;}',
            'header.rep-head h1{margin:0 0 6px;font-size:1.6rem;}',
            'header.rep-head p{margin:0;opacity:.9;}',
            'section{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px 24px;margin-top:20px;}',
            'h2{margin:0 0 12px;font-size:1.15rem;color:#2C3E8F;}',
            'dl.meta{display:grid;grid-template-columns:max-content 1fr;gap:6px 18px;margin:0;}',
            'dl.meta dt{font-weight:700;}dl.meta dd{margin:0;}',
            '.table-wrap{overflow-x:auto;}',
            'table{border-collapse:collapse;width:100%;font-size:.9rem;}',
            'caption{text-align:left;font-weight:700;padding:0 0 8px;}',
            'th,td{border:1px solid #e2e8f0;padding:8px 10px;text-align:right;}',
            'th:first-child,td:first-child{text-align:left;}',
            'thead th{background:#E5EEFF;color:#1A2A5C;}',
            'tfoot th,tfoot td{font-weight:700;background:#f8fafc;}',
            '.na{color:#64748b;font-style:italic;}',
            '.badge{display:inline-block;border:1px solid #cbd5e1;background:#f1f5f9;border-radius:999px;padding:2px 12px;font-weight:700;font-size:.82rem;margin-right:8px;}',
            'h3{margin:16px 0 4px;font-size:1rem;}',
            'ul,ol{margin:8px 0 0;padding-left:22px;}li{margin-bottom:6px;}',
            'footer{margin-top:20px;font-size:.8rem;color:#64748b;text-align:center;}',
            '@media print{body{background:#fff;}section{break-inside:avoid;}}',
        ]);

        $o = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
        $o .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $o .= '<title>' . $h($meta['title'] . ' - ' . $meta['year']) . '</title>';
        $o .= '<style>' . $css . '</style></head><body><main>';

        // Title block
        $o .= '<header class="rep-head"><h1>' . $h($meta['title']) . '</h1>';
        $o .= '<p>Municipal Social Welfare and Development Office (MSWDO) &middot; ' . $h($meta['period']) . '</p></header>';

        // Report details
        $o .= '<section aria-labelledby="s-details"><h2 id="s-details">Report Details</h2><dl class="meta">';
        $o .= '<dt>Reporting period</dt><dd>' . $h($meta['period']) . '</dd>';
        $o .= '<dt>Dataset</dt><dd>' . $h($meta['category']) . '</dd>';
        $o .= '<dt>Municipalities</dt><dd>' . $h(implode(', ', $names)) . '</dd>';
        $o .= '<dt>Date generated</dt><dd>' . $h($meta['generated']) . '</dd></dl></section>';

        // Data summary
        $o .= '<section aria-labelledby="s-summary"><h2 id="s-summary">Data Summary</h2><ul>';
        $o .= '<li>Total recorded beneficiaries in ' . $h($meta['year']) . ': <strong>' . $num($sum['total_beneficiaries']) . '</strong></li>';
        $o .= '<li>Municipalities with program records: <strong>' . $h($sum['municipalities_with_data']) . ' of ' . $h($sum['municipalities_total']) . '</strong></li>';
        $o .= '<li>Program types in the records: <strong>' . $h($sum['program_type_count']) . '</strong></li>';
        if (!empty($sum['largest_programs'])) {
            $o .= '<li>Largest recorded beneficiary count: <strong>' . $h(implode(', ', $sum['largest_programs'])) . '</strong> (a count of recorded beneficiaries, not a measure of need or effectiveness)</li>';
        }
        $o .= '</ul></section>';

        // Table A: programs x municipalities
        $o .= '<section aria-labelledby="s-programs"><h2 id="s-programs">Beneficiaries by Program, ' . $h($meta['year']) . '</h2>';
        if (empty($r['programs'])) {
            $o .= '<p class="na">No program types are recorded.</p>';
        } else {
            $o .= '<div class="table-wrap"><table><caption>Recorded beneficiaries per program and municipality (N/A = no record, not zero)</caption><thead><tr><th scope="col">Program</th>';
            foreach ($names as $n) {
                $o .= '<th scope="col">' . $h($n) . '</th>';
            }
            $o .= '<th scope="col">Total</th><th scope="col">Share of total</th></tr></thead><tbody>';
            foreach ($r['programs'] as $pr) {
                $o .= '<tr><th scope="row">' . $h($pr['label']) . '</th>';
                foreach ($names as $n) {
                    $o .= '<td>' . $num($pr['values'][$n] ?? null) . '</td>';
                }
                $o .= '<td><strong>' . $num($pr['total']) . '</strong></td><td>' . $pct($pr['share']) . '</td></tr>';
            }
            $o .= '</tbody><tfoot><tr><th scope="row">All programs</th>';
            foreach ($r['municipalities'] as $m) {
                $o .= '<td>' . $num($m['beneficiaries']) . '</td>';
            }
            $o .= '<td>' . $num($sum['total_beneficiaries']) . '</td><td>' . ($sum['total_beneficiaries'] !== null && $sum['total_beneficiaries'] > 0 ? '100.0%' : '<span class="na">N/A</span>') . '</td></tr></tfoot></table></div>';
        }
        $o .= '</section>';

        // Table B: municipality summary
        $o .= '<section aria-labelledby="s-munis"><h2 id="s-munis">Municipality Summary, ' . $h($meta['year']) . '</h2>';
        $o .= '<div class="table-wrap"><table><caption>Beneficiaries relative to census population (N/A = not available for this year)</caption><thead><tr>';
        $o .= '<th scope="col">Municipality</th><th scope="col">Population</th><th scope="col">Households</th><th scope="col">Beneficiaries</th><th scope="col">Beneficiaries as % of population</th></tr></thead><tbody>';
        foreach ($r['municipalities'] as $m) {
            $o .= '<tr><th scope="row">' . $h($m['name']) . '</th><td>' . $num($m['population']) . '</td><td>' . $num($m['households']) . '</td><td>' . $num($m['beneficiaries']) . '</td><td>' . $pct($m['benef_pct']) . '</td></tr>';
        }
        $o .= '</tbody></table></div></section>';

        // Table C: yearly totals
        $o .= '<section aria-labelledby="s-trend"><h2 id="s-trend">Yearly Beneficiary Totals</h2>';
        if (empty($r['trend'])) {
            $o .= '<p class="na">No program records are available for any year.</p>';
        } else {
            $o .= '<div class="table-wrap"><table><caption>Total recorded beneficiaries per year (N/A = no record, not zero)</caption><thead><tr><th scope="col">Year</th>';
            foreach ($names as $n) {
                $o .= '<th scope="col">' . $h($n) . '</th>';
            }
            $o .= '<th scope="col">All municipalities</th></tr></thead><tbody>';
            foreach ($r['trend'] as $t) {
                $o .= '<tr><th scope="row">' . $h($t['year']) . '</th>';
                foreach ($names as $n) {
                    $o .= '<td>' . $num($t['values'][$n] ?? null) . '</td>';
                }
                $o .= '<td><strong>' . $num($t['total']) . '</strong></td></tr>';
            }
            $o .= '</tbody></table></div>';
        }
        $o .= '</section>';

        // Methodology
        $o .= '<section aria-labelledby="s-method"><h2 id="s-method">Methodology</h2>';
        $o .= $list($r['methodology']);
        $o .= '</section>';

        // AHP and WSM
        $o .= '<section aria-labelledby="s-mcdm"><h2 id="s-mcdm">AHP and WSM Results</h2>';
        $o .= '<p><span class="badge">AHP: ' . $h($mcdm['status']) . '</span><span class="badge">WSM: ' . $h($mcdm['status']) . '</span></p>';
        $o .= '<p>' . $h($mcdm['reason']) . '</p>';
        $o .= '<h3>Data available now</h3>' . $list($mcdm['available']);
        $o .= '<h3>Inputs required before AHP and WSM can be calculated</h3>' . $list($mcdm['missing'], 'ol');
        $o .= '<h3>How AHP will be calculated once inputs are supplied</h3>' . $list($mcdm['ahp_steps'], 'ol');
        $o .= '<h3>How WSM will be calculated once inputs are supplied</h3>' . $list($mcdm['wsm_steps'], 'ol');
        $o .= '</section>';

        // Limitations
        $o .= '<section aria-labelledby="s-limits"><h2 id="s-limits">Limitations and Missing-Data Notes</h2>' . $list($r['notes']) . '</section>';

        $o .= '<footer>Generated ' . $h($meta['generated']) . ' from the MSWDO Statistical Analysis page.</footer>';
        $o .= '</main></body></html>';

        return $o;
    }

    /**
     * CSV export: UTF-8 with BOM (opens correctly in Excel) and CRLF line endings.
     *
     * Layout: a title block, a contents list and a reading guide, then eight numbered sections.
     * Every section has a title row, a one-line description, a header row and the data rows,
     * and sections are separated by a blank row.
     *
     * Number formatting (the same everywhere):
     *  - counts are whole numbers without thousands separators, so spreadsheets can sort and sum them;
     *  - percentages have exactly one decimal place and the column heading says "(%)";
     *  - missing data is the text N/A and is never written as 0.
     */
    private function renderProgramsReportCsv(array $r): string
    {
        $meta = $r['meta'];
        $sum = $r['summary'];
        $names = $meta['municipalities'];
        $mcdm = $r['mcdm'];
        $year = $meta['year'];

        $fh = fopen('php://temp', 'r+');
        // Text that starts with = + - @ could run as a spreadsheet formula, so it is prefixed with an apostrophe.
        $safe = function ($v) {
            if (is_string($v) && $v !== '' && strpos("=+-@\t\r", $v[0]) !== false) {
                return "'" . $v;
            }
            return $v;
        };
        $put = function (array $cells) use ($fh, $safe) {
            fputcsv($fh, array_map($safe, $cells), ',', '"', '', "\r\n");
        };
        $blank = function () use ($fh) {
            fwrite($fh, "\r\n");
        };
        $int = function ($v) {
            return $v === null ? 'N/A' : (int) $v;
        };
        $dec = function ($v) {
            return $v === null ? 'N/A' : number_format((float) $v, 1, '.', '');
        };
        // Section banner: blank row, "N. TITLE", then a one-line description of what the section shows.
        $section = function ($no, $title, $desc) use ($put, $blank) {
            $blank();
            $put([$no . '. ' . strtoupper($title)]);
            $put([$desc]);
        };
        // Numbered list as a two-column table (No. + text).
        $numbered = function ($heading, array $items) use ($put) {
            $put([$heading]);
            $put(['No.', 'Description']);
            foreach (array_values($items) as $i => $line) {
                $put([$i + 1, $line]);
            }
        };
        // Name(s) of the municipality with the largest known value.
        $highest = function (array $values) {
            $known = array_filter($values, function ($v) {
                return $v !== null;
            });
            if (count($known) === 0) {
                return 'N/A';
            }
            $max = max($known);
            if ($max <= 0) {
                return 'None (all recorded values are 0)';
            }
            $top = array_keys(array_filter($known, function ($v) use ($max) {
                return $v === $max;
            }));
            return implode(' / ', $top);
        };

        // ---- Title block, contents and reading guide ----
        $put([$meta['title']]);
        $put(['Municipal Social Welfare and Development Office (MSWDO)']);
        $put(['Reporting year ' . $year . ' | Dataset: ' . $meta['category']]);

        $blank();
        $put(['CONTENTS']);
        $put(['Section', 'What it shows']);
        $put(['1. Report details', 'Reporting period, dataset, municipalities covered and date generated']);
        $put(['2. Data summary', 'Headline totals for the selected year']);
        $put(['3. Beneficiaries by program', 'Recorded beneficiaries for each program and municipality, with totals and share of total']);
        $put(['4. Municipality summary', 'Population, households and beneficiaries per municipality, with a data-status check']);
        $put(['5. Yearly beneficiary totals', 'Total recorded beneficiaries for every year that has program records']);
        $put(['6. Methodology', 'How the figures in this report are calculated']);
        $put(['7. AHP and WSM results', 'Why AHP and WSM are not computed, and the inputs needed to compute them']);
        $put(['8. Limitations and missing-data notes', 'Data gaps and cautions that apply to the selected year']);

        $blank();
        $put(['HOW TO READ THIS FILE']);
        $put(['Item', 'Meaning']);
        $put(['N/A', 'No record exists for that item, or the value cannot be calculated (for example a share of a total of 0). It is never a zero; an actual recorded 0 is shown as 0.']);
        $put(['Counts', 'Whole numbers of recorded beneficiaries, households or persons, written without thousands separators so spreadsheets can sort and add them.']);
        $put(['Percentages', 'Numbers with one decimal place; the column heading ends in (%). A value of 12.5 means 12.5 percent.']);
        $put(['Layout', 'Each numbered section has a title, a one-line description, a header row and then the data rows. Blank rows separate sections.']);

        // ---- 1. Report details ----
        $section(1, 'Report details', 'Basic information about this report.');
        $put(['Field', 'Value']);
        $put(['Report title', $meta['title']]);
        $put(['Office', 'Municipal Social Welfare and Development Office (MSWDO)']);
        $put(['Reporting year', $year]);
        $put(['Reporting period', $meta['period']]);
        $put(['Dataset', $meta['category']]);
        $put(['Municipalities covered', implode(', ', $names)]);
        $put(['Date generated', $meta['generated']]);

        // ---- 2. Data summary ----
        $section(2, 'Data summary', 'Headline totals for reporting year ' . $year . '.');
        $put(['Measure', 'Value', 'Notes']);
        $put(['Total recorded beneficiaries', $int($sum['total_beneficiaries']), 'Sum of recorded program counts. N/A means there are no program records for ' . $year . '.']);
        $put(['Municipalities with program records', (int) $sum['municipalities_with_data'], 'Out of ' . (int) $sum['municipalities_total'] . ' municipalities in the system.']);
        $put(['Program types in the records', (int) $sum['program_type_count'], 'Distinct program types found in the program records.']);
        if (!empty($sum['largest_programs'])) {
            $put(['Program with the largest recorded count', implode(' / ', $sum['largest_programs']), 'A count of recorded beneficiaries, not a measure of need or effectiveness.']);
        }

        // ---- 3. Beneficiaries by program ----
        $section(3, 'Beneficiaries by program, ' . $year, 'Recorded beneficiaries (number of persons) for each program and municipality. N/A = no record, not zero.');
        if (empty($r['programs'])) {
            $put(['No program types are recorded.']);
        } else {
            $put(array_merge(['Program'], $names, ['Total, all municipalities', 'Share of total (%)', 'Highest municipality']));
            foreach ($r['programs'] as $pr) {
                $row = [$pr['label']];
                $vals = [];
                foreach ($names as $n) {
                    $v = $pr['values'][$n] ?? null;
                    $vals[$n] = $v;
                    $row[] = $int($v);
                }
                $row[] = $int($pr['total']);
                $row[] = $dec($pr['share']);
                $row[] = $highest($vals);
                $put($row);
            }
            $totalRow = ['TOTAL, ALL PROGRAMS'];
            $totVals = [];
            foreach ($r['municipalities'] as $m) {
                $totalRow[] = $int($m['beneficiaries']);
                $totVals[$m['name']] = $m['beneficiaries'];
            }
            $totalRow[] = $int($sum['total_beneficiaries']);
            $totalRow[] = ($sum['total_beneficiaries'] !== null && $sum['total_beneficiaries'] > 0) ? $dec(100) : 'N/A';
            $totalRow[] = $highest($totVals);
            $put($totalRow);
        }

        // ---- 4. Municipality summary ----
        $section(4, 'Municipality summary, ' . $year, 'Census population and households next to recorded beneficiaries. N/A = not available for this year.');
        $put(['Municipality', 'Population', 'Households', 'Total beneficiaries', 'Beneficiaries as share of population (%)', 'Data status']);
        foreach ($r['municipalities'] as $m) {
            if ($m['beneficiaries'] === null) {
                $status = 'No program records for ' . $year;
            } elseif (!empty($m['missing_types'])) {
                $status = 'Partial program data (no count for: ' . implode(', ', $m['missing_types']) . ')';
            } else {
                $status = 'Complete program data';
            }
            if ($m['population'] === null) {
                $status .= '; no census population for ' . $year;
            }
            $put([$m['name'], $int($m['population']), $int($m['households']), $int($m['beneficiaries']), $dec($m['benef_pct']), $status]);
        }

        // ---- 5. Yearly beneficiary totals ----
        $section(5, 'Yearly beneficiary totals', 'Total recorded beneficiaries per year, for every year that has program records. N/A = no record, not zero.');
        if (empty($r['trend'])) {
            $put(['No program records are available for any year.']);
        } else {
            $put(array_merge(['Year'], $names, ['All municipalities', 'Municipalities with records']));
            foreach ($r['trend'] as $t) {
                $row = [(int) $t['year']];
                $reporting = 0;
                foreach ($names as $n) {
                    $v = $t['values'][$n] ?? null;
                    if ($v !== null) {
                        $reporting++;
                    }
                    $row[] = $int($v);
                }
                $row[] = $int($t['total']);
                $row[] = $reporting . ' of ' . count($names);
                $put($row);
            }
        }

        // ---- 6. Methodology ----
        $section(6, 'Methodology', 'How the figures in this report are calculated.');
        $put(['No.', 'Description']);
        foreach (array_values($r['methodology']) as $i => $line) {
            $put([$i + 1, $line]);
        }

        // ---- 7. AHP and WSM results ----
        $section(7, 'AHP and WSM results', 'Status of the AHP and WSM analyses and what is needed before they can be calculated.');
        $put(['Item', 'Detail']);
        $put(['AHP status', $mcdm['status']]);
        $put(['WSM status', $mcdm['status']]);
        $put(['Reason', $mcdm['reason']]);
        $blank();
        $numbered('7a. Data available now', $mcdm['available']);
        $blank();
        $numbered('7b. Inputs required before AHP and WSM can be calculated', $mcdm['missing']);
        $blank();
        $numbered('7c. How AHP will be calculated once inputs are supplied', $mcdm['ahp_steps']);
        $blank();
        $numbered('7d. How WSM will be calculated once inputs are supplied', $mcdm['wsm_steps']);

        // ---- 8. Limitations ----
        $section(8, 'Limitations and missing-data notes', 'Data gaps and cautions that apply to reporting year ' . $year . '.');
        $put(['No.', 'Note']);
        foreach (array_values($r['notes']) as $i => $line) {
            $put([$i + 1, $line]);
        }

        // ---- End ----
        $blank();
        $put(['END OF REPORT']);
        $put(['Generated ' . $meta['generated'] . ' from the MSWDO Statistical Analysis page.']);

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return "\xEF\xBB\xBF" . $csv;
    }

    /**
     * Word (.docx) export built directly as an Office Open XML package (no extra library).
     * Returns null when the PHP zip extension is not available.
     */
    private function renderProgramsReportDocx(array $r): ?string
    {
        if (!class_exists('ZipArchive')) {
            return null;
        }

        $esc = function ($v) {
            $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', (string) $v);
            return htmlspecialchars((string) $s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        };
        $run = function ($text, $bold = false, $color = null, $size = null) use ($esc) {
            $pr = '';
            if ($bold) {
                $pr .= '<w:b/>';
            }
            if ($color !== null) {
                $pr .= '<w:color w:val="' . $color . '"/>';
            }
            if ($size !== null) {
                $pr .= '<w:sz w:val="' . (int) $size . '"/><w:szCs w:val="' . (int) $size . '"/>';
            }
            return '<w:r>' . ($pr !== '' ? '<w:rPr>' . $pr . '</w:rPr>' : '') . '<w:t xml:space="preserve">' . $esc($text) . '</w:t></w:r>';
        };
        $para = function ($runs, $style = null, $ppr = '') {
            $p = ($style !== null ? '<w:pStyle w:val="' . $style . '"/>' : '') . $ppr;
            return '<w:p>' . ($p !== '' ? '<w:pPr>' . $p . '</w:pPr>' : '') . $runs . '</w:p>';
        };
        $bullet = function ($text, $label) use ($run, $para) {
            return $para($run($label . ' ' . $text), null, '<w:spacing w:after="60"/><w:ind w:left="360" w:hanging="260"/>');
        };
        $labelled = function ($label, $value) use ($run, $para) {
            return $para($run($label . ': ', true) . $run($value), null, '<w:spacing w:after="40"/>');
        };
        $table = function (array $headers, array $rows) use ($run, $para) {
            $tw = 9638;
            $n = count($headers);
            $first = $n > 1 ? 2400 : $tw;
            $other = $n > 1 ? (int) floor(($tw - $first) / ($n - 1)) : 0;
            $grid = '<w:tblGrid><w:gridCol w:w="' . $first . '"/>';
            for ($i = 1; $i < $n; $i++) {
                $grid .= '<w:gridCol w:w="' . $other . '"/>';
            }
            $grid .= '</w:tblGrid>';
            $border = '';
            foreach (['top', 'left', 'bottom', 'right', 'insideH', 'insideV'] as $side) {
                $border .= '<w:' . $side . ' w:val="single" w:sz="4" w:space="0" w:color="CBD5E1"/>';
            }
            $cell = function ($t, $w, $isFirst, $head) use ($run, $para) {
                $tcpr = '<w:tcW w:w="' . $w . '" w:type="dxa"/>' . ($head ? '<w:shd w:val="clear" w:color="auto" w:fill="E5EEFF"/>' : '');
                $ppr = '<w:spacing w:before="0" w:after="0"/>' . ($isFirst ? '' : '<w:jc w:val="right"/>');
                return '<w:tc><w:tcPr>' . $tcpr . '</w:tcPr>' . $para($run($t, $head, $head ? '1A2A5C' : null, 18), null, $ppr) . '</w:tc>';
            };
            $x = '<w:tbl><w:tblPr><w:tblW w:w="' . $tw . '" w:type="dxa"/><w:tblBorders>' . $border . '</w:tblBorders>'
                . '<w:tblLayout w:type="fixed"/><w:tblCellMar><w:top w:w="40" w:type="dxa"/><w:left w:w="80" w:type="dxa"/><w:bottom w:w="40" w:type="dxa"/><w:right w:w="80" w:type="dxa"/></w:tblCellMar></w:tblPr>'
                . $grid;
            $x .= '<w:tr><w:trPr><w:cantSplit/><w:tblHeader/></w:trPr>';
            foreach ($headers as $i => $hd) {
                $x .= $cell($hd, $i === 0 ? $first : $other, $i === 0, true);
            }
            $x .= '</w:tr>';
            foreach ($rows as $row) {
                $x .= '<w:tr><w:trPr><w:cantSplit/></w:trPr>';
                foreach ($row as $i => $val) {
                    $x .= $cell($val, $i === 0 ? $first : $other, $i === 0, false);
                }
                $x .= '</w:tr>';
            }
            $x .= '</w:tbl>';
            return $x . $para('', null, '<w:spacing w:after="120"/>');
        };
        $n = function ($v) {
            return $v === null ? 'N/A' : number_format($v);
        };
        $p = function ($v) {
            return $v === null ? 'N/A' : number_format($v, 1) . '%';
        };

        $meta = $r['meta'];
        $sum = $r['summary'];
        $mcdm = $r['mcdm'];
        $names = $meta['municipalities'];
        $year = $meta['year'];

        $body = $para($run($meta['title']), 'Title');
        $body .= $para($run('Municipal Social Welfare and Development Office (MSWDO) - ' . $meta['period']), 'Subtitle');

        $body .= $para($run('Report Details'), 'Heading1');
        $body .= $labelled('Reporting period', $meta['period']);
        $body .= $labelled('Dataset', $meta['category']);
        $body .= $labelled('Municipalities', implode(', ', $names));
        $body .= $labelled('Date generated', $meta['generated']);

        $body .= $para($run('Data Summary'), 'Heading1');
        $body .= $bullet('Total recorded beneficiaries in ' . $year . ': ' . $n($sum['total_beneficiaries']), "\u{2022}");
        $body .= $bullet('Municipalities with program records: ' . $sum['municipalities_with_data'] . ' of ' . $sum['municipalities_total'], "\u{2022}");
        $body .= $bullet('Program types in the records: ' . $sum['program_type_count'], "\u{2022}");
        if (!empty($sum['largest_programs'])) {
            $body .= $bullet('Largest recorded beneficiary count: ' . implode(', ', $sum['largest_programs']) . ' (a count of recorded beneficiaries, not a measure of need or effectiveness)', "\u{2022}");
        }

        $body .= $para($run('Beneficiaries by Program, ' . $year), 'Heading1');
        if (empty($r['programs'])) {
            $body .= $para($run('No program types are recorded.'));
        } else {
            $rows = [];
            foreach ($r['programs'] as $pr) {
                $row = [$pr['label']];
                foreach ($names as $nm) {
                    $row[] = $n($pr['values'][$nm] ?? null);
                }
                $row[] = $n($pr['total']);
                $row[] = $p($pr['share']);
                $rows[] = $row;
            }
            $allRow = ['All programs'];
            foreach ($r['municipalities'] as $m) {
                $allRow[] = $n($m['beneficiaries']);
            }
            $allRow[] = $n($sum['total_beneficiaries']);
            $allRow[] = ($sum['total_beneficiaries'] !== null && $sum['total_beneficiaries'] > 0) ? '100.0%' : 'N/A';
            $rows[] = $allRow;
            $body .= $para($run('Recorded beneficiaries per program and municipality (N/A = no record, not zero)', false, '64748B', 18));
            $body .= $table(array_merge(['Program'], $names, ['Total', 'Share of total']), $rows);
        }

        $body .= $para($run('Municipality Summary, ' . $year), 'Heading1');
        $rows = [];
        foreach ($r['municipalities'] as $m) {
            $rows[] = [$m['name'], $n($m['population']), $n($m['households']), $n($m['beneficiaries']), $p($m['benef_pct'])];
        }
        $body .= $para($run('Beneficiaries relative to census population (N/A = not available for this year)', false, '64748B', 18));
        $body .= $table(['Municipality', 'Population', 'Households', 'Beneficiaries', '% of population'], $rows);

        $body .= $para($run('Yearly Beneficiary Totals'), 'Heading1');
        if (empty($r['trend'])) {
            $body .= $para($run('No program records are available for any year.'));
        } else {
            $rows = [];
            foreach ($r['trend'] as $t) {
                $row = [(string) $t['year']];
                foreach ($names as $nm) {
                    $row[] = $n($t['values'][$nm] ?? null);
                }
                $row[] = $n($t['total']);
                $rows[] = $row;
            }
            $body .= $para($run('Total recorded beneficiaries per year (N/A = no record, not zero)', false, '64748B', 18));
            $body .= $table(array_merge(['Year'], $names, ['All municipalities']), $rows);
        }

        $body .= $para($run('Methodology'), 'Heading1');
        foreach ($r['methodology'] as $line) {
            $body .= $bullet($line, "\u{2022}");
        }

        $body .= $para($run('AHP and WSM Results'), 'Heading1');
        $body .= $para($run('AHP: ' . $mcdm['status'] . '     WSM: ' . $mcdm['status'], true));
        $body .= $para($run($mcdm['reason']));
        $body .= $para($run('Data available now'), 'Heading2');
        foreach ($mcdm['available'] as $line) {
            $body .= $bullet($line, "\u{2022}");
        }
        $body .= $para($run('Inputs required before AHP and WSM can be calculated'), 'Heading2');
        foreach ($mcdm['missing'] as $i => $line) {
            $body .= $bullet($line, ($i + 1) . '.');
        }
        $body .= $para($run('How AHP will be calculated once inputs are supplied'), 'Heading2');
        foreach ($mcdm['ahp_steps'] as $i => $line) {
            $body .= $bullet($line, ($i + 1) . '.');
        }
        $body .= $para($run('How WSM will be calculated once inputs are supplied'), 'Heading2');
        foreach ($mcdm['wsm_steps'] as $i => $line) {
            $body .= $bullet($line, ($i + 1) . '.');
        }

        $body .= $para($run('Limitations and Missing-Data Notes'), 'Heading1');
        foreach ($r['notes'] as $line) {
            $body .= $bullet($line, "\u{2022}");
        }
        $body .= $para($run('Generated ' . $meta['generated'] . ' from the MSWDO Statistical Analysis page.', false, '64748B', 18), null, '<w:spacing w:before="240"/>');

        $ns = 'xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"';
        $head = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $document = $head . '<w:document ' . $ns . '><w:body>' . $body
            . '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1134" w:right="1134" w:bottom="1134" w:left="1134" w:header="708" w:footer="708" w:gutter="0"/></w:sectPr></w:body></w:document>';

        $styles = $head . '<w:styles ' . $ns . '>'
            . '<w:docDefaults><w:rPrDefault><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:eastAsia="Calibri" w:cs="Calibri"/><w:sz w:val="21"/><w:szCs w:val="21"/><w:lang w:val="en-US"/></w:rPr></w:rPrDefault>'
            . '<w:pPrDefault><w:pPr><w:spacing w:after="100" w:line="264" w:lineRule="auto"/></w:pPr></w:pPrDefault></w:docDefaults>'
            . '<w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/><w:qFormat/></w:style>'
            . '<w:style w:type="paragraph" w:styleId="Title"><w:name w:val="Title"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:pPr><w:spacing w:after="60"/></w:pPr><w:rPr><w:b/><w:color w:val="2C3E8F"/><w:sz w:val="44"/><w:szCs w:val="44"/></w:rPr></w:style>'
            . '<w:style w:type="paragraph" w:styleId="Subtitle"><w:name w:val="Subtitle"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:pPr><w:spacing w:after="240"/></w:pPr><w:rPr><w:color w:val="64748B"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr></w:style>'
            . '<w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:pPr><w:keepNext/><w:spacing w:before="320" w:after="120"/><w:outlineLvl w:val="0"/></w:pPr><w:rPr><w:b/><w:color w:val="2C3E8F"/><w:sz w:val="30"/><w:szCs w:val="30"/></w:rPr></w:style>'
            . '<w:style w:type="paragraph" w:styleId="Heading2"><w:name w:val="heading 2"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:pPr><w:keepNext/><w:spacing w:before="200" w:after="80"/><w:outlineLvl w:val="1"/></w:pPr><w:rPr><w:b/><w:color w:val="1A2A5C"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:style>'
            . '</w:styles>';

        $contentTypes = $head . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
            . '<Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/></Types>';
        $rootRels = $head . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>';
        $docRels = $head . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>';

        $tmp = tempnam(sys_get_temp_dir(), 'mswdo');
        if ($tmp === false) {
            return null;
        }
        $zip = new \ZipArchive();
        if ($zip->open($tmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            @unlink($tmp);
            return null;
        }
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rootRels);
        $zip->addFromString('word/document.xml', $document);
        $zip->addFromString('word/_rels/document.xml.rels', $docRels);
        $zip->addFromString('word/styles.xml', $styles);
        $zip->close();

        $bin = file_get_contents($tmp);
        @unlink($tmp);

        return $bin === false ? null : $bin;
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
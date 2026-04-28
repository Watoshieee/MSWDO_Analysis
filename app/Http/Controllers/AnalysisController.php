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
     * Main public page at /analysis â€” About / Programs info.
     * Labelled "Programs" in the navbar (1st nav item).
     */
    public function index(Request $request)
    {
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
                $yearlyPopulation[$muni][$yr] = $row ? $row->total_population : 0;
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
                'vision'          => $row?->vision ?? '',
                'mission'         => $row?->mission ?? '',
                'goals'           => $row?->goals ?? '',
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
        $coreNames = Municipality::orderBy('name')->pluck('name')->toArray();
        $palette   = ['#2C3E8F','#FDB913','#6366f1','#16a34a','#9333ea','#0891b2','#ea580c','#db2777','#65a30d','#d97706'];
        $colors    = [];
        foreach ($coreNames as $i => $n) { $colors[$n] = $palette[$i % count($palette)]; }

        // â”€â”€ All unique years across summaries â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $allYears = MunicipalityYearlySummary::whereIn('municipality', $coreNames)
            ->distinct()->orderBy('year')->pluck('year')->toArray();
        if (empty($allYears)) $allYears = [(int) date('Y')];

        $latestYear = end($allYears);
        $selectedYear = (int) $request->input('year', $latestYear);

        // â”€â”€ Per-year, per-municipality data â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $summariesByMuni = [];
        foreach ($coreNames as $name) {
            $rows = MunicipalityYearlySummary::where('municipality', $name)
                ->orderBy('year')->get();
            $summariesByMuni[$name] = $rows->keyBy('year');
        }

        // â”€â”€ Trend arrays (indexed by $allYears) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $populationTrend  = [];  // [muni => [yr => pop]]
        $householdsTrend  = [];  // [muni => [yr => hh]]
        $benefTrend       = [];  // [muni => [yr => total_benef]]
        foreach ($coreNames as $name) {
            $populationTrend[$name]  = [];
            $householdsTrend[$name]  = [];
            $benefTrend[$name]       = [];
            foreach ($allYears as $yr) {
                $row = $summariesByMuni[$name][$yr] ?? null;
                $populationTrend[$name][$yr]  = $row ? (int)$row->total_population : 0;
                $householdsTrend[$name][$yr]  = $row ? (int)$row->total_households : 0;
                $benefTrend[$name][$yr] = $row
                    ? ((int)$row->total_pwd + (int)$row->total_aics + (int)$row->total_solo_parent
                        + (int)$row->total_4ps + (int)$row->total_senior)
                    : 0;
            }
        }

        // â”€â”€ Selected-year demographic data â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $demographicData = [];
        foreach ($coreNames as $name) {
            $row = $summariesByMuni[$name][$selectedYear] ?? null;
            $pop  = $row ? (int)$row->total_population : 0;
            $male = $row ? (int)$row->male_population  : 0;
            $female = $row ? (int)$row->female_population : 0;
            $hh   = $row ? (int)$row->total_households : 0;
            $pwd  = $row ? (int)$row->total_pwd    : 0;
            $aics = $row ? (int)$row->total_aics   : 0;
            $solo = $row ? (int)$row->total_solo_parent : 0;
            $fps  = $row ? (int)$row->total_4ps    : 0;
            $sen  = $row ? (int)$row->total_senior  : 0;
            $age0  = $row ? (int)$row->population_0_19   : 0;
            $age20 = $row ? (int)$row->population_20_59  : 0;
            $age60 = $row ? (int)$row->population_60_100 : 0;
            $totalBenef = $pwd + $aics + $solo + $fps + $sen;

            $demographicData[$name] = [
                'total'           => $pop,
                'male'            => $male,
                'female'          => $female,
                'households'      => $hh,
                'avg_hh_size'     => ($hh > 0 && $pop > 0) ? round($pop / $hh, 1) : 0,
                'beneficiaries'   => $totalBenef,
                'pwd'             => $pwd,
                'aics'            => $aics,
                'solo_parent'     => $solo,
                'four_ps'         => $fps,
                'senior'          => $sen,
                'age_0_19'        => $age0,
                'age_20_59'       => $age20,
                'age_60_100'      => $age60,
                'age_0_19_pct'    => $pop > 0 ? round($age0  / $pop * 100, 1) : 0,
                'age_20_59_pct'   => $pop > 0 ? round($age20 / $pop * 100, 1) : 0,
                'age_60_100_pct'  => $pop > 0 ? round($age60 / $pop * 100, 1) : 0,
                'beneficiaries_pct' => $pop > 0 ? round($totalBenef / $pop * 100, 1) : 0,
                'households_pct'  => $pop > 0 ? round($hh / $pop * 100, 1) : 0,
            ];
        }

        // â”€â”€ Auto-generated key insights â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        // Use array_map (not array_column) to preserve municipality name keys
        $pops = array_map(fn($d) => $d['total'],         $demographicData);
        $bens = array_map(fn($d) => $d['beneficiaries'], $demographicData);

        // Highest population
        arsort($pops);  $highPop = key($pops);
        // Fastest growing (biggest absolute increase last 2 years)
        $growthMap = [];
        foreach ($coreNames as $n) {
            $yrs = array_keys($populationTrend[$n]);
            if (count($yrs) >= 2) {
                $last  = $populationTrend[$n][end($yrs)];
                $prev  = $populationTrend[$n][$yrs[count($yrs)-2]];
                $growthMap[$n] = $last - $prev;
            } else { $growthMap[$n] = 0; }
        }
        arsort($growthMap); $fastestGrowing = key($growthMap);
        // Highest beneficiaries
        arsort($bens); $highBen = key($bens);
        // Dominant age group (across all 3)
        $totAge0  = array_sum(array_map(fn($d) => $d['age_0_19'],   $demographicData));
        $totAge20 = array_sum(array_map(fn($d) => $d['age_20_59'],  $demographicData));
        $totAge60 = array_sum(array_map(fn($d) => $d['age_60_100'], $demographicData));
        $domAgeGroup = $totAge0 >= $totAge20 && $totAge0 >= $totAge60
            ? 'Youth (0â€“19)'
            : ($totAge20 >= $totAge0 && $totAge20 >= $totAge60 ? 'Working Age (20â€“59)' : 'Senior (60+)');
        // Gender imbalance
        $totalMale   = array_sum(array_map(fn($d) => $d['male'],   $demographicData));
        $totalFemale = array_sum(array_map(fn($d) => $d['female'], $demographicData));
        $genderNote  = ($totalMale + $totalFemale) > 0
            ? ($totalMale > $totalFemale ? 'Male-dominant' : ($totalFemale > $totalMale ? 'Female-dominant' : 'Balanced'))
            : 'No gender data';

        $insights = [
            "$highPop has the largest population at " . number_format($demographicData[$highPop]['total']) . " in {$selectedYear}, making it the most populous municipality in this dataset.",
            "$fastestGrowing shows the highest population growth between available years — indicating strong community expansion and increasing demand for social services.",
            "$highBen leads in registered beneficiaries with " . number_format($demographicData[$highBen]['beneficiaries']) . " — representing " . $demographicData[$highBen]['beneficiaries_pct'] . "% of its total population.",
            "The $domAgeGroup age bracket is the dominant age segment across all municipalities, which should guide targeted program prioritization.",
            "Overall gender distribution is $genderNote. Male: " . number_format($totalMale) . ", Female: " . number_format($totalFemale) . " — a gap of " . number_format(abs($totalMale - $totalFemale)) . " persons.",
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
     * Demographic data  → municipality_yearly_summary  (same source as /superadmin/data/municipalities)
     * Program data      → social_welfare_programs       (same source as /superadmin/data/programs)
     */
    public function programs(Request $request)
    {
        $coreNames = Municipality::orderBy('name')->pluck('name')->toArray();
        $palette   = ['#2C3E8F','#FDB913','#6366f1','#16a34a','#9333ea','#0891b2','#ea580c','#db2777','#65a30d','#d97706'];
        $colors    = [];
        foreach ($coreNames as $i => $n) { $colors[$n] = $palette[$i % count($palette)]; }

        // ── Demographic data: municipality_yearly_summary ────────────────────
        $allSummaries = MunicipalityYearlySummary::whereIn('municipality', $coreNames)
            ->orderBy('year')->get();

        $summariesByMuni = [];
        foreach ($coreNames as $name) {
            $summariesByMuni[$name] = $allSummaries->where('municipality', $name)->keyBy('year');
        }
        $getDemog = fn($name, $yr, $field) => (int) ($summariesByMuni[$name][$yr]?->$field ?? 0);

        // ── Program data: social_welfare_programs ────────────────────────────
        $allPrograms = SocialWelfareProgram::whereIn('municipality', $coreNames)->get();
        $programLookup = []; // [municipality][year][program_type] = beneficiary_count
        foreach ($allPrograms as $p) {
            $programLookup[$p->municipality][$p->year][$p->program_type]
                = (int) $p->beneficiary_count;
        }
        $getProg = fn($name, $yr, $type) => $programLookup[$name][$yr][$type] ?? 0;

        // ── All unique years: union of both sources ──────────────────────────
        $summaryYears = $allSummaries->pluck('year')->unique()->sort()->values()->toArray();
        $programYears = $allPrograms->pluck('year')->unique()->sort()->values()->toArray();
        $allYears = collect(array_merge($summaryYears, $programYears))
            ->unique()->sort()->values()->toArray();
        if (empty($allYears)) $allYears = [(int) date('Y')];

        $latestYear   = end($allYears);
        $selectedYear = (int) $request->input('year', $latestYear);

        // ── Section 1: Snapshot for selected year ────────────────────────────
        $snapshot = [];
        foreach ($coreNames as $name) {
            // Demographics from municipality_yearly_summary
            $pop    = $getDemog($name, $selectedYear, 'total_population');
            $hh     = $getDemog($name, $selectedYear, 'total_households');
            $male   = $getDemog($name, $selectedYear, 'male_population');
            $female = $getDemog($name, $selectedYear, 'female_population');
            $a0     = $getDemog($name, $selectedYear, 'population_0_19');
            $a20    = $getDemog($name, $selectedYear, 'population_20_59');
            $a60    = $getDemog($name, $selectedYear, 'population_60_100');
            // Program counts from social_welfare_programs
            $pwd    = $getProg($name, $selectedYear, 'PWD_Assistance');
            $aics   = $getProg($name, $selectedYear, 'AICS');
            $solo   = $getProg($name, $selectedYear, 'Solo_Parent');
            $fps    = $getProg($name, $selectedYear, '4Ps');
            $sen    = $getProg($name, $selectedYear, 'Senior_Citizen_Pension');
            $benef  = $pwd + $aics + $solo + $fps + $sen;

            $snapshot[$name] = [
                'population'       => $pop,
                'households'       => $hh,
                'beneficiaries'    => $benef,
                'pwd'              => $pwd,
                'aics'             => $aics,
                'solo_parent'      => $solo,
                'four_ps'          => $fps,
                'senior'           => $sen,
                'male'             => $male,
                'female'           => $female,
                'age_0_19'         => $a0,
                'age_20_59'        => $a20,
                'age_60_100'       => $a60,
                'avg_hh_size'      => ($hh > 0 && $pop > 0) ? round($pop / $hh, 2) : 0,
                'dependency_ratio' => $a20 > 0 ? round(($a0 + $a60) / $a20 * 100, 1) : 0,
                'benef_pct'        => $pop > 0 ? round($benef / $pop * 100, 1) : 0,
            ];
        }

        // ── Trend arrays ─────────────────────────────────────────────────────
        $populationTrend = []; $maleTrend = []; $femaleTrend = [];
        $householdsTrend = []; $benefTrend  = [];
        $pwdTrend = []; $aicsTrend = []; $soloTrend = []; $fpsTrend = []; $seniorTrend = [];
        $growthRates = [];

        foreach ($coreNames as $name) {
            $prevPop = null;
            foreach ($allYears as $yr) {
                // Demographics from municipality_yearly_summary
                $pop    = $getDemog($name, $yr, 'total_population');
                $hh     = $getDemog($name, $yr, 'total_households');
                $male   = $getDemog($name, $yr, 'male_population');
                $female = $getDemog($name, $yr, 'female_population');
                // Programs from social_welfare_programs
                $pwd    = $getProg($name, $yr, 'PWD_Assistance');
                $aics   = $getProg($name, $yr, 'AICS');
                $solo   = $getProg($name, $yr, 'Solo_Parent');
                $fps    = $getProg($name, $yr, '4Ps');
                $sen    = $getProg($name, $yr, 'Senior_Citizen_Pension');

                $populationTrend[$name][$yr] = $pop;
                $householdsTrend[$name][$yr] = $hh;
                $maleTrend[$name][$yr]       = $male;
                $femaleTrend[$name][$yr]     = $female;
                $benefTrend[$name][$yr]      = $pwd + $aics + $solo + $fps + $sen;
                $pwdTrend[$name][$yr]        = $pwd;
                $aicsTrend[$name][$yr]       = $aics;
                $soloTrend[$name][$yr]       = $solo;
                $fpsTrend[$name][$yr]        = $fps;
                $seniorTrend[$name][$yr]     = $sen;

                if ($prevPop !== null && $prevPop > 0) {
                    $growthRates[$name][$yr] = round(($pop - $prevPop) / $prevPop * 100, 2);
                } else {
                    $growthRates[$name][$yr] = null;
                }
                $prevPop = $pop;
            }
        }

        // ── Section 7: ANOVA ─────────────────────────────────────────────────
        $anovaPopGroups   = array_map(fn($n) => array_values($populationTrend[$n]), $coreNames);
        $anovaBenefGroups = array_map(fn($n) => array_values($benefTrend[$n]),      $coreNames);
        $anovaPopResult   = $this->oneWayAnova($anovaPopGroups);
        $anovaBenefResult = $this->oneWayAnova($anovaBenefGroups);

        if ($anovaPopResult) {
            $anovaPopResult['means']  = array_combine($coreNames, $anovaPopResult['groupMeans']);
        }
        if ($anovaBenefResult) {
            $anovaBenefResult['means'] = array_combine($coreNames, $anovaBenefResult['groupMeans']);
        }

        // ── Section 8: Correlation ───────────────────────────────────────────
        $allPop   = []; $allBenef = [];
        $allAge60 = []; $allSen   = [];
        $allHH    = []; $allAics  = [];
        foreach ($coreNames as $name) {
            foreach ($allYears as $yr) {
                $allPop[]   = $populationTrend[$name][$yr];
                $allBenef[] = $benefTrend[$name][$yr];
                $allAge60[] = $getDemog($name, $yr, 'population_60_100');
                $allSen[]   = $seniorTrend[$name][$yr];   // from social_welfare_programs
                $allHH[]    = $householdsTrend[$name][$yr];
                $allAics[]  = $aicsTrend[$name][$yr];     // from social_welfare_programs
            }
        }
        $corrPopBenef    = $this->pearsonCorr($allPop,   $allBenef);
        $corrAge60Senior = $this->pearsonCorr($allAge60, $allSen);
        $corrHhAics      = $this->pearsonCorr($allHH,    $allAics);

        $corrLabel = fn($r) => $r === null ? 'N/A'
            : (abs($r) >= 0.7 ? 'Strong' : (abs($r) >= 0.4 ? 'Moderate' : 'Weak'))
              . ' ' . ($r >= 0 ? 'Positive' : 'Negative');

        $correlations = [
            [
                'label'    => 'Population vs Total Beneficiaries',
                'r'        => $corrPopBenef,
                'strength' => $corrLabel($corrPopBenef),
                'xData'    => $allPop,
                'yData'    => $allBenef,
                'xLabel'   => 'Population',
                'yLabel'   => 'Beneficiaries',
            ],
            [
                'label'    => 'Age 60+ vs Senior Assistance',
                'r'        => $corrAge60Senior,
                'strength' => $corrLabel($corrAge60Senior),
                'xData'    => $allAge60,
                'yData'    => $allSen,
                'xLabel'   => 'Age 60+ Population',
                'yLabel'   => 'Senior Beneficiaries',
            ],
            [
                'label'    => 'Households vs AICS',
                'r'        => $corrHhAics,
                'strength' => $corrLabel($corrHhAics),
                'xData'    => $allHH,
                'yData'    => $allAics,
                'xLabel'   => 'Households',
                'yLabel'   => 'AICS Beneficiaries',
            ],
        ];

        // ── Section 9: Key Insights ──────────────────────────────────────────
        $popMap   = array_map(fn($n) => $snapshot[$n]['population'],    $coreNames);
        $benefMap = array_map(fn($n) => $snapshot[$n]['beneficiaries'], $coreNames);
        arsort($popMap);   $highestPop   = $coreNames[key($popMap)];
        asort($popMap);    $lowestPop    = $coreNames[key($popMap)];
        arsort($benefMap); $highestBenef = $coreNames[key($benefMap)];

        $avgGrowth = [];
        foreach ($coreNames as $i => $name) {
            $rates = array_filter($growthRates[$name], fn($v) => $v !== null);
            $avgGrowth[$i] = count($rates) > 0 ? array_sum($rates) / count($rates) : 0;
        }
        arsort($avgGrowth); $fastestIdx = key($avgGrowth); $fastest = $coreNames[$fastestIdx];

        $totAge0  = array_sum(array_map(fn($n) => $snapshot[$n]['age_0_19'],   $coreNames));
        $totAge20 = array_sum(array_map(fn($n) => $snapshot[$n]['age_20_59'],  $coreNames));
        $totAge60 = array_sum(array_map(fn($n) => $snapshot[$n]['age_60_100'], $coreNames));
        $domAge   = $totAge0 >= $totAge20 && $totAge0 >= $totAge60 ? 'Youth (0–19)'
                  : ($totAge20 >= $totAge60 ? 'Working Age (20–59)' : 'Senior (60+)');

        $totalMale   = array_sum(array_map(fn($n) => $snapshot[$n]['male'],   $coreNames));
        $totalFemale = array_sum(array_map(fn($n) => $snapshot[$n]['female'], $coreNames));
        $genderGap   = abs($totalMale - $totalFemale);

        $progTotals = ['PWD' => 0, 'AICS' => 0, 'Solo Parent' => 0, '4Ps' => 0, 'Senior' => 0];
        foreach ($coreNames as $n) {
            $progTotals['PWD']         += $snapshot[$n]['pwd'];
            $progTotals['AICS']        += $snapshot[$n]['aics'];
            $progTotals['Solo Parent'] += $snapshot[$n]['solo_parent'];
            $progTotals['4Ps']         += $snapshot[$n]['four_ps'];
            $progTotals['Senior']      += $snapshot[$n]['senior'];
        }
        arsort($progTotals); $topProgram = key($progTotals);

        $insights = [
            "$highestPop has the highest population (" . number_format($snapshot[$highestPop]['population']) . ") while $lowestPop has the lowest.",
            "$fastest shows the highest average population growth rate among the municipalities.",
            "$highestBenef has the most registered beneficiaries (" . number_format($snapshot[$highestBenef]['beneficiaries']) . ") — " . $snapshot[$highestBenef]['benef_pct'] . "% of its population.",
            "The dominant age group across all municipalities is $domAge — indicating a " . ($domAge === 'Youth (0–19)' ? 'young, growing' : ($domAge === 'Working Age (20–59)' ? 'productive' : 'aging')) . " population.",
            $genderGap > 0 ? "A gender gap of " . number_format($genderGap) . " exists: " . ($totalMale > $totalFemale ? "Male-dominant ($totalMale M vs $totalFemale F)." : "Female-dominant ($totalFemale F vs $totalMale M).") : "Gender distribution is balanced.",
            "The $topProgram program has the highest total beneficiaries (" . number_format($progTotals[$topProgram]) . ") across all municipalities.",
            "Dependency ratios: " . implode(', ', array_map(fn($n) => "$n: {$snapshot[$n]['dependency_ratio']}%", $coreNames)) . " — higher ratio means more dependents per working-age person.",
        ];

        // ── Section 10: Recommendations ─────────────────────────────────────
        $recommendations = [
            ['label' => 'Priority Support',   'text' => "$lowestPop has the smallest population base; ensure equitable distribution of welfare resources and avoid underserving this municipality."],
            ['label' => 'Program Expansion',  'text' => "Expand the $topProgram program — it has the highest demand. Consider increasing budget allocation and outreach in all municipalities."],
            ['label' => 'Age Intervention',   'text' => $totAge60 > $totAge0 ? "The senior population is growing — prioritize health care, pension programs, and elder care services." : "Youth programs (education, livelihood) should be reinforced to empower the dominant 0–19 age bracket."],
            ['label' => 'Gender Programs',    'text' => $totalFemale > $totalMale ? "Female beneficiaries outpace males — strengthen Solo Parent and women-focused livelihood programs." : "Consider targeted programs for male residents who may be underrepresented in welfare enrollment."],
            ['label' => 'Fastest Grower',     'text' => "$fastest is growing fastest — proactively scale up social welfare infrastructure and staffing to meet rising demand."],
            ['label' => 'AICS & Households',  'text' => "High AICS uptake correlates with household density. Increase crisis assistance (AICS) funding proportionally with household growth."],
        ];

        // ── Vision / Mission / Goals per municipality ────────────────────────
        $visionRows = MunicipalityVision::whereIn('municipality_name', $coreNames)->get()->keyBy('municipality_name');
        $visionData = [];
        foreach ($coreNames as $n) {
            $row = $visionRows[$n] ?? null;
            $visionData[$n] = [
                'vision'          => $row?->vision ?? '',
                'mission'         => $row?->mission ?? '',
                'goals'           => $row?->goals ?? '',
                'strategic_goals' => $row?->strategic_goals ?? [],
            ];
        }

        return view('analysis.index', compact(
            'coreNames', 'colors', 'allYears', 'selectedYear',
            'snapshot', 'populationTrend', 'maleTrend', 'femaleTrend',
            'householdsTrend', 'benefTrend', 'growthRates',
            'pwdTrend', 'aicsTrend', 'soloTrend', 'fpsTrend', 'seniorTrend',
            'anovaPopResult', 'anovaBenefResult',
            'correlations', 'corrPopBenef', 'corrAge60Senior', 'corrHhAics',
            'insights', 'recommendations',
            'highestPop', 'lowestPop', 'highestBenef', 'fastest',
            'domAge', 'topProgram', 'progTotals',
            'visionData'
        ));
    }


    // â”€â”€ Statistical Helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    private function oneWayAnova(array $groups): ?array
    {
        $groups     = array_values($groups);
        $k          = count($groups);
        $allValues  = array_merge(...$groups);
        $n_total    = count($allValues);

        if ($n_total < $k + 1 || $k < 2) return null;

        $grandMean   = array_sum($allValues) / $n_total;
        $ssBetween   = 0;
        $groupMeans  = [];

        foreach ($groups as $group) {
            $n = count($group);
            if ($n === 0) return null;
            $mean          = array_sum($group) / $n;
            $groupMeans[]  = round($mean, 2);
            $ssBetween    += $n * (($mean - $grandMean) ** 2);
        }

        $ssWithin = 0;
        foreach ($groups as $i => $group) {
            foreach ($group as $val) {
                $ssWithin += ($val - $groupMeans[$i]) ** 2;
            }
        }

        $dfBetween = $k - 1;
        $dfWithin  = $n_total - $k;

        if ($dfWithin <= 0 || $ssWithin == 0) {
            return ['F' => 0, 'significant' => false, 'dfBetween' => $dfBetween, 'dfWithin' => $dfWithin, 'groupMeans' => $groupMeans];
        }

        $F = round(($ssBetween / $dfBetween) / ($ssWithin / $dfWithin), 4);

        // Critical F (alpha=0.05, df1=2) by df2 lookup
        $criticalF = $dfWithin >= 20 ? 3.49 : ($dfWithin >= 10 ? 4.10 : ($dfWithin >= 6 ? 5.14 : ($dfWithin >= 3 ? 9.55 : 19.0)));

        return [
            'F'           => $F,
            'significant' => $F > $criticalF,
            'dfBetween'   => $dfBetween,
            'dfWithin'    => $dfWithin,
            'groupMeans'  => $groupMeans,
        ];
    }

    private function pearsonCorr(array $x, array $y): ?float
    {
        $n = count($x);
        if ($n < 2 || count($y) !== $n) return null;

        $sumX  = array_sum($x);
        $sumY  = array_sum($y);
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

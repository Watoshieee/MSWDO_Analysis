<?php

namespace App\Services\Export;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\MunicipalityYearlySummary;
use App\Models\SocialWelfareProgram;
use Illuminate\Support\Collection;

class DssWsmService
{
    /**
     * Get configurable WSM criteria weights.
     */
    public function getCriteria(): array
    {
        return config('dss.wsm_weights', [
            'pwd' => ['key' => 'pwd', 'label' => 'PWD', 'weight' => 0.15],
            'aics' => ['key' => 'aics', 'label' => 'AICS', 'weight' => 0.20],
            'solo_parent' => ['key' => 'solo_parent', 'label' => 'Solo Parent', 'weight' => 0.15],
            'four_ps' => ['key' => 'four_ps', 'label' => '4Ps', 'weight' => 0.15],
            'senior' => ['key' => 'senior', 'label' => 'Senior Citizen', 'weight' => 0.15],
            'population_concentration' => ['key' => 'population_concentration', 'label' => 'Barangay Population Concentration', 'weight' => 0.10],
            'beneficiary_concentration' => ['key' => 'beneficiary_concentration', 'label' => 'Overall Beneficiary Concentration', 'weight' => 0.10],
        ]);
    }

    /**
     * Compute full WSM Analysis for a municipality and specific year (or all years).
     * Returns structured barangay details, normalization factors, weighted scores, and final WSM scores.
     */
    public function computeWsm(string $municipality, ?int $year = null): array
    {
        $query = Barangay::where('municipality', $municipality);
        if ($year !== null) {
            $query->where('year', $year);
        }
        $barangays = $query->orderBy('name')->get();

        if ($barangays->isEmpty()) {
            return [
                'municipality' => $municipality,
                'year' => $year,
                'barangays' => [],
                'criteria' => $this->getCriteria(),
                'max_values' => [],
                'has_data' => false,
            ];
        }

        $totalMuniPopulation = (float) $barangays->sum('total_population');
        $totalMuniBeneficiaries = (float) $barangays->sum(function ($b) {
            return ($b->pwd_count ?? 0) + ($b->aics_count ?? 0) + ($b->single_parent_count ?? 0)
                + ($b->four_ps_count ?? 0) + ($b->senior_count ?? 0);
        });

        // 1. Extract raw values per barangay
        $rawRecords = [];
        foreach ($barangays as $b) {
            $pop = (float) ($b->total_population ?? 0);
            $pwd = (float) ($b->pwd_count ?? 0);
            $aics = (float) ($b->aics_count ?? 0);
            $solo = (float) ($b->single_parent_count ?? 0);
            $fourPs = (float) ($b->four_ps_count ?? 0);
            $senior = (float) ($b->senior_count ?? 0);
            $households = (float) ($b->total_households ?? 0);
            $totalBen = $pwd + $aics + $solo + $fourPs + $senior;

            $popConc = ($totalMuniPopulation > 0) ? ($pop / $totalMuniPopulation) * 100 : 0.0;
            $benConc = ($totalMuniBeneficiaries > 0) ? ($totalBen / $totalMuniBeneficiaries) * 100 : 0.0;

            $rawRecords[] = [
                'id' => $b->id,
                'name' => $b->name,
                'year' => $b->year,
                'total_population' => $pop,
                'total_households' => $households,
                'total_beneficiaries' => $totalBen,
                'pwd_raw' => $pwd,
                'aics_raw' => $aics,
                'solo_parent_raw' => $solo,
                'four_ps_raw' => $fourPs,
                'senior_raw' => $senior,
                'population_concentration_raw' => $popConc,
                'beneficiary_concentration_raw' => $benConc,
            ];
        }

        // 2. Find Max values for linear normalization (Max Normalization)
        $maxValues = [
            'pwd' => max(array_column($rawRecords, 'pwd_raw')) ?: 1.0,
            'aics' => max(array_column($rawRecords, 'aics_raw')) ?: 1.0,
            'solo_parent' => max(array_column($rawRecords, 'solo_parent_raw')) ?: 1.0,
            'four_ps' => max(array_column($rawRecords, 'four_ps_raw')) ?: 1.0,
            'senior' => max(array_column($rawRecords, 'senior_raw')) ?: 1.0,
            'population_concentration' => max(array_column($rawRecords, 'population_concentration_raw')) ?: 1.0,
            'beneficiary_concentration' => max(array_column($rawRecords, 'beneficiary_concentration_raw')) ?: 1.0,
        ];

        $criteria = $this->getCriteria();

        // 3. Calculate Normalized Scores, Weighted Scores, and Final WSM Score
        $computedBarangays = [];
        foreach ($rawRecords as $rec) {
            $criteriaScores = [];
            $finalWsmScore = 0.0;

            foreach ($criteria as $key => $crit) {
                $rawVal = $rec[$key . '_raw'] ?? 0.0;
                $maxVal = $maxValues[$key] ?? 1.0;
                $weight = (float) $crit['weight'];

                $normScore = ($maxVal > 0) ? round($rawVal / $maxVal, 4) : 0.0;
                $weightedScore = round($normScore * $weight, 4);
                $finalWsmScore += $weightedScore;

                $criteriaScores[$key] = [
                    'label' => $crit['label'],
                    'raw' => $rawVal,
                    'normalized' => $normScore,
                    'weight' => $weight,
                    'weighted_score' => $weightedScore,
                ];
            }

            $finalWsmScore = round($finalWsmScore, 4);

            $computedBarangays[] = [
                'barangay' => $rec['name'],
                'year' => $rec['year'],
                'total_population' => $rec['total_population'],
                'total_households' => $rec['total_households'],
                'total_beneficiaries' => $rec['total_beneficiaries'],
                'criteria_scores' => $criteriaScores,
                'final_wsm_score' => $finalWsmScore,
                'priority_level' => $this->classifyPriority($finalWsmScore),
            ];
        }

        // Sort descending by final WSM score
        usort($computedBarangays, fn($a, $b) => $b['final_wsm_score'] <=> $a['final_wsm_score']);

        return [
            'municipality' => $municipality,
            'year' => $year,
            'total_population' => $totalMuniPopulation,
            'total_beneficiaries' => $totalMuniBeneficiaries,
            'barangays' => $computedBarangays,
            'criteria' => $criteria,
            'max_values' => $maxValues,
            'has_data' => true,
        ];
    }

    /**
     * Classify priority using neutral analytical terminology.
     */
    protected function classifyPriority(float $score): string
    {
        if ($score >= 0.65) {
            return config('dss.terminology.high_priority', 'Higher calculated priority score');
        } elseif ($score >= 0.35) {
            return config('dss.terminology.moderate_priority', 'Moderate calculated priority score');
        } else {
            return config('dss.terminology.low_priority', 'Lower calculated priority score');
        }
    }

    /**
     * Compute DSS indicators (prevalence rates, household size ratio) for a municipality/year.
     */
    public function computeDssIndicators(string $municipality, ?int $year = null): array
    {
        $yearlyQuery = MunicipalityYearlySummary::where('municipality', $municipality);
        if ($year !== null) {
            $yearlyQuery->where('year', $year);
        }
        $yearlySummaries = $yearlyQuery->orderBy('year', 'desc')->get();

        $barangayQuery = Barangay::where('municipality', $municipality);
        if ($year !== null) {
            $barangayQuery->where('year', $year);
        }
        $barangays = $barangayQuery->get();

        $programsQuery = SocialWelfareProgram::where('municipality', $municipality);
        if ($year !== null) {
            $programsQuery->where('year', $year);
        }
        $programs = $programsQuery->get();

        $summary = $yearlySummaries->first();

        $population = (float) ($summary->total_population ?? $barangays->sum('total_population') ?: 0);
        $households = (float) ($summary->total_households ?? $barangays->sum('total_households') ?: 0);

        $pwd = (float) ($summary->total_pwd ?? $barangays->sum('pwd_count') ?: 0);
        $aics = (float) ($summary->total_aics ?? $barangays->sum('aics_count') ?: 0);
        $soloParent = (float) ($summary->total_solo_parent ?? $barangays->sum('single_parent_count') ?: 0);
        $fourPs = (float) ($summary->total_4ps ?? $barangays->sum('four_ps_count') ?: 0);
        $senior = (float) ($summary->total_senior ?? $barangays->sum('senior_count') ?: 0);

        // Fallback to program table if summary & barangay are 0
        if ($pwd == 0) $pwd = (float) $programs->where('program_type', 'PWD_Assistance')->sum('beneficiary_count');
        if ($aics == 0) $aics = (float) $programs->whereIn('program_type', ['AICS', 'AICS_Medical', 'AICS_Burial', 'AICS_Educational'])->sum('beneficiary_count');
        if ($soloParent == 0) $soloParent = (float) $programs->where('program_type', 'Solo_Parent')->sum('beneficiary_count');
        if ($fourPs == 0) $fourPs = (float) $programs->where('program_type', '4Ps')->sum('beneficiary_count');
        if ($senior == 0) $senior = (float) $programs->where('program_type', 'Senior_Citizen_Pension')->sum('beneficiary_count');

        $totalBeneficiaries = $pwd + $aics + $soloParent + $fourPs + $senior;

        $pwdRate = ($population > 0) ? round(($pwd / $population) * 100, 2) : null;
        $aicsRate = ($population > 0) ? round(($aics / $population) * 100, 2) : null;
        $soloParentRate = ($population > 0) ? round(($soloParent / $population) * 100, 2) : null;
        $fourPsRate = ($population > 0) ? round(($fourPs / $population) * 100, 2) : null;
        $seniorRate = ($population > 0) ? round(($senior / $population) * 100, 2) : null;
        $overallBeneficiaryRate = ($population > 0) ? round(($totalBeneficiaries / $population) * 100, 2) : null;
        $householdSizeRatio = ($households > 0) ? round($population / $households, 2) : null;

        return [
            'municipality' => $municipality,
            'year' => $year ?? ($summary->year ?? date('Y')),
            'population' => $population,
            'households' => $households,
            'total_beneficiaries' => $totalBeneficiaries,
            'pwd' => $pwd,
            'pwd_rate' => $pwdRate,
            'aics' => $aics,
            'aics_rate' => $aicsRate,
            'solo_parent' => $soloParent,
            'solo_parent_rate' => $soloParentRate,
            'four_ps' => $fourPs,
            'four_ps_rate' => $fourPsRate,
            'senior' => $senior,
            'senior_rate' => $seniorRate,
            'overall_beneficiary_rate' => $overallBeneficiaryRate,
            'household_size_ratio' => $householdSizeRatio,
        ];
    }

    /**
     * Compute Year-over-Year (YoY) demographic and program trends.
     */
    public function computeYearOverYear(string $municipality): array
    {
        $summaries = MunicipalityYearlySummary::where('municipality', $municipality)
            ->orderBy('year', 'asc')
            ->get();

        if ($summaries->isEmpty()) {
            return [];
        }

        $yoyResults = [];
        $prev = null;

        foreach ($summaries as $curr) {
            $pop = (int) ($curr->total_population ?? 0);
            $households = (int) ($curr->total_households ?? 0);
            $male = (int) ($curr->male_population ?? 0);
            $female = (int) ($curr->female_population ?? 0);
            $age0_19 = (int) ($curr->population_0_19 ?? 0);
            $age20_59 = (int) ($curr->population_20_59 ?? 0);
            $age60Plus = (int) ($curr->population_60_100 ?? 0);
            $pwd = (int) ($curr->total_pwd ?? 0);
            $aics = (int) ($curr->total_aics ?? 0);
            $solo = (int) ($curr->total_solo_parent ?? 0);
            $fourPs = (int) ($curr->total_4ps ?? 0);
            $senior = (int) ($curr->total_senior ?? 0);
            $totalBen = $pwd + $aics + $solo + $fourPs + $senior;

            if ($prev === null) {
                $yoyResults[] = [
                    'year' => $curr->year,
                    'population' => $pop,
                    'population_change' => 'N/A',
                    'population_growth_rate' => 'N/A',
                    'households' => $households,
                    'households_change' => 'N/A',
                    'households_growth_rate' => 'N/A',
                    'male' => $male,
                    'female' => $female,
                    'age_0_19' => $age0_19,
                    'age_20_59' => $age20_59,
                    'age_60_plus' => $age60Plus,
                    'pwd' => $pwd,
                    'aics' => $aics,
                    'solo_parent' => $solo,
                    'four_ps' => $fourPs,
                    'senior' => $senior,
                    'beneficiaries' => $totalBen,
                    'beneficiaries_change' => 'N/A',
                    'beneficiaries_growth_rate' => 'N/A',
                ];
            } else {
                $prevPop = (int) ($prev->total_population ?? 0);
                $prevHouse = (int) ($prev->total_households ?? 0);
                $prevBen = (int) (($prev->total_pwd ?? 0) + ($prev->total_aics ?? 0) + ($prev->total_solo_parent ?? 0) + ($prev->total_4ps ?? 0) + ($prev->total_senior ?? 0));

                $popChange = $pop - $prevPop;
                $popGrowth = ($prevPop > 0) ? round(($popChange / $prevPop) * 100, 2) : 'N/A';

                $houseChange = $households - $prevHouse;
                $houseGrowth = ($prevHouse > 0) ? round(($houseChange / $prevHouse) * 100, 2) : 'N/A';

                $benChange = $totalBen - $prevBen;
                $benGrowth = ($prevBen > 0) ? round(($benChange / $prevBen) * 100, 2) : 'N/A';

                $yoyResults[] = [
                    'year' => $curr->year,
                    'population' => $pop,
                    'population_change' => $popChange,
                    'population_growth_rate' => ($popGrowth !== 'N/A') ? $popGrowth . '%' : 'N/A',
                    'households' => $households,
                    'households_change' => $houseChange,
                    'households_growth_rate' => ($houseGrowth !== 'N/A') ? $houseGrowth . '%' : 'N/A',
                    'male' => $male,
                    'female' => $female,
                    'age_0_19' => $age0_19,
                    'age_20_59' => $age20_59,
                    'age_60_plus' => $age60Plus,
                    'pwd' => $pwd,
                    'aics' => $aics,
                    'solo_parent' => $solo,
                    'four_ps' => $fourPs,
                    'senior' => $senior,
                    'beneficiaries' => $totalBen,
                    'beneficiaries_change' => $benChange,
                    'beneficiaries_growth_rate' => ($benGrowth !== 'N/A') ? $benGrowth . '%' : 'N/A',
                ];
            }

            $prev = $curr;
        }

        return $yoyResults;
    }

    /**
     * Compute Comparative Analysis across multiple municipalities (e.g. Magdalena, Liliw, Majayjay).
     */
    public function computeComparative(array $municipalities = ['Magdalena', 'Liliw', 'Majayjay'], ?int $year = null): array
    {
        $comparison = [];
        foreach ($municipalities as $muni) {
            $dss = $this->computeDssIndicators($muni, $year);
            $wsm = $this->computeWsm($muni, $year);

            $comparison[] = [
                'municipality' => $muni,
                'year' => $year ?? $dss['year'],
                'population' => $dss['population'],
                'households' => $dss['households'],
                'total_beneficiaries' => $dss['total_beneficiaries'],
                'pwd' => $dss['pwd'],
                'pwd_rate' => $dss['pwd_rate'],
                'aics' => $dss['aics'],
                'aics_rate' => $dss['aics_rate'],
                'solo_parent' => $dss['solo_parent'],
                'solo_parent_rate' => $dss['solo_parent_rate'],
                'four_ps' => $dss['four_ps'],
                'four_ps_rate' => $dss['four_ps_rate'],
                'senior' => $dss['senior'],
                'senior_rate' => $dss['senior_rate'],
                'household_size_ratio' => $dss['household_size_ratio'],
                'average_wsm_score' => !empty($wsm['barangays']) ? round(collect($wsm['barangays'])->avg('final_wsm_score'), 4) : 0.0,
            ];
        }

        return $comparison;
    }
}

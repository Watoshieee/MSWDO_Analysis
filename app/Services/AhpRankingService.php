<?php

namespace App\Services;

use App\Models\SocialWelfareProgram;
use Illuminate\Support\Collection;

/**
 * AHP-weighted comparative ranking from approved criteria weights
 * and annual municipality-level beneficiary counts.
 *
 * Zero, null, and missing beneficiary_count values are treated as N/A
 * (not-yet-entered data), never as verified zero beneficiaries.
 */
class AhpRankingService
{
    public const MUNICIPALITIES = ['Liliw', 'Magdalena', 'Majayjay'];

    /** Approved AHP group weights (sum to 0.9999 before normalization). */
    public const RAW_WEIGHTS = [
        'Senior_Citizen_Pension' => 0.3737,
        'PWD_Assistance' => 0.3271,
        'Solo_Parent' => 0.2991,
    ];

    public const CRITERION_LABELS = [
        'Senior_Citizen_Pension' => 'Senior Citizen Pension',
        'PWD_Assistance' => 'PWD Assistance',
        'Solo_Parent' => 'Solo Parent',
    ];

    /** Hidden from public program analysis tables, charts, totals, and insights. */
    public const EXCLUDED_PROGRAM_TYPES = ['ECCD', 'Kababaihan'];

    /**
     * When all three municipalities have the same valid value for a criterion,
     * each receives this normalized value so none gains an artificial advantage.
     */
    public const EQUAL_CRITERION_NORMALIZED_VALUE = 1.0;

    public function municipalities(): array
    {
        return self::MUNICIPALITIES;
    }

    public function criteria(): array
    {
        return array_keys(self::RAW_WEIGHTS);
    }

    public function effectiveWeights(): array
    {
        $total = array_sum(self::RAW_WEIGHTS);
        $weights = [];
        foreach (self::RAW_WEIGHTS as $type => $weight) {
            $weights[$type] = $total > 0 ? $weight / $total : 0.0;
        }

        return $weights;
    }

    public function criterionLabel(string $type): string
    {
        return self::CRITERION_LABELS[$type] ?? trim(str_replace('_', ' ', $type));
    }

    /**
     * Years that have any non-deleted program record for the three municipalities.
     * Newly inserted years appear automatically.
     */
    public function availableProgramYears(): array
    {
        return SocialWelfareProgram::query()
            ->whereIn('municipality', self::MUNICIPALITIES)
            ->whereNotIn('program_type', self::EXCLUDED_PROGRAM_TYPES)
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->values()
            ->all();
    }

    /**
     * Annual (month IS NULL) non-deleted rows for the three municipalities.
     */
    public function loadAnnualRows(): Collection
    {
        return SocialWelfareProgram::query()
            ->whereIn('municipality', self::MUNICIPALITIES)
            ->whereNotIn('program_type', self::EXCLUDED_PROGRAM_TYPES)
            ->whereNull('month')
            ->orderBy('id')
            ->get();
    }

    /**
     * Build municipality/year/program lookup from annual rows.
     *
     * Duplicate rule: among active annual rows for the same municipality,
     * year, and program_type, keep the row with the highest id. Other
     * duplicates are reported and never summed.
     *
     * A stored count of 0 or null is stored as null (N/A).
     *
     * @return array{lookup: array, duplicates: array, types: array}
     */
    public function buildAnnualLookup(Collection $annualRows): array
    {
        $grouped = [];
        foreach ($annualRows as $row) {
            $muni = (string) ($row->getRawOriginal('municipality') ?? $row->municipality);
            if (!in_array($muni, self::MUNICIPALITIES, true)) {
                continue;
            }
            $year = (int) $row->year;
            $type = (string) $row->program_type;
            if ($this->isExcludedProgramType($type)) {
                continue;
            }
            $grouped[$muni][$year][$type][] = $row;
        }

        $lookup = [];
        $duplicates = [];
        $types = [];

        foreach ($grouped as $muni => $byYear) {
            foreach ($byYear as $year => $byType) {
                foreach ($byType as $type => $rows) {
                    $types[$type] = true;
                    usort($rows, function ($a, $b) {
                        return ((int) $b->id) <=> ((int) $a->id);
                    });
                    $selected = $rows[0];
                    if (count($rows) > 1) {
                        $duplicates[] = [
                            'municipality' => $muni,
                            'year' => (int) $year,
                            'program_type' => $type,
                            'label' => $this->criterionLabel($type),
                            'count' => count($rows),
                            'selected_id' => (int) $selected->id,
                            'duplicate_ids' => array_map(fn ($r) => (int) $r->id, array_slice($rows, 1)),
                        ];
                    }
                    $lookup[$muni][$year][$type] = $this->usableCount($selected->beneficiary_count);
                }
            }
        }

        $typeList = array_keys($types);
        sort($typeList);

        return [
            'lookup' => $lookup,
            'duplicates' => $duplicates,
            'types' => $typeList,
        ];
    }

    public function isExcludedProgramType(string $type): bool
    {
        return in_array($type, self::EXCLUDED_PROGRAM_TYPES, true);
    }

    public function usableCount(mixed $count): ?int
    {
        if ($count === null) {
            return null;
        }
        $value = (int) $count;

        return $value > 0 ? $value : null;
    }

    /**
     * Full AHP ranking plus descriptive findings for one year.
     */
    public function analyze(int $year, Collection $annualRows, array $lookup, array $duplicates = []): array
    {
        $weights = $this->effectiveWeights();
        $criteria = $this->criteria();
        $matrix = [];
        $missing = [];

        foreach (self::MUNICIPALITIES as $muni) {
            foreach ($criteria as $type) {
                $value = $lookup[$muni][$year][$type] ?? null;
                $matrix[$muni][$type] = $value;
                if ($value === null) {
                    $hasAnnualRow = $annualRows->contains(function ($row) use ($muni, $year, $type) {
                        $rowMuni = (string) ($row->getRawOriginal('municipality') ?? $row->municipality);
                        return $rowMuni === $muni
                            && (int) $row->year === $year
                            && (string) $row->program_type === $type;
                    });
                    $missing[] = [
                        'municipality' => $muni,
                        'program_type' => $type,
                        'label' => $this->criterionLabel($type),
                        'year' => $year,
                        'reason' => $hasAnnualRow
                            ? 'Annual record exists but beneficiary_count is 0 or null (treated as N/A).'
                            : 'No annual record (month IS NULL) for this municipality, program, and year.',
                    ];
                }
            }
        }

        $yearDuplicates = array_values(array_filter(
            $duplicates,
            fn ($item) => (int) $item['year'] === $year
        ));

        $rankingAvailable = count($missing) === 0;
        $equalCriteria = [];
        $normalized = [];
        $weighted = [];
        $scores = [];
        $ranks = [];
        $rankingRows = [];

        if ($rankingAvailable) {
            foreach ($criteria as $type) {
                $values = [];
                foreach (self::MUNICIPALITIES as $muni) {
                    $values[$muni] = (int) $matrix[$muni][$type];
                }
                $min = min($values);
                $max = max($values);
                $range = $max - $min;
                if ($range === 0) {
                    $equalCriteria[] = $type;
                    foreach (self::MUNICIPALITIES as $muni) {
                        $normalized[$muni][$type] = self::EQUAL_CRITERION_NORMALIZED_VALUE;
                    }
                } else {
                    foreach (self::MUNICIPALITIES as $muni) {
                        $normalized[$muni][$type] = ($values[$muni] - $min) / $range;
                    }
                }
            }

            foreach (self::MUNICIPALITIES as $muni) {
                $score = 0.0;
                foreach ($criteria as $type) {
                    $contrib = $weights[$type] * $normalized[$muni][$type];
                    $weighted[$muni][$type] = $contrib;
                    $score += $contrib;
                }
                $scores[$muni] = round($score * 100, 4);
            }

            $sorted = $scores;
            arsort($sorted);
            $rank = 0;
            $previous = null;
            $seen = 0;
            foreach ($sorted as $muni => $score) {
                $seen++;
                if ($previous === null || abs($score - $previous) > 0.0000001) {
                    $rank = $seen;
                    $previous = $score;
                }
                $ranks[$muni] = $rank;
            }

            foreach ($sorted as $muni => $score) {
                $rankingRows[] = [
                    'municipality' => $muni,
                    'rank' => $ranks[$muni],
                    'score' => $score,
                    'raw' => $matrix[$muni],
                    'normalized' => $normalized[$muni],
                    'weighted' => $weighted[$muni],
                ];
            }
        }

        $warnings = $this->buildWarnings($year, $missing, $yearDuplicates, $rankingAvailable);
        $insights = $this->buildInsights($year, $lookup, $matrix, $rankingAvailable, $rankingRows, $weights, $yearDuplicates);
        $recommendations = $this->buildRecommendations($year, $missing, $lookup, $rankingAvailable, $rankingRows, $weights, $yearDuplicates);

        return [
            'year' => $year,
            'municipalities' => self::MUNICIPALITIES,
            'criteria' => $criteria,
            'criterion_labels' => self::CRITERION_LABELS,
            'raw_weights' => self::RAW_WEIGHTS,
            'effective_weights' => $weights,
            'equal_criterion_normalized_value' => self::EQUAL_CRITERION_NORMALIZED_VALUE,
            'matrix' => $matrix,
            'ranking_available' => $rankingAvailable,
            'missing' => $missing,
            'duplicates' => $yearDuplicates,
            'all_duplicates' => $duplicates,
            'equal_criteria' => $equalCriteria,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'scores' => $scores,
            'ranks' => $ranks,
            'ranking_rows' => $rankingRows,
            'warnings' => $warnings,
            'insights' => $insights,
            'recommendations' => $recommendations,
            'status' => $rankingAvailable ? 'Computed' : 'Not computed',
            'score_label' => 'AHP-weighted comparative score based on beneficiary counts',
            'disclaimer' => 'The score is an AHP-weighted comparative score based on beneficiary counts using approved AHP criteria weights. It does not measure poverty severity, program effectiveness, or unmet need.',
            'methodology' => $this->methodologyNotes(),
        ];
    }

    public function methodologyNotes(): array
    {
        $weights = $this->effectiveWeights();
        $weightText = [];
        foreach (self::RAW_WEIGHTS as $type => $raw) {
            $weightText[] = $this->criterionLabel($type) . ' raw ' . $raw
                . ', effective ' . round($weights[$type], 6);
        }

        return [
            'The overall ranking uses approved AHP criteria weights from pairwise comparisons of 10 respondents. Weights are not recalculated from beneficiary counts.',
            'Approved raw weights: ' . implode('; ', $weightText) . '. Each raw weight is divided by the total (0.9999) so effective weights sum to 1.0.',
            'AICS and other programs may appear in descriptive tables when annual data exist, but they are excluded from the three-criterion AHP score.',
            'Only annual municipality-level records are used (month IS NULL, not soft-deleted). Monthly slices are never added to annual totals.',
            'A stored beneficiary_count of 0, NULL, or a missing annual record is N/A (data not yet entered) and is never treated as zero beneficiaries.',
            'N(i,j) = (X(i,j) - min(X(j))) / (max(X(j)) - min(X(j))) across Liliw, Magdalena, and Majayjay. Score(i) = Σ effectiveWeight(j) × N(i,j), displayed on a 0–100 scale.',
            'If all three valid values for a criterion are equal, each municipality receives a normalized value of '
                . self::EQUAL_CRITERION_NORMALIZED_VALUE
                . ' for that criterion so no municipality gains an artificial advantage.',
            'Duplicate active annual rows for the same municipality, year, and program are not summed. The row with the highest id is used.',
            'A complete overall ranking requires a valid annual count greater than zero for Senior Citizen Pension, PWD Assistance, and Solo Parent in every municipality. Missing values are not replaced with zero and weights are not renormalized over a subset of criteria.',
        ];
    }

    private function buildWarnings(int $year, array $missing, array $duplicates, bool $rankingAvailable): array
    {
        $warnings = [];
        if (!$rankingAvailable) {
            $parts = array_map(
                fn ($item) => $item['municipality'] . ' / ' . $item['label'],
                $missing
            );
            $warnings[] = 'Overall AHP ranking for ' . $year . ' cannot be calculated because required annual data are N/A for: '
                . implode('; ', $parts)
                . '. A valid overall ranking will become available when all required annual data have been entered.';
        }
        foreach ($duplicates as $dup) {
            $warnings[] = 'Duplicate annual records for ' . $dup['municipality'] . ' / ' . $dup['label']
                . ' in ' . $dup['year'] . ' (count ' . $dup['count']
                . '). Used record id ' . $dup['selected_id'] . ' (highest id); other ids were not added together.';
        }

        return $warnings;
    }

    private function buildInsights(
        int $year,
        array $lookup,
        array $matrix,
        bool $rankingAvailable,
        array $rankingRows,
        array $weights,
        array $duplicates
    ): array {
        $insights = [];
        $types = $this->discoveredTypes($lookup);

        foreach ($types as $type) {
            $known = [];
            foreach (self::MUNICIPALITIES as $muni) {
                $value = $lookup[$muni][$year][$type] ?? null;
                if ($value !== null) {
                    $known[$muni] = $value;
                }
            }
            if (count($known) === 0) {
                continue;
            }
            $max = max($known);
            $min = min($known);
            $high = array_keys(array_filter($known, fn ($v) => $v === $max));
            $low = array_keys(array_filter($known, fn ($v) => $v === $min));
            $coverage = count($known);
            $coverageNote = $coverage < 3
                ? ' Based on ' . $coverage . ' of 3 municipalities with known annual data; this does not represent all three municipalities.'
                : '';
            $insights[] = $this->criterionLabel($type) . ' in ' . $year . ': highest known count is '
                . implode(' / ', $high) . ' (' . number_format($max) . '); lowest known count is '
                . implode(' / ', $low) . ' (' . number_format($min) . ').' . $coverageNote;

            if ($coverage >= 2 && $max !== $min) {
                $insights[] = $this->criterionLabel($type) . ' differs by '
                    . number_format($max - $min) . ' recorded beneficiaries between the highest and lowest known municipality values in '
                    . $year . '.';
            }
        }

        $growth = $this->yearOverYearChanges($lookup, $year);
        foreach ($growth as $line) {
            $insights[] = $line;
        }

        foreach (self::MUNICIPALITIES as $muni) {
            $missingTypes = [];
            foreach ($types as $type) {
                if (($lookup[$muni][$year][$type] ?? null) === null) {
                    $missingTypes[] = $this->criterionLabel($type);
                }
            }
            if (!empty($missingTypes)) {
                $insights[] = $muni . ' has N/A annual data for ' . implode(', ', $missingTypes) . ' in ' . $year . '.';
            }
        }

        if ($rankingAvailable && !empty($rankingRows)) {
            $lead = $rankingRows[0];
            $insights[] = 'AHP-weighted comparative ranking for ' . $year . ': '
                . $lead['municipality'] . ' ranks 1 with a score of '
                . number_format($lead['score'], 2)
                . ' out of 100. This is a comparative score based on beneficiary counts, not a measure of poverty, effectiveness, or unmet need.';
            foreach ($rankingRows as $row) {
                $parts = [];
                foreach ($this->criteria() as $type) {
                    $parts[] = $this->criterionLabel($type) . ' contribution '
                        . number_format($row['weighted'][$type] * 100, 2)
                        . ' points (weight ' . number_format($weights[$type] * 100, 2) . '%)';
                }
                $insights[] = $row['municipality'] . ' score breakdown: ' . implode('; ', $parts) . '.';
            }
        }

        foreach ($duplicates as $dup) {
            $insights[] = 'Data-quality issue: duplicate annual ' . $dup['label'] . ' records for '
                . $dup['municipality'] . ' in ' . $dup['year'] . '.';
        }

        return $insights;
    }

    private function buildRecommendations(
        int $year,
        array $missing,
        array $lookup,
        bool $rankingAvailable,
        array $rankingRows,
        array $weights,
        array $duplicates
    ): array {
        $recs = [];

        if (!$rankingAvailable) {
            $recs[] = 'Complete and validate the missing annual municipality/program records for ' . $year
                . ' before relying on an overall AHP ranking. Required data: Senior Citizen Pension, PWD Assistance, and Solo Parent for Liliw, Magdalena, and Majayjay.';
            foreach ($missing as $item) {
                $recs[] = 'Enter a validated annual beneficiary count greater than zero for '
                    . $item['municipality'] . ' / ' . $item['label'] . ' / ' . $item['year'] . '.';
            }
        }

        foreach ($duplicates as $dup) {
            $recs[] = 'Review and resolve duplicate annual records for ' . $dup['municipality']
                . ' / ' . $dup['label'] . ' / ' . $dup['year'] . ' so a single validated total remains.';
        }

        foreach ($this->criteria() as $type) {
            $known = [];
            foreach (self::MUNICIPALITIES as $muni) {
                $value = $lookup[$muni][$year][$type] ?? null;
                if ($value !== null) {
                    $known[$muni] = $value;
                }
            }
            if (count($known) >= 2) {
                $max = max($known);
                $min = min($known);
                if ($min > 0 && ($max / $min) >= 2) {
                    $recs[] = 'The known ' . $this->criterionLabel($type)
                        . ' counts in ' . $year . ' differ substantially across municipalities. Review the source records and confirm they are comparable before drawing conclusions.';
                }
            }
        }

        $years = $this->yearsInLookup($lookup);
        sort($years);
        $prev = $this->previousYearWithKnownData($years, $year, $lookup);
        if ($prev !== null) {
            $recs[] = 'Where year-to-year changes appear between ' . $prev . ' and ' . $year
                . ', validate the records and check documented program changes before treating the difference as a confirmed increase or decrease in beneficiaries.';
        }

        if ($rankingAvailable && !empty($rankingRows)) {
            foreach ($rankingRows as $row) {
                $topType = null;
                $topVal = -1;
                foreach ($this->criteria() as $type) {
                    if ($row['weighted'][$type] > $topVal) {
                        $topVal = $row['weighted'][$type];
                        $topType = $type;
                    }
                }
                $recs[] = $row['municipality'] . ' (rank ' . $row['rank'] . ', score '
                    . number_format($row['score'], 2)
                    . '): the largest criterion contribution is '
                    . $this->criterionLabel((string) $topType)
                    . '. Use this as a comparative profile of recorded beneficiary counts, not as evidence of greater poverty or better program performance.';
            }
        }

        if (empty($recs)) {
            $recs[] = 'Continue validating annual records as new years are entered so comparative ranking and descriptive summaries stay complete.';
        }

        return array_values(array_unique($recs));
    }

    private function yearOverYearChanges(array $lookup, int $selectedYear): array
    {
        $lines = [];
        $years = $this->yearsInLookup($lookup);
        sort($years);
        $prev = $this->previousYearWithKnownData($years, $selectedYear, $lookup);
        if ($prev === null) {
            return $lines;
        }

        $types = $this->discoveredTypes($lookup);
        foreach (self::MUNICIPALITIES as $muni) {
            foreach ($types as $type) {
                $current = $lookup[$muni][$selectedYear][$type] ?? null;
                $earlier = $lookup[$muni][$prev][$type] ?? null;
                if ($current === null || $earlier === null) {
                    if (($lookup[$muni][$selectedYear][$type] ?? null) !== null || ($lookup[$muni][$prev][$type] ?? null) !== null) {
                        $lines[] = 'A trend for ' . $muni . ' / ' . $this->criterionLabel($type)
                            . ' between ' . $prev . ' and ' . $selectedYear
                            . ' cannot be calculated because one of the years is N/A (including stored 0). A change from N/A to a positive count is not treated as a confirmed increase.';
                    }
                    continue;
                }
                $delta = $current - $earlier;
                if ($delta === 0) {
                    $lines[] = $muni . ' / ' . $this->criterionLabel($type)
                        . ' recorded the same known beneficiary count in ' . $prev . ' and ' . $selectedYear
                        . ' (' . number_format($current) . ').';
                    continue;
                }
                $direction = $delta > 0 ? 'increased' : 'decreased';
                $lines[] = $muni . ' / ' . $this->criterionLabel($type) . ' ' . $direction . ' from '
                    . number_format($earlier) . ' in ' . $prev . ' to '
                    . number_format($current) . ' in ' . $selectedYear
                    . '. Validate the records before treating this as a confirmed program change.';
            }
        }

        return array_values(array_unique($lines));
    }

    private function discoveredTypes(array $lookup): array
    {
        $types = [];
        foreach ($lookup as $byYear) {
            foreach ($byYear as $byType) {
                foreach (array_keys($byType) as $type) {
                    if ($this->isExcludedProgramType((string) $type)) {
                        continue;
                    }
                    $types[$type] = true;
                }
            }
        }
        foreach ($this->criteria() as $type) {
            $types[$type] = true;
        }
        $list = array_keys($types);
        sort($list);

        return $list;
    }

    private function yearsInLookup(array $lookup): array
    {
        $years = [];
        foreach ($lookup as $byYear) {
            foreach (array_keys($byYear) as $year) {
                $years[(int) $year] = true;
            }
        }
        $list = array_keys($years);
        sort($list);

        return $list;
    }

    private function previousYearWithKnownData(array $years, int $selectedYear, array $lookup): ?int
    {
        $previous = null;
        foreach ($years as $year) {
            if ($year < $selectedYear) {
                $previous = $year;
            }
        }
        if ($previous === null) {
            return null;
        }
        foreach (self::MUNICIPALITIES as $muni) {
            foreach ($this->discoveredTypes($lookup) as $type) {
                if (($lookup[$muni][$previous][$type] ?? null) !== null) {
                    return $previous;
                }
            }
        }

        return $previous;
    }
}

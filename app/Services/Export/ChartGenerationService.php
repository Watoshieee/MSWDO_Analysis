<?php

namespace App\Services\Export;

use App\Models\Barangay;
use App\Models\MunicipalityYearlySummary;
use App\Models\SocialWelfareProgram;

class ChartGenerationService
{
    protected int $width = 800;
    protected int $height = 480;

    /**
     * Generate all 7 required PNG charts and save them in the target directory.
     * Returns an associative array of [chart_key => absolute_file_path].
     */
    public function generateAllCharts(string $municipality, ?int $year, string $outputDir): array
    {
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $generated = [];

        // 1. Population Trend
        $generated['population_trend'] = $this->generatePopulationTrendChart($municipality, $outputDir);

        // 2. Gender Distribution
        $generated['gender_distribution'] = $this->generateGenderDistributionChart($municipality, $year, $outputDir);

        // 3. Age Structure
        $generated['age_structure'] = $this->generateAgeStructureChart($municipality, $year, $outputDir);

        // 4. Barangay Population (sorted descending)
        $generated['barangay_population'] = $this->generateBarangayPopulationChart($municipality, $year, $outputDir);

        // 5. Program Beneficiaries
        $generated['program_beneficiaries'] = $this->generateProgramBeneficiariesChart($municipality, $year, $outputDir);

        // 6. Social Program Trend
        $generated['social_program_trend'] = $this->generateSocialProgramTrendChart($municipality, $outputDir);

        // 7. Households vs Population
        $generated['households_vs_population'] = $this->generateHouseholdsVsPopulationChart($municipality, $outputDir);

        return $generated;
    }

    /**
     * 1. Population Trend (Line/Bar Chart across years)
     */
    public function generatePopulationTrendChart(string $municipality, string $outputDir): string
    {
        $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'population_trend.png';

        $data = MunicipalityYearlySummary::where('municipality', $municipality)
            ->orderBy('year', 'asc')
            ->pluck('total_population', 'year')
            ->toArray();

        if (empty($data)) {
            $data = [date('Y') => 0];
        }

        $title = "Population Trend across Available Years";
        $subtitle = "Municipality of {$municipality} | Total Population Growth";
        
        $this->renderBarChart($filePath, $title, $subtitle, $data, 'Year', 'Population', [44, 62, 143]);
        return $filePath;
    }

    /**
     * 2. Gender Distribution (Male vs Female)
     */
    public function generateGenderDistributionChart(string $municipality, ?int $year, string $outputDir): string
    {
        $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'gender_distribution.png';

        $summary = MunicipalityYearlySummary::where('municipality', $municipality);
        if ($year) {
            $summary->where('year', $year);
        }
        $row = $summary->orderBy('year', 'desc')->first();

        $male = (int) ($row->male_population ?? 0);
        $female = (int) ($row->female_population ?? 0);

        if ($male == 0 && $female == 0) {
            $muni = \App\Models\Municipality::where('name', $municipality)->first();
            if ($muni) {
                $male = (int) ($muni->male_population ?? 0);
                $female = (int) ($muni->female_population ?? 0);
            }
        }

        $data = [
            'Male' => $male,
            'Female' => $female,
        ];

        $yearLabel = $year ?: ($row->year ?? 'Latest');
        $title = "Gender Distribution (Male vs. Female)";
        $subtitle = "Municipality of {$municipality} ({$yearLabel})";

        $this->renderBarChart($filePath, $title, $subtitle, $data, 'Gender', 'Count', [8, 145, 178], [
            'Male' => [44, 62, 143],
            'Female' => [234, 88, 12]
        ]);

        return $filePath;
    }

    /**
     * 3. Age Structure (0–19, 20–59, 60+)
     */
    public function generateAgeStructureChart(string $municipality, ?int $year, string $outputDir): string
    {
        $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'age_structure.png';

        $summary = MunicipalityYearlySummary::where('municipality', $municipality);
        if ($year) {
            $summary->where('year', $year);
        }
        $row = $summary->orderBy('year', 'desc')->first();

        $age0_19 = (int) ($row->population_0_19 ?? 0);
        $age20_59 = (int) ($row->population_20_59 ?? 0);
        $age60 = (int) ($row->population_60_100 ?? 0);

        if ($age0_19 == 0 && $age20_59 == 0 && $age60 == 0) {
            $muni = \App\Models\Municipality::where('name', $municipality)->first();
            if ($muni) {
                $age0_19 = (int) ($muni->population_0_19 ?? 0);
                $age20_59 = (int) ($muni->population_20_59 ?? 0);
                $age60 = (int) ($muni->population_60_100 ?? 0);
            }
        }

        $data = [
            'Age 0-19' => $age0_19,
            'Age 20-59' => $age20_59,
            'Age 60+' => $age60,
        ];

        $yearLabel = $year ?: ($row->year ?? 'Latest');
        $title = "Demographic Age Structure Distribution";
        $subtitle = "Municipality of {$municipality} ({$yearLabel})";

        $this->renderBarChart($filePath, $title, $subtitle, $data, 'Age Group', 'Population', [22, 163, 74], [
            'Age 0-19' => [44, 62, 143],
            'Age 20-59' => [253, 185, 19],
            'Age 60+' => [196, 30, 36],
        ]);

        return $filePath;
    }

    /**
     * 4. Barangay Population (Sorted descending)
     */
    public function generateBarangayPopulationChart(string $municipality, ?int $year, string $outputDir): string
    {
        $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'barangay_population.png';

        $query = Barangay::where('municipality', $municipality);
        if ($year) {
            $query->where('year', $year);
        }
        $barangays = $query->orderBy('total_population', 'desc')->get();

        $data = [];
        foreach ($barangays as $b) {
            $name = strlen($b->name) > 14 ? substr($b->name, 0, 12) . '..' : $b->name;
            $data[$name] = (int) ($b->total_population ?? 0);
        }

        if (empty($data)) {
            $data = ['No Data' => 0];
        }

        $yearLabel = $year ?: 'All / Latest';
        $title = "Barangay Population (Highest to Lowest)";
        $subtitle = "Municipality of {$municipality} ({$yearLabel}) | Descending Ranking";

        $this->renderBarChart($filePath, $title, $subtitle, $data, 'Barangay', 'Population', [44, 62, 143]);

        return $filePath;
    }

    /**
     * 5. Program Beneficiary Distribution
     */
    public function generateProgramBeneficiariesChart(string $municipality, ?int $year, string $outputDir): string
    {
        $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'program_beneficiaries.png';

        $query = SocialWelfareProgram::where('municipality', $municipality);
        if ($year) {
            $query->where('year', $year);
        }
        $programs = $query->get()->groupBy('program_type');

        $data = [];
        foreach ($programs as $type => $group) {
            $label = str_replace(['_', 'Assistance', 'Citizen'], ['', 'Asst', 'Cit.'], $type);
            $label = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $label));
            if (strlen($label) > 13) $label = substr($label, 0, 11) . '..';
            $data[$label] = (int) $group->sum('beneficiary_count');
        }

        if (empty($data)) {
            // Fallback to MunicipalityYearlySummary
            $summary = MunicipalityYearlySummary::where('municipality', $municipality);
            if ($year) $summary->where('year', $year);
            $row = $summary->first();
            if ($row) {
                $data = [
                    '4Ps' => (int) $row->total_4ps,
                    'PWD' => (int) $row->total_pwd,
                    'Senior' => (int) $row->total_senior,
                    'AICS' => (int) $row->total_aics,
                    'Solo Parent' => (int) $row->total_solo_parent,
                ];
            } else {
                $data = ['No Programs' => 0];
            }
        }

        $yearLabel = $year ?: 'All / Latest';
        $title = "Social Welfare Program Beneficiary Distribution";
        $subtitle = "Municipality of {$municipality} ({$yearLabel}) | Beneficiary Counts";

        $this->renderBarChart($filePath, $title, $subtitle, $data, 'Program', 'Beneficiaries', [124, 58, 237]);

        return $filePath;
    }

    /**
     * 6. Social Program Trend (Beneficiary counts across years)
     */
    public function generateSocialProgramTrendChart(string $municipality, string $outputDir): string
    {
        $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'social_program_trend.png';

        $yearly = SocialWelfareProgram::where('municipality', $municipality)
            ->selectRaw('year, SUM(beneficiary_count) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year')
            ->toArray();

        if (empty($yearly)) {
            $summaries = MunicipalityYearlySummary::where('municipality', $municipality)
                ->orderBy('year')
                ->get();
            foreach ($summaries as $s) {
                $yearly[$s->year] = (int) ($s->total_4ps + $s->total_pwd + $s->total_senior + $s->total_aics + $s->total_solo_parent);
            }
        }

        if (empty($yearly)) {
            $yearly = [date('Y') => 0];
        }

        $title = "Social Welfare Program Trend Across Years";
        $subtitle = "Municipality of {$municipality} | Total Enrolled Beneficiaries per Year";

        $this->renderBarChart($filePath, $title, $subtitle, $yearly, 'Year', 'Total Beneficiaries', [253, 185, 19]);

        return $filePath;
    }

    /**
     * 7. Households vs Population
     */
    public function generateHouseholdsVsPopulationChart(string $municipality, string $outputDir): string
    {
        $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'households_vs_population.png';

        $summaries = MunicipalityYearlySummary::where('municipality', $municipality)
            ->orderBy('year')
            ->get();

        $years = [];
        $populations = [];
        $households = [];

        foreach ($summaries as $s) {
            $years[] = (string) $s->year;
            $populations[] = (int) ($s->total_population ?? 0);
            $households[] = (int) ($s->total_households ?? 0);
        }

        if (empty($years)) {
            $years = [date('Y')];
            $populations = [0];
            $households = [0];
        }

        $title = "Comparative Trend: Total Population vs. Total Households";
        $subtitle = "Municipality of {$municipality} | Demographic Scale Comparison";

        $this->renderDualBarChart($filePath, $title, $subtitle, $years, $populations, $households, 'Population', 'Households');

        return $filePath;
    }

    /**
     * Internal helper to draw a high-quality single bar chart.
     */
    protected function renderBarChart(
        string $targetPath,
        string $title,
        string $subtitle,
        array $data,
        string $xLabel,
        string $yLabel,
        array $defaultColor = [44, 62, 143],
        array $colorMap = []
    ): void {
        $w = $this->width;
        $h = $this->height;

        $img = imagecreatetruecolor($w, $h);
        imagealphablending($img, true);

        // Colors
        $cWhite = imagecolorallocate($img, 255, 255, 255);
        $cBgCard = imagecolorallocate($img, 248, 250, 252);
        $cBorder = imagecolorallocate($img, 226, 232, 240);
        $cGrid = imagecolorallocate($img, 241, 245, 249);
        $cPrimary = imagecolorallocate($img, 44, 62, 143);
        $cTextDark = imagecolorallocate($img, 30, 41, 59);
        $cTextMuted = imagecolorallocate($img, 100, 116, 139);
        $cYellow = imagecolorallocate($img, 253, 185, 19);

        // Fill background
        imagefilledrectangle($img, 0, 0, $w - 1, $h - 1, $cWhite);
        imagerectangle($img, 0, 0, $w - 1, $h - 1, $cBorder);

        // Top Banner bar
        imagefilledrectangle($img, 0, 0, $w - 1, 6, $cPrimary);
        imagefilledrectangle($img, 0, 6, 80, 10, $cYellow);

        // Title and subtitle
        imagestring($img, 5, 25, 18, $title, $cPrimary);
        imagestring($img, 2, 25, 38, $subtitle, $cTextMuted);

        // Chart plot area
        $plotX1 = 70;
        $plotY1 = 75;
        $plotX2 = $w - 30;
        $plotY2 = $h - 60;
        $plotW = $plotX2 - $plotX1;
        $plotH = $plotY2 - $plotY1;

        imagefilledrectangle($img, $plotX1, $plotY1, $plotX2, $plotY2, $cBgCard);
        imagerectangle($img, $plotX1, $plotY1, $plotX2, $plotY2, $cBorder);

        $maxVal = !empty($data) ? max(array_values($data)) : 0;
        if ($maxVal <= 0) $maxVal = 10;
        // Round up max to nice number
        $mag = pow(10, max(0, floor(log10($maxVal))));
        $yCeil = ceil($maxVal / $mag) * $mag;
        if ($yCeil == 0) $yCeil = 10;

        // Draw horizontal grid lines and Y-axis labels
        $gridSteps = 4;
        for ($i = 0; $i <= $gridSteps; $i++) {
            $yVal = ($yCeil / $gridSteps) * $i;
            $yPos = $plotY2 - ($plotH * ($i / $gridSteps));

            imageline($img, $plotX1, (int)$yPos, $plotX2, (int)$yPos, $cGrid);
            $strVal = number_format($yVal);
            $textX = $plotX1 - (strlen($strVal) * 8) - 8;
            imagestring($img, 2, max(5, $textX), (int)$yPos - 6, $strVal, $cTextMuted);
        }

        // Draw Bars
        $count = count($data);
        if ($count > 0) {
            $barSlotWidth = $plotW / $count;
            $barWidth = min(60, max(14, (int)($barSlotWidth * 0.65)));

            $idx = 0;
            foreach ($data as $key => $val) {
                $barH = ($plotH * ($val / $yCeil));
                $bx1 = (int)($plotX1 + ($idx * $barSlotWidth) + (($barSlotWidth - $barWidth) / 2));
                $bx2 = $bx1 + $barWidth;
                $by2 = $plotY2;
                $by1 = (int)($plotY2 - $barH);

                // Bar Color
                $rgb = $colorMap[$key] ?? $defaultColor;
                $cBar = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);

                if ($barH > 0) {
                    imagefilledrectangle($img, $bx1, $by1, $bx2, $by2, $cBar);
                    // Add subtle border
                    $cBarBorder = imagecolorallocate($img, max(0, $rgb[0]-30), max(0, $rgb[1]-30), max(0, $rgb[2]-30));
                    imagerectangle($img, $bx1, $by1, $bx2, $by2, $cBarBorder);
                }

                // Value on top of bar
                $vStr = number_format($val);
                $vx = (int)($bx1 + ($barWidth / 2) - ((strlen($vStr) * 6) / 2));
                imagestring($img, 1, max(0, $vx), max($plotY1 - 12, $by1 - 14), $vStr, $cTextDark);

                // X-axis label
                $kStr = (string)$key;
                $kx = (int)($bx1 + ($barWidth / 2) - ((strlen($kStr) * 6) / 2));
                imagestring($img, 2, max($plotX1, min($plotX2 - 40, $kx)), $plotY2 + 8, $kStr, $cTextDark);

                $idx++;
            }
        }

        // Axis label
        imagestring($img, 2, $plotX1, $h - 22, "[ " . strtoupper($xLabel) . " ]", $cTextMuted);
        imagestring($img, 2, $w - 180, $h - 22, "MSWDO Analysis System", $cPrimary);

        imagepng($img, $targetPath);
    }

    /**
     * Internal helper to draw a side-by-side comparative dual bar chart.
     */
    protected function renderDualBarChart(
        string $targetPath,
        string $title,
        string $subtitle,
        array $labels,
        array $series1,
        array $series2,
        string $label1,
        string $label2
    ): void {
        $w = $this->width;
        $h = $this->height;

        $img = imagecreatetruecolor($w, $h);
        imagealphablending($img, true);

        $cWhite = imagecolorallocate($img, 255, 255, 255);
        $cBgCard = imagecolorallocate($img, 248, 250, 252);
        $cBorder = imagecolorallocate($img, 226, 232, 240);
        $cGrid = imagecolorallocate($img, 241, 245, 249);
        $cPrimary = imagecolorallocate($img, 44, 62, 143);
        $cAccent = imagecolorallocate($img, 253, 185, 19);
        $cTextDark = imagecolorallocate($img, 30, 41, 59);
        $cTextMuted = imagecolorallocate($img, 100, 116, 139);

        imagefilledrectangle($img, 0, 0, $w - 1, $h - 1, $cWhite);
        imagerectangle($img, 0, 0, $w - 1, $h - 1, $cBorder);

        imagefilledrectangle($img, 0, 0, $w - 1, 6, $cPrimary);
        imagefilledrectangle($img, 0, 6, 80, 10, $cAccent);

        imagestring($img, 5, 25, 18, $title, $cPrimary);
        imagestring($img, 2, 25, 38, $subtitle, $cTextMuted);

        // Legend
        $legX = $w - 240;
        imagefilledrectangle($img, $legX, 22, $legX + 12, 34, $cPrimary);
        imagestring($img, 2, $legX + 18, 22, $label1, $cTextDark);

        imagefilledrectangle($img, $legX + 110, 22, $legX + 122, 34, $cAccent);
        imagestring($img, 2, $legX + 128, 22, $label2, $cTextDark);

        $plotX1 = 70;
        $plotY1 = 75;
        $plotX2 = $w - 30;
        $plotY2 = $h - 60;
        $plotW = $plotX2 - $plotX1;
        $plotH = $plotY2 - $plotY1;

        imagefilledrectangle($img, $plotX1, $plotY1, $plotX2, $plotY2, $cBgCard);
        imagerectangle($img, $plotX1, $plotY1, $plotX2, $plotY2, $cBorder);

        $maxVal = max(array_merge($series1, $series2, [10]));
        $mag = pow(10, max(0, floor(log10($maxVal))));
        $yCeil = ceil($maxVal / $mag) * $mag;
        if ($yCeil == 0) $yCeil = 10;

        $gridSteps = 4;
        for ($i = 0; $i <= $gridSteps; $i++) {
            $yVal = ($yCeil / $gridSteps) * $i;
            $yPos = $plotY2 - ($plotH * ($i / $gridSteps));

            imageline($img, $plotX1, (int)$yPos, $plotX2, (int)$yPos, $cGrid);
            $strVal = number_format($yVal);
            $textX = $plotX1 - (strlen($strVal) * 8) - 8;
            imagestring($img, 2, max(5, $textX), (int)$yPos - 6, $strVal, $cTextMuted);
        }

        $count = count($labels);
        if ($count > 0) {
            $slotW = $plotW / $count;
            $singleBarW = min(36, max(12, (int)($slotW * 0.35)));

            for ($i = 0; $i < $count; $i++) {
                $v1 = $series1[$i] ?? 0;
                $v2 = $series2[$i] ?? 0;

                $h1 = ($plotH * ($v1 / $yCeil));
                $h2 = ($plotH * ($v2 / $yCeil));

                $midX = $plotX1 + ($i * $slotW) + ($slotW / 2);

                $b1x1 = (int)($midX - $singleBarW - 2);
                $b1x2 = (int)($midX - 2);
                $b1y1 = (int)($plotY2 - $h1);

                $b2x1 = (int)($midX + 2);
                $b2x2 = (int)($midX + $singleBarW + 2);
                $b2y1 = (int)($plotY2 - $h2);

                if ($h1 > 0) {
                    imagefilledrectangle($img, $b1x1, $b1y1, $b1x2, $plotY2, $cPrimary);
                }
                if ($h2 > 0) {
                    imagefilledrectangle($img, $b2x1, $b2y1, $b2x2, $plotY2, $cAccent);
                }

                // Values
                $v1Str = number_format($v1);
                $v2Str = number_format($v2);
                imagestring($img, 1, max(0, (int)($b1x1 - 4)), max($plotY1 - 10, $b1y1 - 12), $v1Str, $cTextDark);
                imagestring($img, 1, max(0, (int)($b2x1 - 4)), max($plotY1 - 10, $b2y1 - 12), $v2Str, $cTextDark);

                // Category Label
                $lbl = (string)$labels[$i];
                $lx = (int)($midX - ((strlen($lbl) * 7) / 2));
                imagestring($img, 2, max($plotX1, $lx), $plotY2 + 8, $lbl, $cTextDark);
            }
        }

        imagestring($img, 2, $plotX1, $h - 22, "[ YEAR COMPARISON ]", $cTextMuted);
        imagestring($img, 2, $w - 180, $h - 22, "MSWDO Analysis System", $cPrimary);

        imagepng($img, $targetPath);
    }
}

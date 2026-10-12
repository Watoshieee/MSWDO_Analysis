<?php

namespace App\Services\Export;

use App\Models\Barangay;
use App\Models\MunicipalityYearlySummary;
use App\Models\SocialWelfareProgram;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Exception;

class AnalysisReportExcelService
{
    protected DssWsmService $dssService;
    protected ChartGenerationService $chartService;

    // Brand color constants
    const COLOR_PRIMARY = '2C3E8F';   // MSWDO Navy Blue
    const COLOR_SECONDARY = 'FDB913'; // MSWDO Gold
    const COLOR_LIGHT_BG = 'F8FAFC';  // Light background
    const COLOR_HEADER_TEXT = 'FFFFFF';
    const COLOR_BORDER = 'CBD5E1';

    public function __construct(DssWsmService $dssService, ChartGenerationService $chartService)
    {
        $this->dssService = $dssService;
        $this->chartService = $chartService;
    }

    /**
     * Generate the complete Analysis Report workbook (.xlsx).
     */
    public function generateReport(string $municipality, ?int $year = null): string
    {
        $muniSlug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $municipality));
        $yearSlug = $year ? (string) $year : 'all_years';
        $filename = "{$muniSlug}_analysis_report_{$yearSlug}.xlsx";

        $tempBaseDir = storage_path('app/temp/exports/' . uniqid('excel_exp_', true));
        if (!is_dir($tempBaseDir)) {
            mkdir($tempBaseDir, 0755, true);
        }

        $chartsDir = $tempBaseDir . DIRECTORY_SEPARATOR . 'charts';
        $chartFiles = $this->chartService->generateAllCharts($municipality, $year, $chartsDir);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('MSWDO Analysis System')
            ->setTitle("MSWDO Analysis Report - {$municipality}")
            ->setSubject("Social Welfare & Demographic Analysis ({$yearSlug})")
            ->setDescription("Automated Analysis Report containing raw data, DSS metrics, WSM priority scores, YoY trends, and visualizations for {$municipality}.");

        // Sheet 1: Executive Summary
        $sheetExec = $spreadsheet->getActiveSheet();
        $sheetExec->setTitle('Executive Summary');
        $this->buildExecutiveSummarySheet($sheetExec, $municipality, $year);

        // Sheet 2: Municipality Yearly Data
        $sheetMuni = $spreadsheet->createSheet();
        $sheetMuni->setTitle('Municipality Yearly Data');
        $this->buildMunicipalityYearlySheet($sheetMuni, $municipality, $year);

        // Sheet 3: Barangay Data
        $sheetBrgy = $spreadsheet->createSheet();
        $sheetBrgy->setTitle('Barangay Data');
        $this->buildBarangayDataSheet($sheetBrgy, $municipality, $year);

        // Sheet 4: Social Programs
        $sheetProg = $spreadsheet->createSheet();
        $sheetProg->setTitle('Social Programs');
        $this->buildSocialProgramsSheet($sheetProg, $municipality, $year);

        // Sheet 5: Data Summary
        $sheetSumm = $spreadsheet->createSheet();
        $sheetSumm->setTitle('Data Summary');
        $this->buildDataSummarySheet($sheetSumm, $municipality, $year);

        // Sheet 6: Year-over-Year Analysis
        $sheetYoy = $spreadsheet->createSheet();
        $sheetYoy->setTitle('Year-over-Year Analysis');
        $this->buildYearOverYearSheet($sheetYoy, $municipality);

        // Sheet 7: DSS Indicators
        $sheetDss = $spreadsheet->createSheet();
        $sheetDss->setTitle('DSS Indicators');
        $this->buildDssIndicatorsSheet($sheetDss, $municipality, $year);

        // Sheet 8: WSM Results
        $sheetWsm = $spreadsheet->createSheet();
        $sheetWsm->setTitle('WSM Results');
        $this->buildWsmResultsSheet($sheetWsm, $municipality, $year);

        // Sheet 9: Graphs
        $sheetGraphs = $spreadsheet->createSheet();
        $sheetGraphs->setTitle('Graphs');
        $this->buildGraphsSheet($sheetGraphs, $municipality, $year, $chartFiles);

        // Sheet 10: Methodology
        $sheetMethod = $spreadsheet->createSheet();
        $sheetMethod->setTitle('Methodology');
        $this->buildMethodologySheet($sheetMethod, $municipality, $year);

        $spreadsheet->setActiveSheetIndex(0);

        $filePath = $tempBaseDir . DIRECTORY_SEPARATOR . $filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }

    /**
     * 1. Executive Summary Sheet
     */
    protected function buildExecutiveSummarySheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $dss = $this->dssService->computeDssIndicators($municipality, $year);
        $wsm = $this->dssService->computeWsm($municipality, $year);

        // Banner Header
        $sheet->setCellValue('A1', "MUNICIPAL SOCIAL WELFARE AND DEVELOPMENT OFFICE (MSWDO)");
        $sheet->setCellValue('A2', "EXECUTIVE SUMMARY ANALYSIS REPORT");
        $sheet->setCellValue('A3', "Municipality: " . strtoupper($municipality) . "  |  Selected Year: " . ($year ?: 'All Available Years') . "  |  Export Date: " . date('Y-m-d H:i:s'));

        $this->styleBannerHeader($sheet, 'A1:H1', self::COLOR_PRIMARY, self::COLOR_HEADER_TEXT, 14);
        $this->styleBannerHeader($sheet, 'A2:H2', self::COLOR_PRIMARY, self::COLOR_SECONDARY, 12);
        $this->styleBannerHeader($sheet, 'A3:H3', self::COLOR_LIGHT_BG, '475569', 9, false);

        // KPI Metric Cards Table
        $sheet->setCellValue('A5', 'KEY DEMOGRAPHIC & WELFARE INDICATORS');
        $this->styleSectionTitle($sheet, 'A5:D5');

        $headers = ['Key Indicator', 'Metric Value', 'Unit / Basis', 'Analytical Context'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '6', $h);
            $col++;
        }
        $this->styleTableHeader($sheet, 'A6:D6');

        $kpis = [
            ['Total Population', $dss['population'], 'Residents', 'Current official census/registered municipality residents'],
            ['Total Households', $dss['households'], 'Households', 'Official municipality household registrations'],
            ['Average Household Size', $dss['household_size_ratio'] ?? 'N/A', 'Persons/HH', 'Ratio of total population to total households'],
            ['Total Social Beneficiaries', $dss['total_beneficiaries'], 'Beneficiaries', 'Aggregate beneficiaries across all welfare programs'],
            ['Overall Beneficiary Ratio', ($dss['overall_beneficiary_rate'] !== null) ? ($dss['overall_beneficiary_rate'] . '%') : 'N/A', 'Prevalence', 'Proportion of population receiving assistance'],
            ['PWD Beneficiaries', $dss['pwd'], 'Persons', 'PWD Assistance enrolled beneficiaries'],
            ['AICS Recipients', $dss['aics'], 'Cases', 'Assistance to Individuals in Crisis Situations'],
            ['Solo Parents', $dss['solo_parent'], 'Heads of HH', 'Registered Solo Parent beneficiaries'],
            ['4Ps Beneficiaries', $dss['four_ps'], 'Households', 'Pantawid Pamilyang Pilipino Program participants'],
            ['Senior Citizens', $dss['senior'], 'Elderly', 'Social pension and senior citizens assistance'],
            ['Calculated WSM Priority Barangay', !empty($wsm['barangays']) ? $wsm['barangays'][0]['barangay'] : 'N/A', 'Highest Score', 'Barangay with highest multi-criteria score: ' . (!empty($wsm['barangays']) ? number_format($wsm['barangays'][0]['final_wsm_score'], 4) : 'N/A')],
        ];

        $row = 7;
        foreach ($kpis as $kpi) {
            $sheet->setCellValue('A' . $row, $kpi[0]);
            $sheet->setCellValue('B' . $row, is_numeric($kpi[1]) ? number_format($kpi[1]) : $kpi[1]);
            $sheet->setCellValue('C' . $row, $kpi[2]);
            $sheet->setCellValue('D' . $row, $kpi[3]);
            $this->styleTableRow($sheet, "A{$row}:D{$row}", $row % 2 == 0);
            $row++;
        }

        // Disclaimer Note
        $sheet->setCellValue('A' . ($row + 1), "IMPORTANT NOTE: " . config('dss.disclaimer'));
        $sheet->getStyle('A' . ($row + 1))->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('64748B');

        $this->autoFitColumns($sheet, ['A', 'B', 'C', 'D']);
    }

    /**
     * 2. Municipality Yearly Data Sheet
     */
    protected function buildMunicipalityYearlySheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $sheet->setCellValue('A1', "MUNICIPALITY YEARLY DATA — " . strtoupper($municipality));
        $this->styleSectionTitle($sheet, 'A1:H1');

        $headers = ['Year', 'Total Population', 'Male', 'Female', 'Age 0–19', 'Age 20–59', 'Age 60+', 'Households'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '3', $h);
        }
        $this->styleTableHeader($sheet, 'A3:H3');

        $query = MunicipalityYearlySummary::where('municipality', $municipality);
        if ($year) $query->where('year', $year);
        $records = $query->orderBy('year', 'desc')->get();

        $row = 4;
        if ($records->isEmpty()) {
            $sheet->setCellValue('A4', 'No Municipality Yearly Data Available');
            $sheet->mergeCells('A4:H4');
        } else {
            foreach ($records as $r) {
                $sheet->setCellValue('A' . $row, $r->year);
                $sheet->setCellValue('B' . $row, (int)$r->total_population);
                $sheet->setCellValue('C' . $row, (int)$r->male_population);
                $sheet->setCellValue('D' . $row, (int)$r->female_population);
                $sheet->setCellValue('E' . $row, (int)$r->population_0_19);
                $sheet->setCellValue('F' . $row, (int)$r->population_20_59);
                $sheet->setCellValue('G' . $row, (int)$r->population_60_100);
                $sheet->setCellValue('H' . $row, (int)$r->total_households);

                $sheet->getStyle("B{$row}:H{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $this->styleTableRow($sheet, "A{$row}:H{$row}", $row % 2 == 0);
                $row++;
            }
        }

        $sheet->freezePane('A4');
        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * 3. Barangay Data Sheet
     */
    protected function buildBarangayDataSheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $sheet->setCellValue('A1', "BARANGAY LEVEL DEMOGRAPHIC & PROGRAM DATA — " . strtoupper($municipality));
        $this->styleSectionTitle($sheet, 'A1:I1');

        $headers = ['Year', 'Barangay', 'Total Population', 'PWD', 'AICS', 'Solo Parent', 'Households', '4Ps', 'Senior'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '3', $h);
        }
        $this->styleTableHeader($sheet, 'A3:I3');

        $query = Barangay::where('municipality', $municipality);
        if ($year) $query->where('year', $year);
        $records = $query->orderBy('name', 'asc')->get();

        $row = 4;
        if ($records->isEmpty()) {
            $sheet->setCellValue('A4', 'No Barangay Data Available');
            $sheet->mergeCells('A4:I4');
        } else {
            foreach ($records as $b) {
                $sheet->setCellValue('A' . $row, $b->year ?? ($year ?: ''));
                $sheet->setCellValue('B' . $row, $b->name);
                $sheet->setCellValue('C' . $row, (int)$b->total_population);
                $sheet->setCellValue('D' . $row, (int)$b->pwd_count);
                $sheet->setCellValue('E' . $row, (int)$b->aics_count);
                $sheet->setCellValue('F' . $row, (int)$b->single_parent_count);
                $sheet->setCellValue('G' . $row, (int)$b->total_households);
                $sheet->setCellValue('H' . $row, (int)$b->four_ps_count);
                $sheet->setCellValue('I' . $row, (int)$b->senior_count);

                $sheet->getStyle("C{$row}:I{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $this->styleTableRow($sheet, "A{$row}:I{$row}", $row % 2 == 0);
                $row++;
            }
        }

        $sheet->freezePane('A4');
        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * 4. Social Programs Sheet
     */
    protected function buildSocialProgramsSheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $sheet->setCellValue('A1', "SOCIAL WELFARE PROGRAMS & BENEFICIARY RECORDS — " . strtoupper($municipality));
        $this->styleSectionTitle($sheet, 'A1:D1');

        $headers = ['Year', 'Municipality', 'Program Type', 'Beneficiary Count'];
        $cols = ['A', 'B', 'C', 'D'];
        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '3', $h);
        }
        $this->styleTableHeader($sheet, 'A3:D3');

        $query = SocialWelfareProgram::where('municipality', $municipality);
        if ($year) $query->where('year', $year);
        $records = $query->orderBy('year', 'desc')->orderBy('program_type')->get();

        $row = 4;
        if ($records->isEmpty()) {
            $sheet->setCellValue('A4', 'No Social Program Data Available');
            $sheet->mergeCells('A4:D4');
        } else {
            foreach ($records as $p) {
                $sheet->setCellValue('A' . $row, $p->year);
                $sheet->setCellValue('B' . $row, $p->municipality);
                $sheet->setCellValue('C' . $row, $p->program_type);
                $sheet->setCellValue('D' . $row, (int)$p->beneficiary_count);

                $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $this->styleTableRow($sheet, "A{$row}:D{$row}", $row % 2 == 0);
                $row++;
            }
        }

        $sheet->freezePane('A4');
        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * 5. Data Summary Sheet
     */
    protected function buildDataSummarySheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $dss = $this->dssService->computeDssIndicators($municipality, $year);

        $sheet->setCellValue('A1', "COMPUTED DATA SUMMARY & DEMOGRAPHIC RATIOS — " . strtoupper($municipality));
        $this->styleSectionTitle($sheet, 'A1:C1');

        $headers = ['Indicator', 'Computed Value', 'Remarks'];
        $cols = ['A', 'B', 'C'];
        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '3', $h);
        }
        $this->styleTableHeader($sheet, 'A3:C3');

        $summaryRows = [
            ['Total Population', number_format($dss['population']), 'Registered residents in municipality'],
            ['Total Households', number_format($dss['households']), 'Registered households in municipality'],
            ['Average Household Size', $dss['household_size_ratio'] ? number_format($dss['household_size_ratio'], 2) : 'N/A', 'Population divided by households'],
            ['PWD Beneficiary Count', number_format($dss['pwd']), 'Persons with disability'],
            ['PWD Prevalence Rate', $dss['pwd_rate'] !== null ? $dss['pwd_rate'] . '%' : 'N/A', 'PWD / Total Population * 100'],
            ['AICS Beneficiary Count', number_format($dss['aics']), 'Assistance to individuals in crisis'],
            ['AICS Prevalence Rate', $dss['aics_rate'] !== null ? $dss['aics_rate'] . '%' : 'N/A', 'AICS / Total Population * 100'],
            ['Solo Parent Count', number_format($dss['solo_parent']), 'Solo parent heads of households'],
            ['Solo Parent Prevalence Rate', $dss['solo_parent_rate'] !== null ? $dss['solo_parent_rate'] . '%' : 'N/A', 'Solo Parents / Total Population * 100'],
            ['4Ps Household Count', number_format($dss['four_ps']), 'Pantawid Pamilyang Pilipino Program'],
            ['4Ps Prevalence Rate', $dss['four_ps_rate'] !== null ? $dss['four_ps_rate'] . '%' : 'N/A', '4Ps / Total Population * 100'],
            ['Senior Citizen Count', number_format($dss['senior']), 'Senior citizens pension / assistance'],
            ['Senior Citizen Prevalence Rate', $dss['senior_rate'] !== null ? $dss['senior_rate'] . '%' : 'N/A', 'Senior / Total Population * 100'],
            ['Total Program Beneficiaries', number_format($dss['total_beneficiaries']), 'Aggregate across all welfare assistance programs'],
            ['Overall Beneficiary Concentration', $dss['overall_beneficiary_rate'] !== null ? $dss['overall_beneficiary_rate'] . '%' : 'N/A', 'Total Beneficiaries / Total Population * 100'],
        ];

        $row = 4;
        foreach ($summaryRows as $item) {
            $sheet->setCellValue('A' . $row, $item[0]);
            $sheet->setCellValue('B' . $row, $item[1]);
            $sheet->setCellValue('C' . $row, $item[2]);
            $this->styleTableRow($sheet, "A{$row}:C{$row}", $row % 2 == 0);
            $row++;
        }

        $sheet->freezePane('A4');
        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * 6. Year-over-Year Analysis Sheet
     */
    protected function buildYearOverYearSheet(Worksheet $sheet, string $municipality): void
    {
        $sheet->setCellValue('A1', "YEAR-OVER-YEAR (YoY) DEMOGRAPHIC & PROGRAM TREND ANALYSIS — " . strtoupper($municipality));
        $this->styleSectionTitle($sheet, 'A1:H1');

        $headers = [
            'Year',
            'Population',
            'Pop Change',
            'Pop Growth Rate',
            'Households',
            'HH Change',
            'HH Growth Rate',
            'Total Beneficiaries',
            'Ben Change',
            'Ben Growth Rate'
        ];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '3', $h);
        }
        $this->styleTableHeader($sheet, 'A3:J3');

        $yoy = $this->dssService->computeYearOverYear($municipality);

        $row = 4;
        if (empty($yoy)) {
            $sheet->setCellValue('A4', 'No Multi-Year Summary Records Available');
            $sheet->mergeCells('A4:J4');
        } else {
            foreach ($yoy as $item) {
                $sheet->setCellValue('A' . $row, $item['year']);
                $sheet->setCellValue('B' . $row, $item['population']);
                $sheet->setCellValue('C' . $row, is_numeric($item['population_change']) ? $item['population_change'] : $item['population_change']);
                $sheet->setCellValue('D' . $row, $item['population_growth_rate']);
                $sheet->setCellValue('E' . $row, $item['households']);
                $sheet->setCellValue('F' . $row, is_numeric($item['households_change']) ? $item['households_change'] : $item['households_change']);
                $sheet->setCellValue('G' . $row, $item['households_growth_rate']);
                $sheet->setCellValue('H' . $row, $item['beneficiaries']);
                $sheet->setCellValue('I' . $row, is_numeric($item['beneficiaries_change']) ? $item['beneficiaries_change'] : $item['beneficiaries_change']);
                $sheet->setCellValue('J' . $row, $item['beneficiaries_growth_rate']);

                $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('#,##0');

                $this->styleTableRow($sheet, "A{$row}:J{$row}", $row % 2 == 0);
                $row++;
            }
        }

        $sheet->freezePane('A4');
        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * 7. DSS Indicators Sheet
     */
    protected function buildDssIndicatorsSheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $sheet->setCellValue('A1', "DECISION SUPPORT SYSTEM (DSS) — PREVALENCE & VULNERABILITY INDICATORS");
        $this->styleSectionTitle($sheet, 'A1:J1');

        $headers = [
            'Barangay',
            'Year',
            'Population',
            'PWD Rate (%)',
            'AICS Rate (%)',
            'Solo Parent Rate (%)',
            '4Ps Rate (%)',
            'Senior Rate (%)',
            'Pop Concentration (%)',
            'Beneficiary Concentration (%)'
        ];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '3', $h);
        }
        $this->styleTableHeader($sheet, 'A3:J3');

        $wsm = $this->dssService->computeWsm($municipality, $year);

        $row = 4;
        if (empty($wsm['barangays'])) {
            $sheet->setCellValue('A4', 'No Barangay Data Available for DSS Calculation');
            $sheet->mergeCells('A4:J4');
        } else {
            foreach ($wsm['barangays'] as $b) {
                $pop = $b['total_population'];
                $pwdRate = ($pop > 0) ? ($b['criteria_scores']['pwd']['raw'] / $pop) * 100 : 0.0;
                $aicsRate = ($pop > 0) ? ($b['criteria_scores']['aics']['raw'] / $pop) * 100 : 0.0;
                $soloRate = ($pop > 0) ? ($b['criteria_scores']['solo_parent']['raw'] / $pop) * 100 : 0.0;
                $fourPsRate = ($pop > 0) ? ($b['criteria_scores']['four_ps']['raw'] / $pop) * 100 : 0.0;
                $seniorRate = ($pop > 0) ? ($b['criteria_scores']['senior']['raw'] / $pop) * 100 : 0.0;
                $popConc = $b['criteria_scores']['population_concentration']['raw'];
                $benConc = $b['criteria_scores']['beneficiary_concentration']['raw'];

                $sheet->setCellValue('A' . $row, $b['barangay']);
                $sheet->setCellValue('B' . $row, $b['year']);
                $sheet->setCellValue('C' . $row, $pop);
                $sheet->setCellValue('D' . $row, round($pwdRate, 2) . '%');
                $sheet->setCellValue('E' . $row, round($aicsRate, 2) . '%');
                $sheet->setCellValue('F' . $row, round($soloRate, 2) . '%');
                $sheet->setCellValue('G' . $row, round($fourPsRate, 2) . '%');
                $sheet->setCellValue('H' . $row, round($seniorRate, 2) . '%');
                $sheet->setCellValue('I' . $row, round($popConc, 2) . '%');
                $sheet->setCellValue('J' . $row, round($benConc, 2) . '%');

                $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $this->styleTableRow($sheet, "A{$row}:J{$row}", $row % 2 == 0);
                $row++;
            }
        }

        $sheet->freezePane('A4');
        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * 8. WSM Results Sheet
     */
    protected function buildWsmResultsSheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $sheet->setCellValue('A1', "WEIGHTED SCORING MODEL (WSM) — TRANSPARENT PRIORITY ASSESSMENT");
        $this->styleSectionTitle($sheet, 'A1:H1');

        $headers = [
            'Barangay',
            'Year',
            'Evaluation Criterion',
            'Raw Value',
            'Normalized Score',
            'Configured Weight',
            'Weighted Score',
            'Final WSM Score',
            'Analytical Priority Indicator'
        ];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '3', $h);
        }
        $this->styleTableHeader($sheet, 'A3:I3');

        $wsm = $this->dssService->computeWsm($municipality, $year);

        $row = 4;
        if (empty($wsm['barangays'])) {
            $sheet->setCellValue('A4', 'No Barangay Data Available for WSM Calculation');
            $sheet->mergeCells('A4:I4');
        } else {
            foreach ($wsm['barangays'] as $b) {
                $firstRow = true;
                foreach ($b['criteria_scores'] as $critKey => $c) {
                    $sheet->setCellValue('A' . $row, $b['barangay']);
                    $sheet->setCellValue('B' . $row, $b['year']);
                    $sheet->setCellValue('C' . $row, $c['label']);
                    $sheet->setCellValue('D' . $row, $c['raw']);
                    $sheet->setCellValue('E' . $row, $c['normalized']);
                    $sheet->setCellValue('F' . $row, $c['weight']);
                    $sheet->setCellValue('G' . $row, $c['weighted_score']);

                    if ($firstRow) {
                        $sheet->setCellValue('H' . $row, $b['final_wsm_score']);
                        $sheet->setCellValue('I' . $row, $b['priority_level']);
                    }

                    $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('#,##0.##');
                    $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('0.0000');
                    $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('0.00');
                    $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('0.0000');
                    if ($firstRow) {
                        $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('0.0000');
                    }

                    $this->styleTableRow($sheet, "A{$row}:I{$row}", $row % 2 == 0);
                    $firstRow = false;
                    $row++;
                }
            }
        }

        $sheet->freezePane('A4');
        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * 9. Graphs Sheet with Embedded PNG Visualizations
     */
    protected function buildGraphsSheet(Worksheet $sheet, string $municipality, ?int $year, array $chartFiles): void
    {
        $sheet->setCellValue('A1', "GRAPHICAL DATA VISUALIZATIONS — " . strtoupper($municipality));
        $this->styleSectionTitle($sheet, 'A1:H1');

        $chartMeta = [
            'population_trend' => ['title' => 'A. Population Trend across Available Years', 'cell' => 'B4'],
            'gender_distribution' => ['title' => 'B. Gender Distribution (Male vs. Female)', 'cell' => 'I4'],
            'age_structure' => ['title' => 'C. Demographic Age Structure Distribution', 'cell' => 'B26'],
            'barangay_population' => ['title' => 'D. Barangay Population Ranking (Highest to Lowest)', 'cell' => 'I26'],
            'program_beneficiaries' => ['title' => 'E. Social Welfare Program Beneficiary Distribution', 'cell' => 'B48'],
            'social_program_trend' => ['title' => 'F. Social Program Trend across Years', 'cell' => 'I48'],
            'households_vs_population' => ['title' => 'G. Comparative Scale: Households vs. Total Population', 'cell' => 'B70'],
        ];

        foreach ($chartMeta as $key => $meta) {
            $filePath = $chartFiles[$key] ?? null;
            if ($filePath && file_exists($filePath)) {
                $drawing = new Drawing();
                $drawing->setName($meta['title']);
                $drawing->setDescription($meta['title']);
                $drawing->setPath($filePath);
                $drawing->setCoordinates($meta['cell']);
                $drawing->setWidth(480);
                $drawing->setHeight(288);
                $drawing->setWorksheet($sheet);
            }
        }

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(26);
        $sheet->getColumnDimension('I')->setWidth(26);
    }

    /**
     * 10. Methodology Sheet
     */
    protected function buildMethodologySheet(Worksheet $sheet, string $municipality, ?int $year): void
    {
        $sheet->setCellValue('A1', "DSS & WSM METHODOLOGICAL FRAMEWORK");
        $this->styleSectionTitle($sheet, 'A1:E1');

        $sheet->setCellValue('A3', "1. OVERVIEW & PURPOSE");
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11)->getColor()->setRGB(self::COLOR_PRIMARY);
        $sheet->setCellValue('A4', "This Decision Support System (DSS) incorporates a transparent Weighted Scoring Model (WSM) designed to assist MSWDO personnel in objective vulnerability assessment, resource allocation planning, and program prioritization. All calculations are traceable directly to official MSWDO records.");

        $sheet->setCellValue('A6', "2. CONFIGURED CRITERIA & WEIGHTS");
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(11)->getColor()->setRGB(self::COLOR_PRIMARY);

        $headers = ['Criterion Name', 'Configured Weight', 'Weight (%)', 'Data Source Table & Field', 'Analytical Description'];
        $cols = ['A', 'B', 'C', 'D', 'E'];
        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '7', $h);
        }
        $this->styleTableHeader($sheet, 'A7:E7');

        $criteria = $this->dssService->getCriteria();
        $row = 8;
        foreach ($criteria as $k => $c) {
            $sheet->setCellValue('A' . $row, $c['label']);
            $sheet->setCellValue('B' . $row, $c['weight']);
            $sheet->setCellValue('C' . $row, round($c['weight'] * 100) . '%');
            $sheet->setCellValue('D' . $row, "barangays." . ($c['source_field'] ?? ''));
            $sheet->setCellValue('E' . $row, $c['description'] ?? '');

            $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('0.00');
            $this->styleTableRow($sheet, "A{$row}:E{$row}", $row % 2 == 0);
            $row++;
        }

        // Summary Total row
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, '1.00');
        $sheet->setCellValue('C' . $row, '100%');
        $sheet->setCellValue('D' . $row, 'All criteria combined');
        $sheet->setCellValue('E' . $row, 'Normalized multi-criteria evaluation');
        $this->styleTableHeader($sheet, "A{$row}:E{$row}");

        // Mathematical formulation
        $fRow = $row + 2;
        $sheet->setCellValue('A' . $fRow, "3. MATHEMATICAL FORMULATION");
        $sheet->getStyle('A' . $fRow)->getFont()->setBold(true)->setSize(11)->getColor()->setRGB(self::COLOR_PRIMARY);

        $formulas = [
            ["Normalization Method", "Linear Max Normalization: Normalized Score = Raw Value / Max(Raw Value in Municipality)"],
            ["Weighted Score", "Weighted Score = Normalized Score × Criterion Weight"],
            ["Final WSM Score", "Final WSM Score = Σ (Weighted Scores across all 7 criteria)  [Scale: 0.0000 to 1.0000]"],
            ["Prevalence Rates", "Prevalence % = (Vulnerable Count / Total Population) × 100"],
            ["Population Growth Rate", "YoY Growth Rate % = [(Current Population - Previous Population) / Previous Population] × 100"],
        ];

        $r2 = $fRow + 1;
        foreach ($formulas as $f) {
            $sheet->setCellValue('A' . $r2, $f[0]);
            $sheet->setCellValue('B' . $r2, $f[1]);
            $sheet->getStyle("A{$r2}")->getFont()->setBold(true);
            $r2++;
        }

        // Disclaimer
        $dRow = $r2 + 2;
        $sheet->setCellValue('A' . $dRow, "4. OFFICIAL REGULATORY DISCLAIMER");
        $sheet->getStyle('A' . $dRow)->getFont()->setBold(true)->setSize(11)->getColor()->setRGB('C41E24');
        $sheet->setCellValue('A' . ($dRow + 1), config('dss.disclaimer'));
        $sheet->getStyle('A' . ($dRow + 1))->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('475569');

        $this->autoFitColumns($sheet, $cols);
    }

    /**
     * Styling Helpers
     */
    protected function styleBannerHeader(Worksheet $sheet, string $range, string $bgColor, string $textColor, int $fontSize = 12, bool $bold = true): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => $bold,
                'size' => $fontSize,
                'color' => ['rgb' => $textColor],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgColor],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(substr($range, 1, 1))->setRowHeight(26);
    }

    protected function styleSectionTitle(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => self::COLOR_PRIMARY],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::COLOR_LIGHT_BG],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);
    }

    protected function styleTableHeader(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => self::COLOR_HEADER_TEXT],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::COLOR_PRIMARY],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => self::COLOR_BORDER],
                ],
            ],
        ]);
        $sheet->getRowDimension(substr($range, 1, 1))->setRowHeight(22);
    }

    protected function styleTableRow(Worksheet $sheet, string $range, bool $isAlternate = false): void
    {
        $bgColor = $isAlternate ? 'F1F5F9' : 'FFFFFF';
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'size' => 9.5,
                'color' => ['rgb' => '1E293B'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgColor],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);
        $sheet->getRowDimension(preg_replace('/\D/', '', $range))->setRowHeight(20);
    }

    protected function autoFitColumns(Worksheet $sheet, array $columns): void
    {
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

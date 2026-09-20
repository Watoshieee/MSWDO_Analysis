<?php

namespace App\Services\Export;

use App\Models\Barangay;
use App\Models\MunicipalityYearlySummary;
use App\Models\SocialWelfareProgram;
use ZipArchive;
use Exception;

class RawDataZipExportService
{
    protected DssWsmService $dssService;
    protected ChartGenerationService $chartService;

    public function __construct(DssWsmService $dssService, ChartGenerationService $chartService)
    {
        $this->dssService = $dssService;
        $this->chartService = $chartService;
    }

    /**
     * Generate the complete Raw Data ZIP package.
     * 
     * @param string $municipality
     * @param int|null $year If null, exports all available years
     * @return string Path to the created zip file
     */
    public function generateZip(string $municipality, ?int $year = null): string
    {
        $muniSlug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $municipality));
        $yearSlug = $year ? (string) $year : 'all_years';
        $zipFilename = "{$muniSlug}_export_{$yearSlug}.zip";

        $tempBaseDir = storage_path('app/temp/exports/' . uniqid('raw_exp_', true));
        if (!is_dir($tempBaseDir)) {
            mkdir($tempBaseDir, 0755, true);
        }

        $zipPath = $tempBaseDir . DIRECTORY_SEPARATOR . $zipFilename;
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception("Unable to create zip file at: {$zipPath}");
        }

        // 1. Municipality Yearly Data CSV
        $muniCsvContent = $this->buildMunicipalityYearlyCsv($municipality, $year);
        $zip->addFromString("01_Municipality_Yearly_Data/{$muniSlug}_municipality_yearly_data.csv", $muniCsvContent);

        // 2. Barangay Data CSV
        $brgyCsvContent = $this->buildBarangayDataCsv($municipality, $year);
        $zip->addFromString("02_Barangay_Data/{$muniSlug}_barangay_data.csv", $brgyCsvContent);

        // 3. Social Programs CSV
        $progCsvContent = $this->buildSocialProgramsCsv($municipality, $year);
        $zip->addFromString("03_Social_Programs/{$muniSlug}_social_programs.csv", $progCsvContent);

        // 4. DSS / WSM CSV
        $dssCsvContent = $this->buildDssWsmCsv($municipality, $year);
        $zip->addFromString("04_DSS_WSM/{$muniSlug}_dss_wsm.csv", $dssCsvContent);

        // 5. Graphs
        $chartsDir = $tempBaseDir . DIRECTORY_SEPARATOR . 'charts';
        $chartFiles = $this->chartService->generateAllCharts($municipality, $year, $chartsDir);
        foreach ($chartFiles as $chartName => $chartFilePath) {
            if (file_exists($chartFilePath)) {
                $baseFilename = basename($chartFilePath);
                $zip->addFile($chartFilePath, "05_Graphs/{$baseFilename}");
            }
        }

        // 6. README.txt
        $readmeContent = $this->buildReadmeContent($municipality, $year, $muniSlug);
        $zip->addFromString("README.txt", $readmeContent);

        $zip->close();

        return $zipPath;
    }

    /**
     * Build 01_Municipality_Yearly_Data CSV content.
     */
    protected function buildMunicipalityYearlyCsv(string $municipality, ?int $year): string
    {
        $handle = fopen('php://temp', 'r+');
        // BOM for Excel UTF-8 recognition
        fputs($handle, "\xEF\xBB\xBF");

        // Headers
        fputcsv($handle, ['Year', 'Population', 'Male', 'Female', 'Age 0–19', 'Age 20–59', 'Age 60+', 'Households']);

        $query = MunicipalityYearlySummary::where('municipality', $municipality);
        if ($year) {
            $query->where('year', $year);
        }
        $records = $query->orderBy('year', 'desc')->get();

        if ($records->isEmpty()) {
            fputcsv($handle, ['No Municipality Yearly Data Available', '', '', '', '', '', '', '']);
        } else {
            foreach ($records as $r) {
                fputcsv($handle, [
                    $r->year,
                    $r->total_population !== null ? (int)$r->total_population : '',
                    $r->male_population !== null ? (int)$r->male_population : '',
                    $r->female_population !== null ? (int)$r->female_population : '',
                    $r->population_0_19 !== null ? (int)$r->population_0_19 : '',
                    $r->population_20_59 !== null ? (int)$r->population_20_59 : '',
                    $r->population_60_100 !== null ? (int)$r->population_60_100 : '',
                    $r->total_households !== null ? (int)$r->total_households : '',
                ]);
            }
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    /**
     * Build 02_Barangay_Data CSV content.
     */
    protected function buildBarangayDataCsv(string $municipality, ?int $year): string
    {
        $handle = fopen('php://temp', 'r+');
        fputs($handle, "\xEF\xBB\xBF");

        // Headers
        fputcsv($handle, ['Year', 'Barangay', 'Total Population', 'PWD', 'AICS', 'Solo Parent', 'Households', '4Ps', 'Senior']);

        $query = Barangay::where('municipality', $municipality);
        if ($year) {
            $query->where('year', $year);
        }
        $records = $query->orderBy('name', 'asc')->get();

        if ($records->isEmpty()) {
            fputcsv($handle, ['No Barangay Data Available', '', '', '', '', '', '', '', '']);
        } else {
            foreach ($records as $b) {
                fputcsv($handle, [
                    $b->year ?? ($year ?: ''),
                    $b->name,
                    $b->total_population !== null ? (int)$b->total_population : '',
                    $b->pwd_count !== null ? (int)$b->pwd_count : '',
                    $b->aics_count !== null ? (int)$b->aics_count : '',
                    $b->single_parent_count !== null ? (int)$b->single_parent_count : '',
                    $b->total_households !== null ? (int)$b->total_households : '',
                    $b->four_ps_count !== null ? (int)$b->four_ps_count : '',
                    $b->senior_count !== null ? (int)$b->senior_count : '',
                ]);
            }
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    /**
     * Build 03_Social_Programs CSV content.
     */
    protected function buildSocialProgramsCsv(string $municipality, ?int $year): string
    {
        $handle = fopen('php://temp', 'r+');
        fputs($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['Year', 'Municipality', 'Program', 'Beneficiary Count']);

        $query = SocialWelfareProgram::where('municipality', $municipality);
        if ($year) {
            $query->where('year', $year);
        }
        $records = $query->orderBy('year', 'desc')->orderBy('program_type')->get();

        if ($records->isEmpty()) {
            fputcsv($handle, ['No Social Program Data Available', '', '', '']);
        } else {
            foreach ($records as $p) {
                fputcsv($handle, [
                    $p->year,
                    $p->municipality,
                    $p->program_type,
                    $p->beneficiary_count !== null ? (int)$p->beneficiary_count : '',
                ]);
            }
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    /**
     * Build 04_DSS_WSM CSV content.
     */
    protected function buildDssWsmCsv(string $municipality, ?int $year): string
    {
        $handle = fopen('php://temp', 'r+');
        fputs($handle, "\xEF\xBB\xBF");

        // Section 1: Detailed Transparent WSM Calculation Table
        fputs($handle, "# SECTION 1: BARANGAY WEIGHTED SCORING MODEL (WSM) BREAKDOWN\n");
        fputcsv($handle, [
            'Municipality',
            'Year',
            'Barangay',
            'Criterion',
            'Raw Value',
            'Normalized Value',
            'Weight',
            'Weighted Score',
            'Final WSM Score',
            'Analytical Priority Indicator'
        ]);

        $wsm = $this->dssService->computeWsm($municipality, $year);

        if (empty($wsm['barangays'])) {
            fputcsv($handle, [$municipality, $year ?: 'All', 'No Barangay Data Available for WSM Calculation', '', '', '', '', '', '', '']);
        } else {
            foreach ($wsm['barangays'] as $b) {
                $firstRow = true;
                foreach ($b['criteria_scores'] as $critKey => $c) {
                    fputcsv($handle, [
                        $municipality,
                        $b['year'],
                        $b['barangay'],
                        $c['label'],
                        $c['raw'],
                        number_format($c['normalized'], 4),
                        number_format($c['weight'], 2),
                        number_format($c['weighted_score'], 4),
                        $firstRow ? number_format($b['final_wsm_score'], 4) : '',
                        $firstRow ? $b['priority_level'] : '',
                    ]);
                    $firstRow = false;
                }
            }
        }

        fputs($handle, "\n");
        fputs($handle, "# SECTION 2: MUNICIPALITY DSS INDICATORS SUMMARY\n");
        fputcsv($handle, [
            'Municipality',
            'Year',
            'Total Population',
            'Total Households',
            'Total Beneficiaries',
            'PWD Rate (%)',
            'AICS Rate (%)',
            'Solo Parent Rate (%)',
            '4Ps Rate (%)',
            'Senior Rate (%)',
            'Average WSM Score'
        ]);

        $dss = $this->dssService->computeDssIndicators($municipality, $year);
        $avgWsm = !empty($wsm['barangays']) ? round(collect($wsm['barangays'])->avg('final_wsm_score'), 4) : 0.0;

        fputcsv($handle, [
            $municipality,
            $dss['year'],
            $dss['population'],
            $dss['households'],
            $dss['total_beneficiaries'],
            $dss['pwd_rate'] !== null ? $dss['pwd_rate'] . '%' : 'N/A',
            $dss['aics_rate'] !== null ? $dss['aics_rate'] . '%' : 'N/A',
            $dss['solo_parent_rate'] !== null ? $dss['solo_parent_rate'] . '%' : 'N/A',
            $dss['four_ps_rate'] !== null ? $dss['four_ps_rate'] . '%' : 'N/A',
            $dss['senior_rate'] !== null ? $dss['senior_rate'] . '%' : 'N/A',
            number_format($avgWsm, 4)
        ]);

        fputs($handle, "\n");
        fputs($handle, "# NOTE: " . config('dss.disclaimer') . "\n");

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    /**
     * Build README.txt documentation.
     */
    protected function buildReadmeContent(string $municipality, ?int $year, string $muniSlug): string
    {
        $exportTimestamp = date('Y-m-d H:i:s T');
        $yearDisplay = $year ? (string)$year : 'All Available Years';
        $criteria = $this->dssService->getCriteria();
        $disclaimer = config('dss.disclaimer');

        $criteriaList = "";
        foreach ($criteria as $k => $c) {
            $pct = round($c['weight'] * 100);
            $criteriaList .= sprintf("  - %-36s : %2d%% (Weight: %.2f)\n", $c['label'], $pct, $c['weight']);
        }

        return <<<README_DOC
================================================================================
MSWDO ANALYSIS SYSTEM - RAW DATA & ANALYTICAL EXPORT PACKAGE
================================================================================

Municipality : {$municipality}
Export Year  : {$yearDisplay}
Export Date  : {$exportTimestamp}

--------------------------------------------------------------------------------
1. PACKAGE CONTENTS
--------------------------------------------------------------------------------

01_Municipality_Yearly_Data/
    {$muniSlug}_municipality_yearly_data.csv
    Historical yearly summary of total population, gender distribution,
    age cohorts (0-19, 20-59, 60+), and total households.

02_Barangay_Data/
    {$muniSlug}_barangay_data.csv
    Barangay-level demographic data and social welfare program beneficiary counts
    (PWD, AICS, Solo Parent, Households, 4Ps, Senior Citizen).

03_Social_Programs/
    {$muniSlug}_social_programs.csv
    Records of social welfare programs, program types, and beneficiary counts
    scoped to {$municipality}.

04_DSS_WSM/
    {$muniSlug}_dss_wsm.csv
    Decision Support System (DSS) prevalence metrics, linear max normalization,
    criterion weighted scores, and final Weighted Scoring Model (WSM) priority scores.

05_Graphs/
    population_trend.png          : Population changes across available years
    gender_distribution.png       : Male vs. Female demographic balance
    age_structure.png             : Population distribution by age cohorts
    barangay_population.png       : Barangay ranking from highest to lowest population
    program_beneficiaries.png     : Beneficiary counts across welfare programs
    social_program_trend.png      : Multi-year beneficiary growth trend
    households_vs_population.png  : Comparative trend of households and population

README.txt
    This documentation file.

--------------------------------------------------------------------------------
2. DATA SOURCES & INTEGRITY
--------------------------------------------------------------------------------
- All data records are exported directly from the live MSWDO database tables:
  * municipality_yearly_summary
  * barangays
  * social_welfare_programs
- Raw data and computed analytical metrics are strictly separated.
- No synthetic, random, or placeholder data has been generated.
- Missing values are preserved as blank/NULL and division-by-zero is handled safely.
- All CSV files are UTF-8 encoded with Byte Order Mark (BOM) for compatibility
  with Microsoft Excel, R, Python (Pandas), SPSS, and Stata.

--------------------------------------------------------------------------------
3. DECISION SUPPORT SYSTEM (DSS) & WEIGHTED SCORING MODEL (WSM) METHODOLOGY
--------------------------------------------------------------------------------

The Decision Support System evaluates barangay-level vulnerability and priority
indicators using a multi-criteria Weighted Scoring Model (WSM).

A. Criteria & Configured Weights (Total: 100%):
{$criteriaList}
  Total Weights Sum: 100% (1.00)

B. Normalization Method:
  Linear Max Normalization is applied per criterion:
  Normalized Score = Raw Value / Max(Raw Value in Municipality)
  Range: 0.0000 to 1.0000

C. Mathematical Formulas:
  Weighted Score    = Normalized Score × Criterion Weight
  Final WSM Score   = Σ (Weighted Scores across all criteria)
  Prevalence Rate % = (Vulnerable Cohort / Total Population) × 100

D. Priority Categorization Terminology:
  - Final WSM Score >= 0.65 : "Higher calculated priority score"
  - Final WSM Score >= 0.35 : "Moderate calculated priority score"
  - Final WSM Score <  0.35 : "Lower calculated priority score"

--------------------------------------------------------------------------------
4. OFFICIAL DISCLAIMER
--------------------------------------------------------------------------------
"{$disclaimer}"

================================================================================
Municipal Social Welfare and Development Office (MSWDO)
Generated by MSWDO Analysis System
================================================================================
README_DOC;
    }
}

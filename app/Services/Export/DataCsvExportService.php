<?php

namespace App\Services\Export;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\MunicipalityYearlySummary;
use App\Models\SocialWelfareProgram;

/**
 * DataCsvExportService
 *
 * Generates a combined, multi-section CSV export for the SuperAdmin
 * Data Management module. Every call queries the live database so that
 * exports always reflect the latest saved records.
 *
 * Sections included:
 *   1. Municipality Yearly Data
 *   2. Barangay Data
 *   3. Social Programs
 */
class DataCsvExportService
{
    /**
     * Build a combined CSV string (UTF-8 with BOM for Excel compatibility).
     *
     * @param  array  $filters  Accepted keys: municipality (string|null), year (int|null), category (string|null)
     * @return string           Raw CSV content
     */
    public function buildCombinedCsv(array $filters = []): string
    {
        $handle = fopen('php://temp', 'r+');

        // UTF-8 BOM — required for Microsoft Excel to open as UTF-8
        fputs($handle, "\xEF\xBB\xBF");

        $municipality = $filters['municipality'] ?? null;
        $year         = isset($filters['year']) && $filters['year'] !== 'all' && $filters['year'] !== ''
            ? (int) $filters['year']
            : null;
        $category = $filters['category'] ?? 'all'; // all | municipality | barangay | programs

        if ($category === 'all' || $category === 'municipality') {
            $this->writeMunicipalitySection($handle, $municipality, $year);
        }

        if ($category === 'all' || $category === 'barangay') {
            $this->writeBarangaySection($handle, $municipality, $year);
        }

        if ($category === 'all' || $category === 'programs') {
            $this->writeProgramsSection($handle, $municipality, $year);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    // ──────────────────────────────────────────────────────────────
    //  Section writers
    // ──────────────────────────────────────────────────────────────

    private function writeMunicipalitySection($handle, ?string $municipality, ?int $year): void
    {
        fputs($handle, "\n");
        fputs($handle, "\"## SECTION 1: MUNICIPALITY YEARLY DATA\"\n");

        fputcsv($handle, [
            'Year',
            'Municipality',
            'Total Population',
            'Male',
            'Female',
            'Age 0-19',
            'Age 20-59',
            'Age 60+',
            'Households',
        ]);

        $query = MunicipalityYearlySummary::query()
            ->orderBy('municipality')
            ->orderBy('year', 'desc');

        if ($municipality) {
            $query->where('municipality', $municipality);
        }
        if ($year) {
            $query->where('year', $year);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            fputcsv($handle, ['No municipality yearly data found for the selected filters.']);
            return;
        }

        foreach ($records as $r) {
            fputcsv($handle, $this->sanitizeRow([
                $r->year,
                $r->municipality,
                $r->total_population ?? 0,
                $r->male_population ?? 0,
                $r->female_population ?? 0,
                $r->population_0_19 ?? 0,
                $r->population_20_59 ?? 0,
                $r->population_60_100 ?? 0,
                $r->total_households ?? 0,
            ]));
        }

        fputs($handle, "\n");
    }

    private function writeBarangaySection($handle, ?string $municipality, ?int $year): void
    {
        fputs($handle, "\n");
        fputs($handle, "\"## SECTION 2: BARANGAY DATA\"\n");

        fputcsv($handle, [
            'Year',
            'Municipality',
            'Barangay',
            'Total Population',
            'PWD',
            'AICS',
            'Solo Parent',
            'Households',
            '4Ps',
            'Senior',
        ]);

        $query = Barangay::query()
            ->orderBy('municipality')
            ->orderBy('name')
            ->orderBy('year', 'desc');

        if ($municipality) {
            $query->where('municipality', $municipality);
        }
        if ($year) {
            $query->where('year', $year);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            fputcsv($handle, ['No barangay data found for the selected filters.']);
            return;
        }

        foreach ($records as $b) {
            fputcsv($handle, $this->sanitizeRow([
                $b->year ?? '',
                $b->municipality,
                $b->name,
                $b->total_population ?? 0,
                $b->pwd_count ?? 0,
                $b->aics_count ?? 0,
                $b->single_parent_count ?? 0,
                $b->total_households ?? 0,
                $b->four_ps_count ?? 0,
                $b->senior_count ?? 0,
            ]));
        }

        fputs($handle, "\n");
    }

    private function writeProgramsSection($handle, ?string $municipality, ?int $year): void
    {
        fputs($handle, "\n");
        fputs($handle, "\"## SECTION 3: SOCIAL PROGRAMS\"\n");

        fputcsv($handle, [
            'Year',
            'Municipality',
            'Program Type',
            'Beneficiary Count',
        ]);

        $query = SocialWelfareProgram::query()
            ->orderBy('municipality')
            ->orderBy('year', 'desc')
            ->orderBy('program_type');

        if ($municipality) {
            $query->where('municipality', $municipality);
        }
        if ($year) {
            $query->where('year', $year);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            fputcsv($handle, ['No social program data found for the selected filters.']);
            return;
        }

        foreach ($records as $p) {
            fputcsv($handle, $this->sanitizeRow([
                $p->year,
                $p->municipality,
                $p->program_type,
                $p->beneficiary_count ?? 0,
            ]));
        }

        fputs($handle, "\n");
    }

    // ──────────────────────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────────────────────

    /**
     * Sanitize a CSV row to prevent formula injection and ensure safe values.
     * Prefixes any cell starting with =, +, -, @ with a single quote.
     */
    private function sanitizeRow(array $row): array
    {
        return array_map(function ($cell) {
            $str = (string) $cell;
            if ($str !== '' && in_array($str[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
                return "'" . $str;
            }
            return $str;
        }, $row);
    }

    /**
     * Generate a descriptive filename for the CSV export.
     */
    public function buildFilename(?string $municipality, ?int $year, string $category = 'all'): string
    {
        $muniSlug = $municipality
            ? strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $municipality))
            : 'all_municipalities';

        $yearSlug = $year ? (string) $year : 'all_years';
        $catSlug  = $category !== 'all' ? "_{$category}" : '';

        return "{$muniSlug}_{$yearSlug}{$catSlug}_data_export.csv";
    }
}

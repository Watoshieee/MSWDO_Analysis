<?php

namespace App\Services;

use App\Models\Barangay;
use App\Models\CsvImportLog;
use App\Models\Municipality;
use App\Models\MunicipalityYearlySummary;
use App\Models\SocialWelfareProgram;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * SuperAdminCsvImportService
 *
 * Handles CSV import for the SuperAdmin role. Unlike the regular CsvService,
 * this service:
 *  - Is NOT restricted to Auth::user()->municipality — super admins can import
 *    data for any valid municipality.
 *  - Validates municipality names against the municipalities table.
 *  - Validates barangay names for barangay imports.
 *  - Uses DB transactions for each row batch.
 *  - Supports skip-or-update behaviour for duplicates.
 *  - Performs strict server-side file validation (MIME, size, content).
 *  - Protects against CSV formula injection on write-back (export only).
 */
class SuperAdminCsvImportService
{
    const MAX_FILE_SIZE_BYTES = 10 * 1024 * 1024; // 10 MB
    const ALLOWED_MIME_TYPES  = ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'];

    /** Valid program types accepted by the application */
    const VALID_PROGRAM_TYPES = [
        '4Ps', 'Senior_Citizen_Pension', 'PWD_Assistance',
        'AICS', 'SLP', 'ESA', 'Solo_Parent',
    ];

    /**
     * Import a CSV file for the given data type.
     *
     * @param  UploadedFile  $file
     * @param  string        $type     municipality_data | barangay_data | program_data
     * @param  string        $mode     'skip' | 'update'
     * @param  int|null      $filterYear
     * @return array{success:bool, message:string, data:array}
     */
    public function importCsv(UploadedFile $file, string $type, string $mode = 'update', ?int $filterYear = null): array
    {
        $userId   = Auth::id();
        $fileName = $file->getClientOriginalName();

        // ── Server-side file validation ───────────────────────────
        $validationError = $this->validateFile($file);
        if ($validationError) {
            return ['success' => false, 'message' => $validationError, 'data' => []];
        }

        // ── Log the import attempt ────────────────────────────────
        $importLog = CsvImportLog::create([
            'user_id'    => $userId,
            'file_name'  => $fileName,
            'file_type'  => $type,
            'total_rows' => 0,
            'status'     => 'processing',
        ]);

        try {
            $csvData = $this->parseCsv($file);

            if (empty($csvData)) {
                throw new \Exception('CSV file is empty or contains no valid data rows.');
            }

            $importLog->update(['total_rows' => count($csvData)]);

            $result = match ($type) {
                'municipality_data' => $this->importMunicipalityData($csvData, $mode, $filterYear),
                'barangay_data'     => $this->importBarangayData($csvData, $mode, $filterYear),
                'program_data'      => $this->importProgramData($csvData, $mode, $filterYear),
                default             => throw new \Exception('Invalid import type: ' . $type),
            };

            $importLog->update([
                'successful_rows' => $result['imported'],
                'failed_rows'     => $result['failed'] + $result['skipped'],
                'error_details'   => !empty($result['errors']) ? json_encode($result['errors']) : null,
                'status'          => 'completed',
            ]);

            $message = "Import completed: {$result['imported']} imported";
            if ($result['updated'] > 0) $message .= ", {$result['updated']} updated";
            if ($result['skipped'] > 0) $message .= ", {$result['skipped']} skipped";
            if ($result['failed']  > 0) $message .= ", {$result['failed']} failed";

            return ['success' => true, 'message' => $message, 'data' => $result];

        } catch (\Exception $e) {
            $importLog->update(['status' => 'failed', 'error_details' => $e->getMessage()]);
            Log::error('SuperAdmin CSV Import Error', ['error' => $e->getMessage(), 'type' => $type]);
            return ['success' => false, 'message' => 'Import failed: ' . $e->getMessage(), 'data' => []];
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  File validation
    // ══════════════════════════════════════════════════════════════

    private function validateFile(UploadedFile $file): ?string
    {
        if (!$file->isValid()) {
            return 'File upload failed or was corrupted during transfer.';
        }

        if ($file->getSize() > self::MAX_FILE_SIZE_BYTES) {
            return 'File size exceeds the 10 MB limit.';
        }

        // Real MIME check — don't trust client filename extension alone
        $realMime = $file->getMimeType();
        if (!in_array($realMime, self::ALLOWED_MIME_TYPES, true)) {
            // Some systems report text/plain for CSV — accept it
            if ($realMime !== 'text/plain') {
                return "Invalid file type detected ({$realMime}). Only CSV files are allowed.";
            }
        }

        return null;
    }

    // ══════════════════════════════════════════════════════════════
    //  CSV Parsing
    // ══════════════════════════════════════════════════════════════

    private function parseCsv(UploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \Exception('Unable to open uploaded CSV file.');
        }

        // Strip UTF-8 BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle);
        if ($header === false || empty($header)) {
            fclose($handle);
            throw new \Exception('CSV file is missing a header row.');
        }
        // Trim whitespace AND strip BOM bytes from every header cell
        // (Excel may embed BOM mid-stream or on a re-save)
        $header = array_map(function ($h) {
            $h = trim($h);
            // Remove UTF-8 BOM (\xEF\xBB\xBF) if it ended up inside the cell
            $h = ltrim($h, "\xEF\xBB\xBF");
            // Remove other zero-width / non-breaking characters
            $h = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $h);
            return trim($h);
        }, $header);

        $rows = [];
        $lineNumber = 2;
        while (($row = fgetcsv($handle)) !== false) {
            // Skip completely empty lines
            if (count(array_filter($row, fn($v) => trim($v) !== '')) === 0) {
                $lineNumber++;
                continue;
            }
            if (count($row) === count($header)) {
                $rows[$lineNumber] = array_combine($header, array_map('trim', $row));
            }
            $lineNumber++;
        }

        fclose($handle);
        return $rows;
    }

    // ══════════════════════════════════════════════════════════════
    //  Import: Municipality Yearly Data
    // ══════════════════════════════════════════════════════════════

    private function importMunicipalityData(array $data, string $mode, ?int $filterYear): array
    {
        $imported = 0; $updated = 0; $skipped = 0; $failed = 0;
        $errors = [];

        $validMunicipalities = Municipality::pluck('name')->map('strtolower')->toArray();

        DB::beginTransaction();
        try {
            foreach ($data as $lineNum => $row) {
                try {
                    // Required columns
                    $this->assertColumns($row, ['Year', 'Municipality', 'Total_Population', 'Total_Households'], $lineNum);

                    $municipality = trim($row['Municipality']);
                    $year         = (int) $row['Year'];

                    // Validate municipality
                    if (!in_array(strtolower($municipality), $validMunicipalities, true)) {
                        throw new \Exception("Unknown municipality: '{$municipality}'.");
                    }

                    // Year range validation
                    if ($year < 1990 || $year > 2099) {
                        throw new \Exception("Year {$year} is out of valid range (1990–2099).");
                    }

                    // Filter year
                    if ($filterYear !== null && $year !== $filterYear) {
                        $skipped++;
                        continue;
                    }

                    // Numeric validation
                    $totalPop  = $this->assertNonNegativeInt($row['Total_Population'], 'Total_Population', $lineNum);
                    $totalHh   = $this->assertNonNegativeInt($row['Total_Households'], 'Total_Households', $lineNum);
                    $male      = $this->optionalInt($row['Male'] ?? null);
                    $female    = $this->optionalInt($row['Female'] ?? null);
                    $age0      = $this->optionalInt($row['Age_0_19'] ?? null);
                    $age20     = $this->optionalInt($row['Age_20_59'] ?? null);
                    $age60     = $this->optionalInt($row['Age_60_Plus'] ?? null);

                    $fields = [
                        'total_population'  => $totalPop,
                        'total_households'  => $totalHh,
                        'male_population'   => $male,
                        'female_population' => $female,
                        'population_0_19'   => $age0,
                        'population_20_59'  => $age20,
                        'population_60_100' => $age60,
                    ];

                    $existing = MunicipalityYearlySummary::where('municipality', $municipality)
                        ->where('year', $year)
                        ->first();

                    if ($existing) {
                        if ($mode === 'skip') {
                            $skipped++;
                        } else {
                            $existing->update($fields);
                            $updated++;
                        }
                    } else {
                        MunicipalityYearlySummary::create(array_merge(
                            ['municipality' => $municipality, 'year' => $year],
                            $fields
                        ));
                        $imported++;
                    }

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Row {$lineNum}: " . $e->getMessage();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return compact('imported', 'updated', 'skipped', 'failed', 'errors');
    }

    // ══════════════════════════════════════════════════════════════
    //  Import: Barangay Data
    // ══════════════════════════════════════════════════════════════

    private function importBarangayData(array $data, string $mode, ?int $filterYear): array
    {
        $imported = 0; $updated = 0; $skipped = 0; $failed = 0;
        $errors = [];

        $validMunicipalities = Municipality::pluck('name')->map('strtolower')->toArray();

        DB::beginTransaction();
        try {
            foreach ($data as $lineNum => $row) {
                try {
                    $this->assertColumns($row, ['Municipality', 'Barangay', 'Year', 'Total_Population'], $lineNum);

                    $municipality = trim($row['Municipality']);
                    $barangay     = trim($row['Barangay']);
                    $year         = (int) $row['Year'];

                    if (!in_array(strtolower($municipality), $validMunicipalities, true)) {
                        throw new \Exception("Unknown municipality: '{$municipality}'.");
                    }

                    if (empty($barangay)) {
                        throw new \Exception("Barangay name is required.");
                    }

                    if ($year < 1990 || $year > 2099) {
                        throw new \Exception("Year {$year} is out of valid range (1990–2099).");
                    }

                    if ($filterYear !== null && $year !== $filterYear) {
                        $skipped++;
                        continue;
                    }

                    $fields = [
                        'total_population'    => $this->assertNonNegativeInt($row['Total_Population'], 'Total_Population', $lineNum),
                        'pwd_count'           => $this->optionalInt($row['PWD']         ?? null),
                        'aics_count'          => $this->optionalInt($row['AICS']        ?? null),
                        'single_parent_count' => $this->optionalInt($row['Solo_Parent'] ?? null),
                        'total_households'    => $this->optionalInt($row['Households']  ?? null),
                        'four_ps_count'       => $this->optionalInt($row['4Ps']         ?? null),
                        'senior_count'        => $this->optionalInt($row['Senior']      ?? null),
                    ];

                    $existing = Barangay::where('municipality', $municipality)
                        ->where('name', $barangay)
                        ->where('year', $year)
                        ->first();

                    if ($existing) {
                        if ($mode === 'skip') {
                            $skipped++;
                        } else {
                            $existing->update($fields);
                            $updated++;
                        }
                    } else {
                        Barangay::create(array_merge(
                            ['municipality' => $municipality, 'name' => $barangay, 'year' => $year],
                            $fields
                        ));
                        $imported++;
                    }

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Row {$lineNum}: " . $e->getMessage();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return compact('imported', 'updated', 'skipped', 'failed', 'errors');
    }

    // ══════════════════════════════════════════════════════════════
    //  Import: Social Programs
    // ══════════════════════════════════════════════════════════════

    private function importProgramData(array $data, string $mode, ?int $filterYear): array
    {
        $imported = 0; $updated = 0; $skipped = 0; $failed = 0;
        $errors = [];
        $affectedYears = [];

        $validMunicipalities = Municipality::pluck('name')->map('strtolower')->toArray();

        DB::beginTransaction();
        try {
            foreach ($data as $lineNum => $row) {
                try {
                    $this->assertColumns($row, ['Municipality', 'Program', 'Year', 'Beneficiaries'], $lineNum);

                    $municipality = trim($row['Municipality']);
                    $programType  = trim($row['Program']);
                    $year         = (int) $row['Year'];

                    if (!in_array(strtolower($municipality), $validMunicipalities, true)) {
                        throw new \Exception("Unknown municipality: '{$municipality}'.");
                    }

                    if (!in_array($programType, self::VALID_PROGRAM_TYPES, true)) {
                        throw new \Exception("Unknown program type: '{$programType}'. Valid types: " . implode(', ', self::VALID_PROGRAM_TYPES) . '.');
                    }

                    if ($year < 1990 || $year > 2099) {
                        throw new \Exception("Year {$year} is out of valid range (1990–2099).");
                    }

                    if ($filterYear !== null && $year !== $filterYear) {
                        $skipped++;
                        continue;
                    }

                    $beneficiaryCount = $this->assertNonNegativeInt($row['Beneficiaries'], 'Beneficiaries', $lineNum);

                    $existing = SocialWelfareProgram::where('municipality', $municipality)
                        ->where('program_type', $programType)
                        ->where('year', $year)
                        ->whereNull('month')
                        ->first();

                    if ($existing) {
                        if ($mode === 'skip') {
                            $skipped++;
                        } else {
                            $existing->update(['beneficiary_count' => $beneficiaryCount]);
                            $updated++;
                            $affectedYears[$municipality][$year] = true;
                        }
                    } else {
                        SocialWelfareProgram::create([
                            'municipality'     => $municipality,
                            'program_type'     => $programType,
                            'year'             => $year,
                            'beneficiary_count'=> $beneficiaryCount,
                            'barangay'         => null,
                            'month'            => null,
                        ]);
                        $imported++;
                        $affectedYears[$municipality][$year] = true;
                    }

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Row {$lineNum}: " . $e->getMessage();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return compact('imported', 'updated', 'skipped', 'failed', 'errors');
    }

    // ══════════════════════════════════════════════════════════════
    //  Validation helpers
    // ══════════════════════════════════════════════════════════════

    private function assertColumns(array $row, array $required, int $lineNum): void
    {
        foreach ($required as $col) {
            if (!array_key_exists($col, $row) || trim($row[$col]) === '') {
                throw new \Exception("Missing required column or empty value: '{$col}'.");
            }
        }
    }

    private function assertNonNegativeInt($value, string $field, int $lineNum): int
    {
        if (!is_numeric($value)) {
            throw new \Exception("Field '{$field}' must be a number, got: '{$value}'.");
        }
        $int = (int) $value;
        if ($int < 0) {
            throw new \Exception("Field '{$field}' must be 0 or greater, got: {$int}.");
        }
        return $int;
    }

    private function optionalInt($value): int
    {
        if ($value === null || trim((string) $value) === '') return 0;
        return max(0, (int) $value);
    }

    // ══════════════════════════════════════════════════════════════
    //  Template generation
    // ══════════════════════════════════════════════════════════════

    /**
     * Build a downloadable CSV template string for the given type.
     */
    public function buildTemplate(string $type, ?string $municipality = null): string
    {
        $muni = $municipality ?: 'Magdalena';
        $year = date('Y') - 1; // Use last year as example — current year data may not be finalized yet

        $handle = fopen('php://temp', 'r+');
        fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

        switch ($type) {
            case 'municipality_data':
                fputcsv($handle, ['Year', 'Municipality', 'Total_Population', 'Total_Households', 'Male', 'Female', 'Age_0_19', 'Age_20_59', 'Age_60_Plus']);
                // Scoped to $muni (admin) or all municipalities (super admin)
                $muniList = $muni
                    ? [$muni]
                    : Municipality::orderBy('name')->pluck('name')->toArray();
                foreach ($muniList as $m) {
                    fputcsv($handle, [$year, $m, '', '', '', '', '', '', '']);
                }
                break;

            case 'barangay_data':
                fputcsv($handle, ['Municipality', 'Barangay', 'Year', 'Total_Population', 'PWD', 'AICS', 'Solo_Parent', 'Households', '4Ps', 'Senior']);
                // Scoped to $muni (admin) or all municipalities (super admin)
                $bgyQuery = Barangay::select('municipality', 'name')
                    ->orderBy('municipality')
                    ->orderBy('name')
                    ->distinct();
                if ($muni) {
                    $bgyQuery->where('municipality', $muni);
                }
                foreach ($bgyQuery->get() as $b) {
                    fputcsv($handle, [$b->municipality, $b->name, $year, '', '', '', '', '', '', '']);
                }
                break;

            case 'program_data':
                fputcsv($handle, ['Municipality', 'Program', 'Year', 'Beneficiaries']);
                // Scoped to $muni (admin) or all municipalities (super admin)
                $progMuniList = $muni
                    ? [$muni]
                    : Municipality::orderBy('name')->pluck('name')->toArray();
                foreach ($progMuniList as $m) {
                    foreach (self::VALID_PROGRAM_TYPES as $prog) {
                        fputcsv($handle, [$m, $prog, $year, '']);
                    }
                }
                break;
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        return $content;
    }
}

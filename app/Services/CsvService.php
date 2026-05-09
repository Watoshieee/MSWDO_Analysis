<?php

namespace App\Services;

use App\Models\CsvImportLog;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CsvService
{
    /**
     * Import CSV file and process data
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type (municipality_data, barangay_data, program_data)
     * @return array
     */
    public function importCsv($file, $type)
    {
        $userId = Auth::id();
        $adminMunicipality = Auth::user()->municipality;
        $fileName = $file->getClientOriginalName();
        
        // Create import log
        $importLog = CsvImportLog::create([
            'user_id' => $userId,
            'file_name' => $fileName,
            'file_type' => $type,
            'total_rows' => 0,
            'status' => 'processing'
        ]);

        try {
            // Read CSV file
            $csvData = $this->readCsvFile($file);
            
            if (empty($csvData)) {
                throw new \Exception('CSV file is empty or invalid');
            }

            $importLog->update(['total_rows' => count($csvData)]);

            // Process based on type
            $result = match($type) {
                'municipality_data' => $this->importMunicipalityData($csvData, $adminMunicipality),
                'barangay_data' => $this->importBarangayData($csvData, $adminMunicipality),
                'program_data' => $this->importProgramData($csvData, $adminMunicipality),
                default => throw new \Exception('Invalid import type')
            };

            // Update import log
            $importLog->update([
                'successful_rows' => $result['success'],
                'failed_rows' => $result['failed'],
                'error_details' => !empty($result['errors']) ? json_encode($result['errors']) : null,
                'status' => 'completed'
            ]);

            return [
                'success' => true,
                'message' => "Import completed: {$result['success']} successful, {$result['failed']} failed",
                'data' => $result
            ];

        } catch (\Exception $e) {
            $importLog->update([
                'status' => 'failed',
                'error_details' => $e->getMessage()
            ]);

            Log::error('CSV Import Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Read and parse CSV file
     */
    private function readCsvFile($file)
    {
        $csvData = [];
        $handle = fopen($file->getRealPath(), 'r');
        
        if ($handle === false) {
            throw new \Exception('Unable to open CSV file');
        }

        // Read header row
        $header = fgetcsv($handle);
        
        if ($header === false) {
            fclose($handle);
            throw new \Exception('CSV file has no header row');
        }

        // Trim whitespace from headers
        $header = array_map('trim', $header);

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($header)) {
                $csvData[] = array_combine($header, array_map('trim', $row));
            }
        }

        fclose($handle);
        return $csvData;
    }

    /**
     * Import Municipality Data
     * - Skips rows where a MunicipalityYearlySummary already exists for that year (no overwrites).
     * - Writes to both the `municipalities` table AND `municipality_yearly_summaries`.
     */
    private function importMunicipalityData($data, $adminMunicipality)
    {
        $success = 0;
        $failed  = 0;
        $skipped = 0;
        $errors  = [];

        foreach ($data as $index => $row) {
            try {
                $this->validateColumns($row, ['Year', 'Municipality', 'Population', 'Male', 'Female']);

                if (trim($row['Municipality']) !== $adminMunicipality) {
                    throw new \Exception("You can only import data for {$adminMunicipality}");
                }

                $year = (int) $row['Year'];

                // ── Duplicate guard ───────────────────────────────────────────
                $alreadyExists = MunicipalityYearlySummary::where('municipality', $adminMunicipality)
                    ->where('year', $year)
                    ->exists();

                if ($alreadyExists) {
                    $skipped++;
                    $errors[] = "Row " . ($index + 2) . ": Year {$year} already exists — skipped (existing data preserved).";
                    continue;
                }

                $totalPop  = (int)  $row['Population'];
                $malePop   = (int)  $row['Male'];
                $femalePop = (int)  $row['Female'];
                $growthRate = isset($row['GrowthRate']) ? (float) $row['GrowthRate'] : null;

                // ── Save to municipalities table ──────────────────────────────
                Municipality::updateOrCreate(
                    ['name' => $adminMunicipality, 'year' => $year],
                    [
                        'total_population'  => $totalPop,
                        'male_population'   => $malePop,
                        'female_population' => $femalePop,
                        'growth_rate'       => $growthRate,
                    ]
                );

                // ── Sync to MunicipalityYearlySummary (powers /data/yearly) ──
                MunicipalityYearlySummary::create([
                    'municipality'     => $adminMunicipality,
                    'year'             => $year,
                    'total_population' => $totalPop,
                    'male_population'  => $malePop,
                    'female_population'=> $femalePop,
                    'total_households' => 0,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $message = "{$success} imported";
        if ($skipped) $message .= ", {$skipped} skipped (year already exists)";
        if ($failed)  $message .= ", {$failed} failed";

        return compact('success', 'failed', 'skipped', 'errors');
    }

    /**
     * Import Barangay Data
     * - Skips rows where barangay + year already exists (no overwrites).
     */
    private function importBarangayData($data, $adminMunicipality)
    {
        $success = 0;
        $failed  = 0;
        $skipped = 0;
        $errors  = [];

        foreach ($data as $index => $row) {
            try {
                $this->validateColumns($row, ['Municipality', 'Barangay', 'Year', 'Population']);

                if (trim($row['Municipality']) !== $adminMunicipality) {
                    throw new \Exception("You can only import data for {$adminMunicipality}");
                }

                $year     = (int) $row['Year'];
                $barangay = trim($row['Barangay']);

                // ── Duplicate guard ───────────────────────────────────────────
                $alreadyExists = Barangay::where('municipality', $adminMunicipality)
                    ->where('name', $barangay)
                    ->where('year', $year)
                    ->exists();

                if ($alreadyExists) {
                    $skipped++;
                    $errors[] = "Row " . ($index + 2) . ": {$barangay} ({$year}) already exists — skipped.";
                    continue;
                }

                Barangay::create([
                    'municipality'      => $adminMunicipality,
                    'name'              => $barangay,
                    'year'              => $year,
                    'total_population'  => (int)($row['Population'] ?? 0),
                    'male_population'   => (int)($row['Male']       ?? 0),
                    'female_population' => (int)($row['Female']     ?? 0),
                    'population_0_19'   => (int)($row['Age_0_19']   ?? 0),
                    'population_20_59'  => (int)($row['Age_20_59']  ?? 0),
                    'population_60_100' => (int)($row['Age_60_100'] ?? 0),
                    'total_households'  => (int)($row['Households'] ?? 0),
                ]);

                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return compact('success', 'failed', 'skipped', 'errors');
    }

    /**
     * Import Program Data
     * - Skips rows where municipality + program_type + year (+month) already exists.
     * - After import, refreshes MunicipalityYearlySummary program totals for affected years.
     */
    private function importProgramData($data, $adminMunicipality)
    {
        $success      = 0;
        $failed       = 0;
        $skipped      = 0;
        $errors       = [];
        $affectedYears = [];

        foreach ($data as $index => $row) {
            try {
                $this->validateColumns($row, ['Municipality', 'Program', 'Year', 'Beneficiaries']);

                if (trim($row['Municipality']) !== $adminMunicipality) {
                    throw new \Exception("You can only import data for {$adminMunicipality}");
                }

                $year        = (int)   $row['Year'];
                $programType = trim($row['Program']);
                $month       = isset($row['Month']) && $row['Month'] !== '' ? (int)$row['Month'] : null;

                // ── Duplicate guard ───────────────────────────────────────────
                $alreadyExists = SocialWelfareProgram::where('municipality', $adminMunicipality)
                    ->where('program_type', $programType)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->exists();

                if ($alreadyExists) {
                    $skipped++;
                    $errors[] = "Row " . ($index + 2) . ": {$programType} ({$year}" . ($month ? "/month {$month}" : "") . ") already exists — skipped.";
                    continue;
                }

                SocialWelfareProgram::create([
                    'municipality'     => $adminMunicipality,
                    'program_type'     => $programType,
                    'year'             => $year,
                    'beneficiary_count'=> (int) $row['Beneficiaries'],
                    'barangay'         => isset($row['Barangay']) && $row['Barangay'] !== '' ? $row['Barangay'] : null,
                    'month'            => $month,
                ]);

                $affectedYears[$year] = true;
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        // ── Sync MunicipalityYearlySummary for all imported years ────────────
        foreach (array_keys($affectedYears) as $year) {
            $this->syncYearlySummaryProgramTotals($adminMunicipality, $year);
        }

        return compact('success', 'failed', 'skipped', 'errors');
    }

    /**
     * Recompute program totals in MunicipalityYearlySummary for a given municipality + year.
     * This keeps /admin/data/yearly and /analysis/programs in sync after program CSV imports.
     */
    private function syncYearlySummaryProgramTotals($municipality, $year)
    {
        $programs = SocialWelfareProgram::where('municipality', $municipality)
            ->where('year', $year)
            ->get();

        $totals = [
            'total_4ps'         => 0,
            'total_pwd'         => 0,
            'total_senior'      => 0,
            'total_aics'        => 0,
            'total_esa'         => 0,
            'total_slp'         => 0,
            'total_solo_parent' => 0,
        ];

        foreach ($programs as $prog) {
            $bc = (int) $prog->beneficiary_count;
            switch (strtolower($prog->program_type)) {
                case '4ps':                    $totals['total_4ps']         += $bc; break;
                case 'pwd_assistance':
                case 'pwd':                    $totals['total_pwd']         += $bc; break;
                case 'senior_citizen_pension':
                case 'senior':                 $totals['total_senior']      += $bc; break;
                case 'aics':                   $totals['total_aics']        += $bc; break;
                case 'esa':                    $totals['total_esa']         += $bc; break;
                case 'slp':                    $totals['total_slp']         += $bc; break;
                case 'solo_parent':
                case 'solo parent':            $totals['total_solo_parent'] += $bc; break;
            }
        }

        MunicipalityYearlySummary::updateOrCreate(
            ['municipality' => $municipality, 'year' => $year],
            array_merge($totals, ['updated_at' => now()])
        );
    }



    /**
     * Validate required columns exist
     */
    private function validateColumns($row, $requiredColumns)
    {
        foreach ($requiredColumns as $column) {
            if (!isset($row[$column]) || trim($row[$column]) === '') {
                throw new \Exception("Missing required column: {$column}");
            }
        }
    }

    /**
     * Export data to CSV
     * 
     * @param string $type
     * @param array $filters
     * @return string CSV file path
     */
    public function exportCsv($type, $filters = [])
    {
        $data = match($type) {
            'municipality_data' => $this->exportMunicipalityData($filters),
            'barangay_data' => $this->exportBarangayData($filters),
            'program_data' => $this->exportProgramData($filters),
            default => throw new \Exception('Invalid export type')
        };

        return $this->generateCsvFile($data, $type);
    }

    /**
     * Export Municipality Data
     */
    private function exportMunicipalityData($filters)
    {
        $query = Municipality::query();

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['municipality'])) {
            $query->where('name', $filters['municipality']);
        }

        $municipalities = $query->orderBy('year', 'desc')
            ->orderBy('name')
            ->get();

        $csvData = [
            ['Year', 'Municipality', 'Population', 'Male', 'Female', 'GrowthRate']
        ];

        foreach ($municipalities as $mun) {
            $csvData[] = [
                $mun->year,
                $mun->name,
                $mun->total_population,
                $mun->male_population,
                $mun->female_population,
                $mun->growth_rate ?? 0
            ];
        }

        return $csvData;
    }

    /**
     * Export Barangay Data
     */
    private function exportBarangayData($filters)
    {
        $query = Barangay::query();

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['municipality'])) {
            $query->where('municipality', $filters['municipality']);
        }

        $barangays = $query->orderBy('municipality')
            ->orderBy('name')
            ->get();

        $csvData = [
            ['Municipality', 'Barangay', 'Year', 'Male', 'Female', 'Age_0_19', 'Age_20_59', 'Age_60_100', 'Households']
        ];

        foreach ($barangays as $brgy) {
            $csvData[] = [
                $brgy->municipality,
                $brgy->name,
                $brgy->year,
                $brgy->male_population,
                $brgy->female_population,
                $brgy->population_0_19,
                $brgy->population_20_59,
                $brgy->population_60_100,
                $brgy->total_households
            ];
        }

        return $csvData;
    }

    /**
     * Export Program Data
     */
    private function exportProgramData($filters)
    {
        $query = SocialWelfareProgram::query();

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['municipality'])) {
            $query->where('municipality', $filters['municipality']);
        }

        $programs = $query->orderBy('municipality')
            ->orderBy('program_type')
            ->orderBy('year')
            ->get();

        $csvData = [
            ['Municipality', 'Program', 'Year', 'Beneficiaries', 'Barangay', 'Month']
        ];

        foreach ($programs as $prog) {
            $csvData[] = [
                $prog->municipality,
                $prog->program_type,
                $prog->year,
                $prog->beneficiary_count,
                $prog->barangay ?? '',
                $prog->month ?? ''
            ];
        }

        return $csvData;
    }

    /**
     * Generate CSV file from data array
     */
    private function generateCsvFile($data, $type)
    {
        $fileName = $type . '_' . date('Y-m-d_His') . '.csv';
        $filePath = storage_path('app/exports/' . $fileName);

        // Create exports directory if it doesn't exist
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $handle = fopen($filePath, 'w');

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return $filePath;
    }
}

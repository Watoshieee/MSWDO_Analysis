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
     * @param int|null $year Optional year filter for import
     * @return array
     */
    public function importCsv($file, $type, $year = null)
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
                'municipality_data' => $this->importMunicipalityData($csvData, $adminMunicipality, $year),
                'barangay_data' => $this->importBarangayData($csvData, $adminMunicipality, $year),
                'program_data' => $this->importProgramData($csvData, $adminMunicipality, $year),
                default => throw new \Exception('Invalid import type')
            };

            // Update import log
            $importLog->update([
                'successful_rows' => $result['success'],
                'failed_rows' => $result['failed'] + ($result['skipped'] ?? 0),
                'error_details' => !empty($result['errors']) ? json_encode($result['errors']) : null,
                'status' => 'completed'
            ]);



            $message = "Import completed: {$result['success']} successful";
            if (isset($result['skipped']) && $result['skipped'] > 0) {
                $message .= ", {$result['skipped']} skipped";
            }
            if ($result['failed'] > 0) {
                $message .= ", {$result['failed']} failed";
            }

            return [
                'success' => true,
                'message' => $message,
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
     * - If $filterYear is provided, only imports rows matching that year.
     */
    private function importMunicipalityData($data, $adminMunicipality, $filterYear = null)
    {
        $success = 0;
        $failed  = 0;
        $skipped = 0;
        $errors  = [];

        Log::info('Import Municipality Data', [
            'total_rows' => count($data),
            'municipality' => $adminMunicipality,
            'filter_year' => $filterYear
        ]);

        foreach ($data as $index => $row) {
            try {
                Log::info('Processing row ' . ($index + 2), $row);
                
                $this->validateColumns($row, ['Year', 'Municipality', 'Total_Population', 'Total_Households', 'Male', 'Female']);

                $rowMunicipality = trim($row['Municipality']);

                if ($rowMunicipality !== $adminMunicipality) {
                    $skipped++;
                    $errors[] = "Row " . ($index + 2) . ": Municipality mismatch (CSV: '{$rowMunicipality}', Expected: '{$adminMunicipality}')";
                    continue;
                }

                $year = (int) $row['Year'];

                if ($filterYear !== null && $filterYear !== '' && $year != $filterYear) {
                    $skipped++;
                    continue;
                }

                $alreadyExists = MunicipalityYearlySummary::where('municipality', $adminMunicipality)
                    ->where('year', $year)
                    ->exists();

                $totalPop  = (int)  $row['Total_Population'];
                $totalHouseholds = (int) $row['Total_Households'];
                $malePop   = (int)  $row['Male'];
                $femalePop = (int)  $row['Female'];
                $age0_19   = (int)  ($row['Age_0_19'] ?? 0);
                $age20_59  = (int)  ($row['Age_20_59'] ?? 0);
                $age60Plus = (int)  ($row['Age_60_Plus'] ?? 0);

                if ($alreadyExists) {
                    MunicipalityYearlySummary::where('municipality', $adminMunicipality)
                        ->where('year', $year)
                        ->update([
                            'total_population'  => $totalPop,
                            'total_households'  => $totalHouseholds,
                            'male_population'   => $malePop,
                            'female_population' => $femalePop,
                            'population_0_19'   => $age0_19,
                            'population_20_59'  => $age20_59,
                            'population_60_100' => $age60Plus,
                        ]);
                    
                    $success++;
                    continue;
                }

                MunicipalityYearlySummary::create([
                    'municipality'      => $adminMunicipality,
                    'year'              => $year,
                    'total_population'  => $totalPop,
                    'total_households'  => $totalHouseholds,
                    'male_population'   => $malePop,
                    'female_population' => $femalePop,
                    'population_0_19'   => $age0_19,
                    'population_20_59'  => $age20_59,
                    'population_60_100' => $age60Plus,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                $success++;
                Log::info('Row imported successfully', ['year' => $year]);
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Row import failed', ['error' => $e->getMessage(), 'row' => $row]);
            }
        }

        Log::info('Import completed', [
            'success' => $success,
            'failed' => $failed,
            'skipped' => $skipped
        ]);

        $message = "{$success} imported";
        if ($skipped) $message .= ", {$skipped} skipped";
        if ($failed)  $message .= ", {$failed} failed";

        return compact('success', 'failed', 'skipped', 'errors');
    }

    /**
     * Import Barangay Data
     * - Updates existing records if barangay + year already exists
     * - Creates new record if it doesn't exist
     * - If $filterYear is provided, only imports rows matching that year.
     */
    private function importBarangayData($data, $adminMunicipality, $filterYear = null)
    {
        $success = 0;
        $failed  = 0;
        $skipped = 0;
        $errors  = [];

        foreach ($data as $index => $row) {
            try {
                $this->validateColumns($row, ['Municipality', 'Barangay', 'Year', 'Total_Population']);

                if (trim($row['Municipality']) !== $adminMunicipality) {
                    throw new \Exception("You can only import data for {$adminMunicipality}");
                }

                $year     = (int) $row['Year'];
                $barangay = trim($row['Barangay']);

                // Filter by year if specified (empty string means import all)
                if ($filterYear !== null && $filterYear !== '' && $year != $filterYear) {
                    $skipped++;
                    continue;
                }

                // ── Update or Create ───────────────────────────────────────────
                $barangayData = [
                    'total_population'    => (int)($row['Total_Population'] ?? 0),
                    'pwd_count'           => (int)($row['PWD']         ?? 0),
                    'aics_count'          => (int)($row['AICS']        ?? 0),
                    'single_parent_count' => (int)($row['Solo_Parent'] ?? 0),
                    'total_households'    => (int)($row['Households']  ?? 0),
                    'four_ps_count'       => (int)($row['4Ps']         ?? 0),
                    'senior_count'        => (int)($row['Senior']      ?? 0),
                ];

                Barangay::updateOrCreate(
                    [
                        'municipality' => $adminMunicipality,
                        'name'         => $barangay,
                        'year'         => $year
                    ],
                    $barangayData
                );

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
     * - Updates existing records if municipality + program_type + year (+month) already exists.
     * - Creates new record if it doesn't exist.
     * - After import, refreshes MunicipalityYearlySummary program totals for affected years.
     * - If $filterYear is provided, only imports rows matching that year.
     */
    private function importProgramData($data, $adminMunicipality, $filterYear = null)
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
                $month       = null;

                // Filter by year if specified (empty string means import all)
                if ($filterYear !== null && $filterYear !== '' && $year != $filterYear) {
                    $skipped++;
                    continue;
                }

                // ── Update or Create ───────────────────────────────────────────
                SocialWelfareProgram::updateOrCreate(
                    [
                        'municipality'  => $adminMunicipality,
                        'program_type'  => $programType,
                        'year'          => $year,
                        'month'         => null,
                    ],
                    [
                        'beneficiary_count' => (int) $row['Beneficiaries'],
                        'barangay'          => null,
                    ]
                );

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
        $query = MunicipalityYearlySummary::query();

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['municipality'])) {
            $query->where('municipality', $filters['municipality']);
        }

        $summaries = $query->orderBy('year', 'desc')
            ->orderBy('municipality')
            ->get();

        // Match template format: Year, Municipality, Total_Population, Total_Households, Male, Female, Age_0_19, Age_20_59, Age_60_Plus
        $csvData = [
            ['Year', 'Municipality', 'Total_Population', 'Total_Households', 'Male', 'Female', 'Age_0_19', 'Age_20_59', 'Age_60_Plus']
        ];

        foreach ($summaries as $summary) {
            $csvData[] = [
                $summary->year,
                $summary->municipality,
                $summary->total_population ?? 0,
                $summary->total_households ?? 0,
                $summary->male_population ?? 0,
                $summary->female_population ?? 0,
                $summary->population_0_19 ?? 0,
                $summary->population_20_59 ?? 0,
                $summary->population_60_100 ?? 0
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
            ->orderBy('year', 'desc')
            ->orderBy('name')
            ->get();

        // Match template format: Municipality, Barangay, Year, Total_Population, PWD, AICS, Solo_Parent, Households, 4Ps, Senior
        $csvData = [
            ['Municipality', 'Barangay', 'Year', 'Total_Population', 'PWD', 'AICS', 'Solo_Parent', 'Households', '4Ps', 'Senior']
        ];

        foreach ($barangays as $brgy) {
            $csvData[] = [
                $brgy->municipality,
                $brgy->name,
                $brgy->year ?? date('Y'),
                $brgy->total_population ?? 0,
                $brgy->pwd_count ?? 0,
                $brgy->aics_count ?? 0,
                $brgy->single_parent_count ?? 0,
                $brgy->total_households ?? 0,
                $brgy->four_ps_count ?? 0,
                $brgy->senior_count ?? 0
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
            ->orderBy('year', 'desc')
            ->orderBy('program_type')
            ->get();

        // Match template format: Municipality, Program, Year, Beneficiaries
        $csvData = [
            ['Municipality', 'Program', 'Year', 'Beneficiaries']
        ];

        foreach ($programs as $prog) {
            $csvData[] = [
                $prog->municipality,
                $prog->program_type,
                $prog->year,
                $prog->beneficiary_count ?? 0
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

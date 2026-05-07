<?php

namespace App\Services;

use App\Models\CsvImportLog;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
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
     */
    private function importMunicipalityData($data, $adminMunicipality)
    {
        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Validate required columns
                $this->validateColumns($row, ['Year', 'Municipality', 'Population', 'Male', 'Female']);

                // Check if municipality matches admin's municipality
                if ($row['Municipality'] !== $adminMunicipality) {
                    throw new \Exception("You can only import data for {$adminMunicipality}");
                }

                // Update or create municipality record
                Municipality::updateOrCreate(
                    [
                        'name' => $row['Municipality'],
                        'year' => (int)$row['Year']
                    ],
                    [
                        'total_population' => (int)$row['Population'],
                        'male_population' => (int)$row['Male'],
                        'female_population' => (int)$row['Female'],
                        'growth_rate' => isset($row['GrowthRate']) ? (float)$row['GrowthRate'] : null
                    ]
                );

                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return compact('success', 'failed', 'errors');
    }

    /**
     * Import Barangay Data
     */
    private function importBarangayData($data, $adminMunicipality)
    {
        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Validate required columns
                $this->validateColumns($row, ['Municipality', 'Barangay', 'Year', 'Population']);

                // Check if municipality matches admin's municipality
                if ($row['Municipality'] !== $adminMunicipality) {
                    throw new \Exception("You can only import data for {$adminMunicipality}");
                }

                // Update or create barangay record
                Barangay::updateOrCreate(
                    [
                        'municipality' => $row['Municipality'],
                        'name' => $row['Barangay'],
                        'year' => (int)$row['Year']
                    ],
                    [
                        'male_population' => isset($row['Male']) ? (int)$row['Male'] : 0,
                        'female_population' => isset($row['Female']) ? (int)$row['Female'] : 0,
                        'population_0_19' => isset($row['Age_0_19']) ? (int)$row['Age_0_19'] : 0,
                        'population_20_59' => isset($row['Age_20_59']) ? (int)$row['Age_20_59'] : 0,
                        'population_60_100' => isset($row['Age_60_100']) ? (int)$row['Age_60_100'] : 0,
                        'total_households' => isset($row['Households']) ? (int)$row['Households'] : 0
                    ]
                );

                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return compact('success', 'failed', 'errors');
    }

    /**
     * Import Program Data
     */
    private function importProgramData($data, $adminMunicipality)
    {
        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Validate required columns
                $this->validateColumns($row, ['Municipality', 'Program', 'Year', 'Beneficiaries']);

                // Check if municipality matches admin's municipality
                if ($row['Municipality'] !== $adminMunicipality) {
                    throw new \Exception("You can only import data for {$adminMunicipality}");
                }

                // Update or create program record
                SocialWelfareProgram::updateOrCreate(
                    [
                        'municipality' => $row['Municipality'],
                        'program_type' => $row['Program'],
                        'year' => (int)$row['Year']
                    ],
                    [
                        'beneficiary_count' => (int)$row['Beneficiaries'],
                        'barangay' => isset($row['Barangay']) ? $row['Barangay'] : null,
                        'month' => isset($row['Month']) ? (int)$row['Month'] : null
                    ]
                );

                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return compact('success', 'failed', 'errors');
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

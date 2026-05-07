<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CsvService;
use App\Models\CsvImportLog;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CsvImportExportController extends Controller
{
    protected $csvService;

    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
    }

    /**
     * Show CSV Import/Export page
     */
    public function index()
    {
        $adminMunicipality = Auth::user()->municipality;
        
        $importLogs = CsvImportLog::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get all unique years from municipality, barangay, and program tables for this municipality
        $municipalityYears = Municipality::where('name', $adminMunicipality)
            ->select('year')
            ->distinct()
            ->pluck('year');
            
        $barangayYears = \App\Models\Barangay::where('municipality', $adminMunicipality)
            ->select('year')
            ->distinct()
            ->pluck('year');
            
        $programYears = \App\Models\SocialWelfareProgram::where('municipality', $adminMunicipality)
            ->select('year')
            ->distinct()
            ->pluck('year');
        
        // Merge all years and sort descending
        $years = $municipalityYears
            ->merge($barangayYears)
            ->merge($programYears)
            ->unique()
            ->sort()
            ->values()
            ->reverse()
            ->values();
        
        // If no years found in database, use default years for comparative analysis
        if ($years->isEmpty()) {
            $years = collect([2024, 2020, 2015]);
        }

        return view('admin.csv-import-export', compact('importLogs', 'years', 'adminMunicipality'));
    }

    /**
     * Handle CSV Import
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'import_type' => 'required|in:municipality_data,barangay_data,program_data'
        ]);

        try {
            $result = $this->csvService->importCsv(
                $request->file('csv_file'),
                $request->import_type
            );

            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle CSV Export
     */
    public function export(Request $request)
    {
        $request->validate([
            'export_type' => 'required|in:municipality_data,barangay_data,program_data',
            'year' => 'nullable|integer'
        ]);

        try {
            $adminMunicipality = Auth::user()->municipality;
            
            $filters = [
                'municipality' => $adminMunicipality
            ];
            
            if ($request->filled('year')) {
                $filters['year'] = $request->year;
            }

            $filePath = $this->csvService->exportCsv($request->export_type, $filters);

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Get import log details
     */
    public function getImportLog($id)
    {
        $log = CsvImportLog::with('user')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'log' => $log,
            'errors' => $log->error_details ? json_decode($log->error_details, true) : []
        ]);
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate($type)
    {
        $adminMunicipality = Auth::user()->municipality;
        
        $templates = [
            'municipality_data' => [
                ['Year', 'Municipality', 'Population', 'Male', 'Female', 'GrowthRate'],
                ['2024', $adminMunicipality, '45000', '22000', '23000', '2.3'],
                ['2020', $adminMunicipality, '42000', '20500', '21500', '2.1'],
                ['2015', $adminMunicipality, '38000', '19000', '19000', '1.9']
            ],
            'barangay_data' => [
                ['Municipality', 'Barangay', 'Year', 'Population', 'Male', 'Female', 'Age_0_19', 'Age_20_59', 'Age_60_100', 'Households'],
                [$adminMunicipality, 'Poblacion', '2024', '2500', '1200', '1300', '800', '1500', '200', '500'],
                [$adminMunicipality, 'Poblacion', '2020', '2300', '1100', '1200', '750', '1400', '150', '480'],
                [$adminMunicipality, 'San Isidro', '2024', '2000', '1000', '1000', '600', '1200', '200', '400']
            ],
            'program_data' => [
                ['Municipality', 'Program', 'Year', 'Beneficiaries', 'Barangay', 'Month'],
                [$adminMunicipality, 'PWD_Assistance', '2024', '150', 'Poblacion', '1'],
                [$adminMunicipality, 'PWD_Assistance', '2020', '120', '', ''],
                [$adminMunicipality, 'Solo_Parent', '2024', '200', '', ''],
                [$adminMunicipality, 'Solo_Parent', '2020', '180', '', ''],
                [$adminMunicipality, '4Ps', '2024', '300', '', ''],
                [$adminMunicipality, 'AICS', '2024', '250', '', '']
            ]
        ];

        if (!isset($templates[$type])) {
            return redirect()->back()->with('error', 'Invalid template type');
        }

        $fileName = $adminMunicipality . '_' . $type . '_template.csv';
        $filePath = storage_path('app/temp/' . $fileName);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $handle = fopen($filePath, 'w');
        foreach ($templates[$type] as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }
}

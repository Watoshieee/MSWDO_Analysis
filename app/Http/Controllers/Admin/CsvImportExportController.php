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
     * Handle CSV Import
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
            'import_type' => 'required|in:municipality_data,barangay_data,program_data',
            'year' => 'nullable|integer|min:2000|max:' . (date('Y') + 2)
        ]);

        try {
            $year = $request->filled('year') ? $request->year : null;
            
            $result = $this->csvService->importCsv(
                $request->file('csv_file'),
                $request->import_type,
                $year
            );

            if ($result['success']) {
                $redirectUrl = match($request->import_type) {
                    'municipality_data' => route('admin.data.municipality') . '#return',
                    'barangay_data' => route('admin.data.barangays') . '#return',
                    'program_data' => route('admin.data.programs') . '#return',
                    default => route('admin.data.municipality') . '#return'
                };
                return redirect($redirectUrl)->with('success', $result['message']);
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
     * Archive import log
     */
    public function archiveLog($id)
    {
        $log = CsvImportLog::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $log->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Import log archived successfully'
        ]);
    }

    /**
     * Restore archived import log
     */
    public function restoreLog($id)
    {
        $log = CsvImportLog::onlyTrashed()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $log->restore();
        
        return response()->json([
            'success' => true,
            'message' => 'Import log restored successfully'
        ]);
    }

    /**
     * Permanently delete archived import log
     */
    public function forceDeleteLog($id)
    {
        $log = CsvImportLog::onlyTrashed()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $log->forceDelete();
        
        return response()->json([
            'success' => true,
            'message' => 'Import log permanently deleted'
        ]);
    }

    /**
     * Get archived import logs
     */
    public function getArchivedLogs()
    {
        $archivedLogs = CsvImportLog::onlyTrashed()
            ->where('user_id', Auth::id())
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'logs' => $archivedLogs
        ]);
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate($type)
    {
        $adminMunicipality = Auth::user()->municipality;
        $currentYear = date('Y');
        
        // Get municipality's current year if available
        $municipality = Municipality::where('name', $adminMunicipality)->first();
        if ($municipality && $municipality->year) {
            $currentYear = $municipality->year;
        }
        
        $templates = [
            'municipality_data' => [
                ['Year', 'Municipality', 'Total_Population', 'Total_Households', 'Male', 'Female', 'Age_0_19', 'Age_20_59', 'Age_60_Plus'],
                [$currentYear, $adminMunicipality, '0', '0', '0', '0', '0', '0', '0'],
            ],
            'barangay_data' => $this->generateBarangayTemplate($adminMunicipality, $currentYear),
            'program_data' => [
                ['Municipality', 'Program', 'Year', 'Beneficiaries'],
                [$adminMunicipality, 'PWD_Assistance', $currentYear, '0'],
                [$adminMunicipality, 'Solo_Parent', $currentYear, '0'],
                [$adminMunicipality, '4Ps', $currentYear, '0'],
                [$adminMunicipality, 'AICS', $currentYear, '0'],
                [$adminMunicipality, 'Senior_Citizen_Pension', $currentYear, '0'],
                [$adminMunicipality, 'SLP', $currentYear, '0'],
                [$adminMunicipality, 'ESA', $currentYear, '0'],
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
    
    /**
     * Generate barangay template with actual barangays from the municipality
     */
    private function generateBarangayTemplate($municipality, $year)
    {
        $template = [
            ['Municipality', 'Barangay', 'Year', 'Total_Population', 'PWD', 'AICS', 'Solo_Parent', 'Households', '4Ps', 'Senior']
        ];
        
        // Get all unique barangay names for this municipality
        $barangays = \App\Models\Barangay::where('municipality', $municipality)
            ->select('name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name');
        
        // If no barangays found, add sample rows
        if ($barangays->isEmpty()) {
            $template[] = [$municipality, 'Poblacion', $year, '2500', '50', '30', '40', '500', '100', '80'];
            $template[] = [$municipality, 'San Isidro', $year, '2000', '40', '25', '35', '400', '80', '60'];
        } else {
            // Add a row for each barangay
            foreach ($barangays as $barangay) {
                $template[] = [$municipality, $barangay, $year, '0', '0', '0', '0', '0', '0', '0'];
            }
        }
        
        return $template;
    }
}

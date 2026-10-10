<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Services\Export\DataCsvExportService;
use App\Services\Export\DataExcelExportService;
use App\Services\SuperAdminCsvImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DataImportExportController extends Controller
{
    public function __construct(
        protected DataCsvExportService        $csvExporter,
        protected DataExcelExportService      $excelExporter,
        protected SuperAdminCsvImportService  $importer,
    ) {}

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    //  EXPORTS
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    /**
     * Export combined CSV (Municipality + Barangay + Social Programs).
     * Queries the live database every time â€” always reflects latest changes.
     * Route: GET|POST superadmin/data/export/csv
     */
    public function exportCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'nullable|string|max:100',
            'year'         => 'nullable|integer|min:1990|max:' . (date('Y') + 2),
            'category'     => 'nullable|string|in:all,municipality,barangay,programs',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $municipality = $request->filled('municipality') ? trim($request->municipality) : null;
            $year         = $request->filled('year') ? (int) $request->year : null;
            $category     = $request->input('category', 'all');

            // Verify municipality exists if provided
            if ($municipality && !Municipality::where('name', $municipality)->exists()) {
                return redirect()->back()->with('error', "Municipality '{$municipality}' not found.");
            }

            $filters = compact('municipality', 'year', 'category');

            $csvContent = $this->csvExporter->buildCombinedCsv($filters);
            $filename   = $this->csvExporter->buildFilename($municipality, $year, $category);

            return response($csvContent, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control'       => 'no-store, no-cache, must-revalidate',
                'Pragma'              => 'no-cache',
            ]);

        } catch (\Exception $e) {
            Log::error('SuperAdmin CSV Export Error', ['error' => $e->getMessage(), 'user' => Auth::id()]);
            return redirect()->back()->with('error', 'CSV export failed: ' . $e->getMessage());
        }
    }

    /**
     * Export professionally formatted Excel workbook (4 sheets).
     * Route: GET|POST superadmin/data/export/excel
     *
     * Uses streamDownload + ob_end_clean() to prevent any buffered PHP output
     * (notices, whitespace, BOM from included files) from being prepended to the
     * binary XLSX content — which causes Excel's "file format is not valid" error.
     */
    public function exportExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'nullable|string|max:100',
            'year'         => 'nullable|integer|min:1990|max:' . (date('Y') + 2),
            'category'     => 'nullable|string|in:all,municipality,barangay,programs',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $municipality = $request->filled('municipality') ? trim($request->municipality) : null;
            $year         = $request->filled('year') ? (int) $request->year : null;
            $category     = $request->input('category', 'all');

            if ($municipality && !Municipality::where('name', $municipality)->exists()) {
                return redirect()->back()->with('error', "Municipality '{$municipality}' not found.");
            }

            $filters  = compact('municipality', 'year', 'category');
            $xlsxPath = $this->excelExporter->generateWorkbook($filters);
            $filename = $this->excelExporter->buildFilename($municipality, $year);
            $fileSize = filesize($xlsxPath);

            // Clear ALL output buffers before streaming binary content.
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            return response()->streamDownload(function () use ($xlsxPath) {
                readfile($xlsxPath);
                @unlink($xlsxPath);
            }, $filename, [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Length'      => $fileSize,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ]);

        } catch (\Exception $e) {
            Log::error('SuperAdmin Excel Export Error', ['error' => $e->getMessage(), 'user' => Auth::id()]);
            return redirect()->back()->with('error', 'Excel export failed: ' . $e->getMessage());
        }
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    //  IMPORTS
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    /**
     * Handle CSV import for Municipality, Barangay, or Program data.
     * Route: POST superadmin/data/import
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file'    => 'required|file|max:10240',
            'import_type' => 'required|in:municipality_data,barangay_data,program_data',
            'import_mode' => 'nullable|in:skip,update',
            'year'        => 'nullable|integer|min:1990|max:2099',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $mode       = $request->input('import_mode', 'update');
            $filterYear = $request->filled('year') ? (int) $request->year : null;

            $result = $this->importer->importCsv(
                $request->file('csv_file'),
                $request->import_type,
                $mode,
                $filterYear
            );

            if ($request->wantsJson()) {
                return response()->json($result);
            }

            $session_key = $result['success'] ? 'import_result' : 'error';
            $redirect = redirect()->route('superadmin.data.dashboard');

            if ($result['success']) {
                return $redirect
                    ->with('import_result', $result)
                    ->with('success', $result['message']);
            } else {
                return $redirect->with('error', $result['message']);
            }

        } catch (\Exception $e) {
            Log::error('SuperAdmin Import Exception', ['error' => $e->getMessage(), 'user' => Auth::id()]);
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a CSV template for the given type.
     * Route: GET superadmin/data/import/template/{type}
     */
    public function downloadTemplate(string $type)
    {
        $validTypes = ['municipality_data', 'barangay_data', 'program_data'];
        if (!in_array($type, $validTypes, true)) {
            abort(404, 'Invalid template type.');
        }

        $municipality = Auth::user()->municipality ?: null;
        $content  = $this->importer->buildTemplate($type, $municipality);
        $filename = ($municipality ?: 'template') . "_{$type}_template.csv";

        return response($content, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Preview CSV before import (validate only, no write).
     * Route: POST superadmin/data/import/preview  (JSON)
     */
    public function previewImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file'    => 'required|file|max:10240',
            'import_type' => 'required|in:municipality_data,barangay_data,program_data',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Parse and return first 20 rows for preview â€” does NOT write to DB
            $file   = $request->file('csv_file');
            $handle = fopen($file->getRealPath(), 'r');

            // Strip BOM
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") rewind($handle);

            $header = fgetcsv($handle);
            if (!$header) {
                fclose($handle);
                return response()->json(['success' => false, 'message' => 'CSV is missing a header row.'], 422);
            }
            $header = array_map('trim', $header);

            $rows = [];
            $count = 0;
            while (($row = fgetcsv($handle)) !== false && $count < 20) {
                if (count($row) === count($header)) {
                    $rows[] = array_combine($header, array_map('trim', $row));
                    $count++;
                }
            }

            // Count total rows
            $totalRows = $count;
            while (fgetcsv($handle) !== false) {
                $totalRows++;
            }
            fclose($handle);

            return response()->json([
                'success'    => true,
                'headers'    => $header,
                'preview'    => $rows,
                'total_rows' => $totalRows,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

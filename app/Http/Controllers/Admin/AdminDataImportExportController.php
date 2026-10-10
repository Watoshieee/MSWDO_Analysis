<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Export\DataCsvExportService;
use App\Services\Export\DataExcelExportService;
use App\Services\SuperAdminCsvImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * AdminDataImportExportController
 *
 * Mirrors the SuperAdmin DataImportExportController but every operation is
 * automatically scoped to the currently authenticated admin's municipality.
 * The admin can only import/export data for their own municipality — they
 * cannot change the municipality filter.
 */
class AdminDataImportExportController extends Controller
{
    public function __construct(
        protected DataCsvExportService       $csvExporter,
        protected DataExcelExportService     $excelExporter,
        protected SuperAdminCsvImportService $importer,
    ) {}

    // ══════════════════════════════════════════════════════════════
    //  EXPORTS
    // ══════════════════════════════════════════════════════════════

    /**
     * Export combined CSV scoped to this admin's municipality.
     * Route: GET admin/data/export/data-csv
     */
    public function exportCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year'     => 'nullable|integer|min:1990|max:2099',
            'category' => 'nullable|string|in:all,municipality,barangay,programs',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $municipality = Auth::user()->municipality;
            $year         = $request->filled('year') ? (int) $request->year : null;
            $category     = $request->input('category', 'all');

            $filters    = compact('municipality', 'year', 'category');
            $csvContent = $this->csvExporter->buildCombinedCsv($filters);
            $filename   = $this->csvExporter->buildFilename($municipality, $year, $category);

            return response($csvContent, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control'       => 'no-store, no-cache, must-revalidate',
                'Pragma'              => 'no-cache',
            ]);

        } catch (\Exception $e) {
            Log::error('Admin CSV Export Error', ['error' => $e->getMessage(), 'user' => Auth::id()]);
            return redirect()->back()->with('error', 'CSV export failed: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel workbook scoped to this admin's municipality.
     * Route: GET admin/data/export/data-excel
     */
    public function exportExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year'     => 'nullable|integer|min:1990|max:2099',
            'category' => 'nullable|string|in:all,municipality,barangay,programs',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $municipality = Auth::user()->municipality;
            $year         = $request->filled('year') ? (int) $request->year : null;
            $category     = $request->input('category', 'all');

            $filters  = compact('municipality', 'year', 'category');
            $xlsxPath = $this->excelExporter->generateWorkbook($filters);
            $filename = $this->excelExporter->buildFilename($municipality, $year);
            $fileSize = filesize($xlsxPath);

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
            Log::error('Admin Excel Export Error', ['error' => $e->getMessage(), 'user' => Auth::id()]);
            return redirect()->back()->with('error', 'Excel export failed: ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  IMPORTS
    // ══════════════════════════════════════════════════════════════

    /**
     * Handle CSV import — forces municipality to the admin's own municipality.
     * Route: POST admin/data/import
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

            if ($result['success']) {
                return redirect()->route('admin.data.dashboard')
                    ->with('import_result', $result)
                    ->with('success', $result['message']);
            }
            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            Log::error('Admin Import Exception', ['error' => $e->getMessage(), 'user' => Auth::id()]);
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a CSV template scoped to admin's municipality.
     * Route: GET admin/data/import/template/{type}
     */
    public function downloadTemplate(string $type)
    {
        $validTypes = ['municipality_data', 'barangay_data', 'program_data'];
        if (!in_array($type, $validTypes, true)) {
            abort(404, 'Invalid template type.');
        }

        $municipality = Auth::user()->municipality;
        $content  = $this->importer->buildTemplate($type, $municipality);
        $filename = ($municipality ?: 'template') . "_{$type}_template.csv";

        return response($content, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Preview CSV before import (no DB write).
     * Route: POST admin/data/import/preview
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
            $file   = $request->file('csv_file');
            $handle = fopen($file->getRealPath(), 'r');

            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") rewind($handle);

            $header = fgetcsv($handle);
            if (!$header) {
                fclose($handle);
                return response()->json(['success' => false, 'message' => 'CSV is missing a header row.'], 422);
            }
            $header = array_map('trim', $header);

            $rows = []; $count = 0;
            while (($row = fgetcsv($handle)) !== false && $count < 20) {
                if (count($row) === count($header)) {
                    $rows[] = array_combine($header, array_map('trim', $row));
                    $count++;
                }
            }

            $totalRows = $count;
            while (fgetcsv($handle) !== false) { $totalRows++; }
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

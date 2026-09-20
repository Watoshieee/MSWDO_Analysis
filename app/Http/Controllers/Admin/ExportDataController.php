<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Export\AnalysisReportExcelService;
use App\Services\Export\DssWsmService;
use App\Services\Export\RawDataZipExportService;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ExportDataController extends Controller
{
    protected RawDataZipExportService $rawZipService;
    protected AnalysisReportExcelService $excelService;
    protected DssWsmService $dssService;

    public function __construct(
        RawDataZipExportService $rawZipService,
        AnalysisReportExcelService $excelService,
        DssWsmService $dssService
    ) {
        $this->rawZipService = $rawZipService;
        $this->excelService = $excelService;
        $this->dssService = $dssService;
    }

    /**
     * Resolve and validate authorized municipality.
     * Municipality Admins are strictly scoped to their assigned municipality.
     * Super Admins can specify any municipality.
     */
    protected function resolveAuthorizedMunicipality(Request $request): string
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if ($user->role === 'super_admin') {
            $requestedMuni = $request->input('municipality', $user->municipality);
            if (empty($requestedMuni)) {
                $requestedMuni = 'Magdalena';
            }
            return $requestedMuni;
        }

        // Municipality admin: MUST use assigned municipality only
        if (empty($user->municipality)) {
            abort(403, 'Your admin account has no municipality assigned.');
        }

        return $user->municipality;
    }

    /**
     * Resolve and sanitize year parameter.
     */
    protected function resolveYear(Request $request): ?int
    {
        $yearInput = $request->input('year');
        if ($yearInput === 'all' || empty($yearInput)) {
            return null;
        }
        return (int) $yearInput;
    }

    /**
     * Export Raw Data ZIP Package (CSVs, DSS/WSM, Graphs, README).
     */
    public function exportCsv(Request $request)
    {
        try {
            $municipality = $this->resolveAuthorizedMunicipality($request);
            $year = $this->resolveYear($request);

            $zipPath = $this->rawZipService->generateZip($municipality, $year);

            $muniSlug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $municipality));
            $yearSlug = $year ? (string) $year : 'all_years';
            $downloadFilename = "{$muniSlug}_export_{$yearSlug}.zip";

            return response()->download($zipPath, $downloadFilename, [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);

        } catch (Exception $e) {
            Log::error('Export CSV Error: ' . $e->getMessage(), [
                'user' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to generate CSV export: ' . $e->getMessage());
        }
    }

    /**
     * Export Analysis Report (.xlsx Excel Workbook with 10 worksheets and embedded charts).
     */
    public function exportAnalysisReport(Request $request)
    {
        try {
            $municipality = $this->resolveAuthorizedMunicipality($request);
            $year = $this->resolveYear($request);

            $excelPath = $this->excelService->generateReport($municipality, $year);

            $muniSlug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $municipality));
            $yearSlug = $year ? (string) $year : 'all_years';
            $downloadFilename = "{$muniSlug}_analysis_report_{$yearSlug}.xlsx";

            return response()->download($excelPath, $downloadFilename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (Exception $e) {
            Log::error('Export Analysis Report Error: ' . $e->getMessage(), [
                'user' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to generate Analysis Report: ' . $e->getMessage());
        }
    }

    /**
     * Comparative Analysis Export across Magdalena, Liliw, and Majayjay.
     */
    public function exportComparative(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                abort(403, 'Unauthorized');
            }

            $year = $this->resolveYear($request);
            $municipalities = ['Magdalena', 'Liliw', 'Majayjay'];
            $comparativeData = $this->dssService->computeComparative($municipalities, $year);

            // Generate Comparative CSV
            $handle = fopen('php://temp', 'r+');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Municipality',
                'Year',
                'Total Population',
                'Total Households',
                'Total Beneficiaries',
                'PWD Beneficiaries',
                'PWD Rate (%)',
                'AICS Recipients',
                'AICS Rate (%)',
                'Solo Parents',
                'Solo Parent Rate (%)',
                '4Ps Beneficiaries',
                '4Ps Rate (%)',
                'Senior Citizens',
                'Senior Rate (%)',
                'Household Size Ratio',
                'Average Calculated WSM Score'
            ]);

            foreach ($comparativeData as $c) {
                fputcsv($handle, [
                    $c['municipality'],
                    $c['year'],
                    $c['population'],
                    $c['households'],
                    $c['total_beneficiaries'],
                    $c['pwd'],
                    $c['pwd_rate'] !== null ? $c['pwd_rate'] . '%' : 'N/A',
                    $c['aics'],
                    $c['aics_rate'] !== null ? $c['aics_rate'] . '%' : 'N/A',
                    $c['solo_parent'],
                    $c['solo_parent_rate'] !== null ? $c['solo_parent_rate'] . '%' : 'N/A',
                    $c['four_ps'],
                    $c['four_ps_rate'] !== null ? $c['four_ps_rate'] . '%' : 'N/A',
                    $c['senior'],
                    $c['senior_rate'] !== null ? $c['senior_rate'] . '%' : 'N/A',
                    $c['household_size_ratio'] ?? 'N/A',
                    number_format($c['average_wsm_score'], 4),
                ]);
            }

            fputs($handle, "\n# " . config('dss.disclaimer') . "\n");

            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            $yearSlug = $year ? (string) $year : 'all_years';
            $filename = "comparative_analysis_export_{$yearSlug}.csv";

            return response($content, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);

        } catch (Exception $e) {
            Log::error('Export Comparative Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Comparative export failed: ' . $e->getMessage());
        }
    }
}

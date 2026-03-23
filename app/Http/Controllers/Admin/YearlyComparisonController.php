<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\MunicipalityYearlySummary;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YearlyComparisonController extends Controller
{
    /**
     * Display yearly comparison dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        // Get all available years for this municipality
        $years = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Get summary data for each year
        $yearlyData = [];
        foreach ($years as $year) {
            $data = MunicipalityYearlySummary::where('municipality', $user->municipality)
                ->where('year', $year)
                ->first();
                
            if ($data) {
                $yearlyData[$year] = [
                    'total_population' => $data->total_population,
                    'total_households' => $data->total_households,
                    'total_4ps' => $data->total_4ps,
                    'total_pwd' => $data->total_pwd,
                    'total_senior' => $data->total_senior,
                    'total_aics' => $data->total_aics,
                    'total_esa' => $data->total_esa,
                    'total_slp' => $data->total_slp,
                    'total_solo_parent' => $data->total_solo_parent,
                ];
            }
        }
        
        // Calculate trends (compare with previous year)
        $trends = $this->calculateTrends($yearlyData);
        
        return view('admin.yearly.index', compact(
            'municipality',
            'years',
            'yearlyData',
            'trends'
        ));
    }

    /**
     * View data for a specific year
     */
    public function viewYear($year)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        // Get data for the selected year
        $yearlyData = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', $year)
            ->firstOrFail();
        
        // Get barangay data for that year (if available)
        $barangays = Barangay::where('municipality', $user->municipality)
            ->where('year', $year)
            ->get();
        
        // Get program data for that year
        $programs = SocialWelfareProgram::where('municipality', $user->municipality)
            ->where('year', $year)
            ->get()
            ->groupBy('program_type');
        
        // Get previous and next years for navigation
        $prevYear = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', '<', $year)
            ->orderBy('year', 'desc')
            ->first();
            
        $nextYear = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', '>', $year)
            ->orderBy('year', 'asc')
            ->first();
        
        return view('admin.yearly.view', compact(
            'municipality',
            'year',
            'yearlyData',
            'barangays',
            'programs',
            'prevYear',
            'nextYear'
        ));
    }

    /**
     * Compare multiple years
     */
    public function compare(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        // Get selected years from request (default to last 3 years)
        $selectedYears = $request->input('years', []);
        
        $allYears = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        if (empty($selectedYears)) {
            // Default to last 3 years
            $selectedYears = array_slice($allYears, 0, 3);
        }
        
        // Get data for selected years
        $comparisonData = [];
        foreach ($selectedYears as $year) {
            $data = MunicipalityYearlySummary::where('municipality', $user->municipality)
                ->where('year', $year)
                ->first();
                
            if ($data) {
                $comparisonData[$year] = [
                    'total_population' => $data->total_population,
                    'total_households' => $data->total_households,
                    'total_4ps' => $data->total_4ps,
                    'total_pwd' => $data->total_pwd,
                    'total_senior' => $data->total_senior,
                    'total_aics' => $data->total_aics,
                    'total_esa' => $data->total_esa,
                    'total_slp' => $data->total_slp,
                    'total_solo_parent' => $data->total_solo_parent,
                ];
            }
        }
        
        return view('admin.yearly.compare', compact(
            'municipality',
            'allYears',
            'selectedYears',
            'comparisonData'
        ));
    }

    /**
     * Calculate year-over-year trends
     */
    private function calculateTrends($yearlyData)
    {
        $trends = [];
        $years = array_keys($yearlyData);
        sort($years);
        
        for ($i = 1; $i < count($years); $i++) {
            $currentYear = $years[$i];
            $previousYear = $years[$i - 1];
            
            $current = $yearlyData[$currentYear];
            $previous = $yearlyData[$previousYear];
            
            $trends[$currentYear] = [
                'population_change' => $current['total_population'] - $previous['total_population'],
                'population_percent' => $previous['total_population'] > 0 
                    ? round(($current['total_population'] - $previous['total_population']) / $previous['total_population'] * 100, 1)
                    : 0,
                'households_change' => $current['total_households'] - $previous['total_households'],
                'households_percent' => $previous['total_households'] > 0
                    ? round(($current['total_households'] - $previous['total_households']) / $previous['total_households'] * 100, 1)
                    : 0,
            ];
        }
        
        return $trends;
    }
}
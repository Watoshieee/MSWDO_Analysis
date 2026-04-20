<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
use App\Models\MunicipalityMonthlySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DataManagementController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();

        // Use current year (2024) data
        $currentYear = 2024;
        
        // Get yearly summary for current year
        $currentSummary = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', $currentYear)
            ->first();

        // Use current year summary if available, otherwise fall back to latest
        if (!$currentSummary) {
            $currentSummary = MunicipalityYearlySummary::where('municipality', $user->municipality)
                ->orderBy('year', 'desc')
                ->first();
        }

        // Total population from current year summary
        $totalPopulation = $currentSummary->total_population ?? 0;

        // Count unique barangay names (not per year)
        $barangays = Barangay::where('municipality', $user->municipality)
            ->distinct('name')
            ->count('name');
        
        // Programs count (all years)
        $programs = SocialWelfareProgram::where('municipality', $user->municipality)->count();
        
        // Beneficiaries from current year only
        $beneficiaries = SocialWelfareProgram::where('municipality', $user->municipality)
            ->where('year', $currentYear)
            ->sum('beneficiary_count');

        return view('admin.data.dashboard', compact(
            'municipality',
            'barangays',
            'programs',
            'beneficiaries',
            'totalPopulation'
        ));
    }

    public function municipality(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->firstOrFail();

        // Get available years from yearly summaries + default range
        $savedYears = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        $defaultYears = array_merge([2015, 2020, 2024], range(date('Y') - 1, date('Y') + 1));
        $years = array_unique(array_merge($savedYears, $defaultYears));
        rsort($years);

        // Get year from request or use municipality's stored year
        $currentYear = $request->filled('year') ? $request->year : ($municipality->year ?? date('Y'));
        
        $currentSummary = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', $currentYear)
            ->first();

        // If the stored year has no summary, fall back to the most recent available
        if (!$currentSummary && !$request->filled('year')) {
            $currentSummary = MunicipalityYearlySummary::where('municipality', $user->municipality)
                ->orderBy('year', 'desc')
                ->first();

            if ($currentSummary) {
                $municipality->year = $currentSummary->year;
                $municipality->saveQuietly();
                $currentYear = $currentSummary->year;
            }
        }

        // Auto-calculate from barangay data for the selected year
        $barangayData = Barangay::where('municipality', $user->municipality)
            ->where('year', $currentYear)
            ->get();
        
        $autoTotalPopulation = $barangayData->sum('total_population');
        $autoTotalHouseholds = $barangayData->sum('total_households');

        // Use auto-calculated values if available, otherwise use summary values
        $currentTotalPopulation = $autoTotalPopulation > 0 ? $autoTotalPopulation : ($currentSummary->total_population ?? 0);
        $currentTotalHouseholds = $autoTotalHouseholds > 0 ? $autoTotalHouseholds : ($currentSummary->total_households ?? 0);

        // Update municipality year if changed via request
        if ($request->filled('year') && $municipality->year != $currentYear) {
            $municipality->year = $currentYear;
            $municipality->saveQuietly();
        }

        // Full summary objects for the Yearly History tab table
        $allSummaries = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->get();

        $yearlyData = [];
        foreach ($savedYears as $year) {
            $data = MunicipalityYearlySummary::where('municipality', $user->municipality)
                ->where('year', $year)
                ->first();
            if ($data) {
                $yearlyData[$year] = [
                    'total_population' => $data->total_population,
                    'total_households' => $data->total_households,
                ];
            }
        }

        $trends = $this->calculateYearlyTrends($yearlyData);

        return view('admin.data.municipality', compact(
            'municipality',
            'currentTotalPopulation',
            'currentTotalHouseholds',
            'years',
            'allSummaries',
            'yearlyData',
            'trends'
        ));
    }

    public function updateMunicipality(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'total_population'    => 'required|integer|min:0',
            'total_households'    => 'required|integer|min:0',
            'year'                => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $totalPopulation = (int) $request->total_population;

        // Update the municipalities table (current live record)
        $municipality->update([
            'total_households'    => $request->total_households,
            'year'                => $request->year,
        ]);

        // Mirror into the yearly summary
        MunicipalityYearlySummary::updateOrCreate(
            ['municipality' => $municipality->name, 'year' => $request->year],
            [
                'total_population'  => $totalPopulation,
                'total_households'  => $request->total_households,
                'created_at'        => now(),
            ]
        );

        return redirect()->route('admin.data.municipality')
            ->with('success', 'Municipality data updated successfully!');
    }

    private function saveYearlySummaryRecord($municipality, $year, $demographics, $programs)
    {
        $summary = MunicipalityYearlySummary::where('municipality', $municipality)
            ->where('year', $year)
            ->first();

        if ($summary) {
            $summary->update([
                'total_population' => $demographics['total_population'],
                'total_households' => $demographics['total_households'],
                'total_4ps' => $programs['total_4ps'],
                'total_pwd' => $programs['total_pwd'],
                'total_senior' => $programs['total_senior'],
                'total_aics' => $programs['total_aics'],
                'total_esa' => $programs['total_esa'],
                'total_slp' => $programs['total_slp'],
                'total_solo_parent' => $programs['total_solo_parent'],
                'created_at' => now(),
            ]);
        } else {
            MunicipalityYearlySummary::create([
                'municipality' => $municipality,
                'year' => $year,
                'total_population' => $demographics['total_population'],
                'total_households' => $demographics['total_households'],
                'total_4ps' => $programs['total_4ps'],
                'total_pwd' => $programs['total_pwd'],
                'total_senior' => $programs['total_senior'],
                'total_aics' => $programs['total_aics'],
                'total_esa' => $programs['total_esa'],
                'total_slp' => $programs['total_slp'],
                'total_solo_parent' => $programs['total_solo_parent'],
                'created_at' => now(),
            ]);
        }
    }

    private function calculateYearlyTrends($yearlyData)
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
            ];
        }
        
        return $trends;
    }

    public function barangays(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();

        // Get available years from yearly summaries + default range
        $savedYears = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        $defaultYears = array_merge([2015, 2020, 2024], range(date('Y') - 1, date('Y') + 1));
        $years = array_unique(array_merge($savedYears, $defaultYears));
        rsort($years);
        
        // Get available years that have barangay data
        $availableYears = Barangay::where('municipality', $user->municipality)
            ->distinct()->orderByDesc('year')->pluck('year')->toArray();
        
        // Default to latest year if no filter is applied
        $selectedYear = $request->filled('year') ? $request->year : (!empty($availableYears) ? $availableYears[0] : null);

        $query = Barangay::where('municipality', $user->municipality);
        if ($selectedYear) {
            $query->where('year', $selectedYear);
        }

        $barangays = $query->orderBy('name')->orderBy('year', 'desc')->get();
        
        // Count unique barangays
        $uniqueBarangayCount = Barangay::where('municipality', $user->municipality)
            ->distinct('name')
            ->count('name');

        return view('admin.data.barangays', compact('barangays', 'municipality', 'years', 'availableYears', 'uniqueBarangayCount', 'selectedYear'));
    }

    public function updateBarangay(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $barangay = Barangay::where('id', $id)
                ->where('municipality', $user->municipality)
                ->first();

            if (!$barangay) {
                return response()->json(['success' => false, 'message' => 'Barangay not found.'], 404);
            }

            $barangay->update([
                'total_population'    => intval($request->total_population ?? 0),
                'single_parent_count' => intval($request->single_parent_count ?? 0),
                'pwd_count'           => intval($request->pwd_count ?? 0),
                'aics_count'          => intval($request->aics_count ?? 0),
                'four_ps_count'       => intval($request->four_ps_count ?? 0),
                'senior_count'        => intval($request->senior_count ?? 0),
                'total_households'    => intval($request->total_households ?? 0),
                'year'                => intval($request->year ?? date('Y')),
            ]);

            // Auto-update MunicipalityYearlySummary
            $this->updateYearlySummaryFromBarangays($user->municipality, $barangay->year);
            
            // Auto-sync to SocialWelfareProgram
            $this->syncBarangayYearToPrograms($user->municipality, $barangay->year);

            return response()->json(['success' => true, 'message' => 'Barangay updated successfully!']);

        } catch (\Exception $e) {
            Log::error('Barangay update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function bulkUpdateBarangays(Request $request)
    {
        $user = Auth::user();
        $rows = $request->input('rows', []);
        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'No data received.'], 422);
        }

        $updated = 0;
        $yearsToUpdate = [];
        foreach ($rows as $row) {
            $barangay = Barangay::where('id', $row['id'] ?? null)
                ->where('municipality', $user->municipality)
                ->first();
            if (!$barangay) continue;
            $barangay->update([
                'total_population'    => intval($row['total_population'] ?? 0),
                'single_parent_count' => intval($row['single_parent_count'] ?? 0),
                'pwd_count'           => intval($row['pwd_count'] ?? 0),
                'aics_count'          => intval($row['aics_count'] ?? 0),
                'four_ps_count'       => intval($row['four_ps_count'] ?? 0),
                'senior_count'        => intval($row['senior_count'] ?? 0),
                'total_households'    => intval($row['total_households'] ?? 0),
                'year'                => intval($row['year'] ?? date('Y')),
            ]);
            $yearsToUpdate[$row['year']] = true;
            $updated++;
        }

        // Auto-update MunicipalityYearlySummary for all affected years
        foreach (array_keys($yearsToUpdate) as $year) {
            $this->updateYearlySummaryFromBarangays($user->municipality, $year);
            // Auto-sync to SocialWelfareProgram
            $this->syncBarangayYearToPrograms($user->municipality, $year);
        }

        return response()->json(['success' => true, 'updated' => $updated,
            'message' => "{$updated} barangay record(s) updated successfully!"]);
    }

    public function bulkStoreBarangays(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'year'      => 'required|integer',
            'barangays' => 'required|array|min:1',
            'barangays.*' => 'required|string',
        ]);

        $created = 0; $restored = 0;
        foreach ($request->barangays as $barangayName) {
            $name = trim($barangayName);
            $existing = Barangay::withTrashed()
                ->where('municipality', $user->municipality)
                ->where('name', $name)
                ->where('year', $request->year)
                ->first();

            if ($existing) {
                if ($existing->trashed()) { $existing->restore(); $restored++; }
            } else {
                Barangay::create([
                    'municipality'               => $user->municipality,
                    'name'                       => $name,
                    'year'                       => $request->year,
                    'total_population'           => 0,
                    'single_parent_count'        => 0,
                    'pwd_count'                  => 0,
                    'aics_count'                 => 0,
                    'four_ps_count'              => 0,
                    'senior_count'               => 0,
                    'total_households'           => 0,
                    'total_approved_applications'=> 0,
                ]);
                $created++;
            }
        }

        $msg = "{$created} barangay record(s) added";
        if ($restored) $msg .= ", {$restored} archived record(s) restored";
        $msg .= " for {$user->municipality} ({$request->year}).";

        return response()->json(['success' => true, 'created' => $created, 'restored' => $restored, 'message' => $msg]);
    }

    public function findOrCreateBarangay(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'municipality' => 'required|string',
                'year' => 'required|integer'
            ]);

            Log::info('Finding/Creating barangay: ' . $request->name . ' for year ' . $request->year);

            // Check if record exists
            $barangay = Barangay::where('name', $request->name)
                ->where('municipality', $request->municipality)
                ->where('year', $request->year)
                ->first();

            if ($barangay) {
                return response()->json([
                    'success' => true,
                    'barangay_id' => $barangay->id,
                    'message' => 'Existing barangay found'
                ]);
            }

            // Get template from any existing year
            $template = Barangay::where('name', $request->name)
                ->where('municipality', $request->municipality)
                ->first();

            // Create new record
            $newBarangay = Barangay::create([
                'municipality' => $request->municipality,
                'name' => $request->name,
                'year' => $request->year,
                'total_population'           => 0,
                'population_0_19' => 0,
                'population_20_59' => 0,
                'population_60_100' => 0,
                'single_parent_count' => 0,
                'total_households' => 0,
                'total_approved_applications' => 0,
            ]);

            Log::info('Created new barangay with ID: ' . $newBarangay->id);

            return response()->json([
                'success' => true,
                'barangay_id' => $newBarangay->id,
                'message' => 'New barangay record created'
            ]);

        } catch (\Exception $e) {
            Log::error('Find or create error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function programs(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        $programTypes = [
            '4Ps' => '4Ps',
            'Senior_Citizen_Pension' => 'Senior Citizen Pension',
            'PWD_Assistance' => 'PWD Assistance',
            'AICS' => 'AICS',
            'SLP' => 'SLP',
            'ESA' => 'ESA',
            'Solo_Parent' => 'Solo Parent'
        ];
        
        // Get available years from yearly summaries + default range
        $savedYears = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        $defaultYears = array_merge([2015, 2020, 2024], range(date('Y') - 1, date('Y') + 1));
        $years = array_unique(array_merge($savedYears, $defaultYears));
        rsort($years);
        
        // Fetch from SocialWelfareProgram table
        $query = SocialWelfareProgram::where('municipality', $user->municipality);
        
        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        $programs = $query->orderBy('year', 'desc')->paginate(20);
        
        // Get barangay breakdown for each program
        $fieldMapping = [
            'PWD_Assistance' => 'pwd_count',
            'AICS' => 'aics_count',
            '4Ps' => 'four_ps_count',
            'Senior_Citizen_Pension' => 'senior_count',
            'Solo_Parent' => 'single_parent_count',
        ];
        
        $barangayBreakdown = [];
        foreach ($programs as $program) {
            $field = $fieldMapping[$program->program_type] ?? null;
            if ($field) {
                $barangays = Barangay::where('municipality', $user->municipality)
                    ->where('year', $program->year)
                    ->where($field, '>', 0)  // Only get barangays with count > 0
                    ->get();
                
                $barangayBreakdown[$program->id] = $barangays->map(function($b) use ($field) {
                    return [
                        'id' => $b->id,
                        'name' => $b->name,
                        'count' => $b->$field ?? 0,
                    ];
                });
            }
        }
        
        return view('admin.data.programs', compact('programs', 'programTypes', 'years', 'municipality', 'barangayBreakdown'));
    }

    public function updateProgram(Request $request, $id)
    {
        $user = Auth::user();
        $program = SocialWelfareProgram::where('id', $id)
            ->where('municipality', $user->municipality)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'beneficiary_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'barangay_data' => 'nullable|array',
            'barangay_data.*.id' => 'required|integer',
            'barangay_data.*.count' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update program record
        $program->update([
            'beneficiary_count' => $request->beneficiary_count,
            'year' => $request->year,
        ]);
        
        // Update barangay data if provided
        if ($request->has('barangay_data')) {
            $fieldMapping = [
                'PWD_Assistance' => 'pwd_count',
                'AICS' => 'aics_count',
                '4Ps' => 'four_ps_count',
                'Senior_Citizen_Pension' => 'senior_count',
                'Solo_Parent' => 'single_parent_count',
            ];
            
            $field = $fieldMapping[$program->program_type] ?? null;
            if ($field) {
                foreach ($request->barangay_data as $data) {
                    $barangay = Barangay::find($data['id']);
                    if ($barangay && $barangay->municipality === $user->municipality) {
                        $barangay->update([
                            $field => $data['count']
                        ]);
                    }
                }
                
                // Re-sync to SocialWelfareProgram
                $this->syncBarangayYearToPrograms($user->municipality, $request->year);
            }
        }

        return redirect()->back()->with('success', 'Program data updated successfully!');
    }

    public function createProgram(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'program_type' => 'required|in:4Ps,Senior_Citizen_Pension,PWD_Assistance,AICS,SLP,ESA,Solo_Parent',
            'beneficiary_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $existing = SocialWelfareProgram::where('municipality', $user->municipality)
            ->where('program_type', $request->program_type)
            ->where('year', $request->year)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withErrors(['error' => 'This program data already exists for this year.'])
                ->withInput();
        }

        SocialWelfareProgram::create([
            'municipality' => $user->municipality,
            'program_type' => $request->program_type,
            'beneficiary_count' => $request->beneficiary_count,
            'year' => $request->year,
            'barangay' => null,
        ]);

        return redirect()->back()->with('success', 'Program data created successfully!');
    }

    public function deleteProgram($id)
    {
        $user = Auth::user();
        $program = SocialWelfareProgram::where('id', $id)
            ->where('municipality', $user->municipality)
            ->firstOrFail();
            
        $program->delete();
        
        return redirect()->back()->with('success', 'Program data deleted successfully!');
    }

    // ─── Municipality Yearly Data ────────────────────────────────────────────

    public function yearlyData()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->firstOrFail();

        $summaries = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->get();

        $chartData = [];
        $rows = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year')
            ->get();
        $chartData[$user->municipality] = [
            'years'      => $rows->pluck('year')->toArray(),
            'population' => $rows->pluck('total_population')->toArray(),
            'households' => $rows->pluck('total_households')->toArray(),
        ];

        // Get available years from yearly summaries + default range
        $savedYears = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        $defaultYears = array_merge([2015, 2020, 2024], range(date('Y') - 1, date('Y') + 1));
        $years = array_unique(array_merge($savedYears, $defaultYears));
        rsort($years);

        // Load admin colors directly
        $adminPrimaryColor = \App\Models\AdminSetting::where('user_id', $user->id)->where('setting_key', 'primary_color')->value('setting_value') ?? '#2C3E8F';
        $adminSecondaryColor = \App\Models\AdminSetting::where('user_id', $user->id)->where('setting_key', 'secondary_color')->value('setting_value') ?? '#FDB913';
        $adminAccentColor = \App\Models\AdminSetting::where('user_id', $user->id)->where('setting_key', 'accent_color')->value('setting_value') ?? '#C41E24';

        $archivedSummaries = MunicipalityYearlySummary::onlyTrashed()
            ->where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->get();

        return view('admin.data.yearly-data', compact('municipality', 'summaries', 'archivedSummaries', 'chartData', 'years', 'adminPrimaryColor', 'adminSecondaryColor', 'adminAccentColor'));
    }

    public function saveYearlySummary(Request $request)
    {
        $user = Auth::user();
        $muniName = $user->municipality;

        $validator = Validator::make($request->all(), [
            'year'              => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'total_population'  => 'required|integer|min:0',
            'male_population'   => 'nullable|integer|min:0',
            'female_population' => 'nullable|integer|min:0',
            'population_0_19'   => 'nullable|integer|min:0',
            'population_20_59'  => 'nullable|integer|min:0',
            'population_60_100' => 'nullable|integer|min:0',
            'total_households'  => 'required|integer|min:0',
            'total_pwd'         => 'nullable|integer|min:0',
            'total_aics'        => 'nullable|integer|min:0',
            'total_solo_parent' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        MunicipalityYearlySummary::updateOrCreate(
            [
                'municipality' => $muniName,
                'year'         => $request->year,
            ],
            [
                'total_population'  => $request->total_population,
                'male_population'   => $request->male_population   ?? 0,
                'female_population' => $request->female_population ?? 0,
                'population_0_19'   => $request->population_0_19   ?? 0,
                'population_20_59'  => $request->population_20_59  ?? 0,
                'population_60_100' => $request->population_60_100 ?? 0,
                'total_households'  => $request->total_households,
                'total_4ps'         => $request->total_4ps         ?? 0,
                'total_pwd'         => $request->total_pwd         ?? 0,
                'total_senior'      => $request->total_senior      ?? 0,
                'total_aics'        => $request->total_aics        ?? 0,
                'total_esa'         => $request->total_esa         ?? 0,
                'total_slp'         => $request->total_slp         ?? 0,
                'total_solo_parent' => $request->total_solo_parent ?? 0,
                'created_at'        => now(),
            ]
        );

        // Mirror back to municipalities table so /admin/data/municipality stays in sync
        $municipality = Municipality::where('name', $muniName)->first();
        if ($municipality) {
            $municipality->update([
                'male_population'   => $request->male_population   ?? $municipality->male_population,
                'female_population' => $request->female_population ?? $municipality->female_population,
                'population_0_19'   => $request->population_0_19   ?? $municipality->population_0_19,
                'population_20_59'  => $request->population_20_59  ?? $municipality->population_20_59,
                'population_60_100' => $request->population_60_100 ?? $municipality->population_60_100,
                'total_households'  => $request->total_households,
                'year'              => $request->year,
            ]);
        }

        return redirect()->route('admin.data.yearly')
            ->with('success', "{$muniName} ({$request->year}) yearly data saved successfully!");
    }

    public function deleteYearlySummary($id)
    {
        $user = Auth::user();
        $summary = MunicipalityYearlySummary::where('id', $id)
            ->where('municipality', $user->municipality)
            ->firstOrFail();
        $summary->delete(); // soft delete
        return redirect()->route('admin.data.yearly')
            ->with('success', 'Yearly record archived successfully.');
    }

    public function archiveYearlySummary($id)
    {
        $user = Auth::user();
        $summary = MunicipalityYearlySummary::where('id', $id)
            ->where('municipality', $user->municipality)
            ->firstOrFail();
        $summary->delete(); // soft delete → moves to archive
        return redirect()->route('admin.data.yearly')
            ->with('success', "Year {$summary->year} record archived successfully.");
    }

    public function restoreYearlySummary($id)
    {
        $user = Auth::user();
        $summary = MunicipalityYearlySummary::onlyTrashed()
            ->where('id', $id)
            ->where('municipality', $user->municipality)
            ->firstOrFail();
        $summary->restore();
        return redirect()->route('admin.data.yearly')
            ->with('success', "Year {$summary->year} record restored successfully.");
    }

    public function forceDeleteYearlySummary($id)
    {
        $user = Auth::user();
        $summary = MunicipalityYearlySummary::onlyTrashed()
            ->where('id', $id)
            ->where('municipality', $user->municipality)
            ->firstOrFail();
        $summary->forceDelete();
        return redirect()->route('admin.data.yearly')
            ->with('success', "Year {$summary->year} record permanently deleted.");
    }

    // Helper function to auto-update yearly summary from barangay data
    private function updateYearlySummaryFromBarangays($municipality, $year)
    {
        $barangays = Barangay::where('municipality', $municipality)
            ->where('year', $year)
            ->get();

        $totalPopulation = $barangays->sum('total_population');
        $totalHouseholds = $barangays->sum('total_households');
        $totalPwd = $barangays->sum('pwd_count');
        $totalAics = $barangays->sum('aics_count');
        $totalSoloParent = $barangays->sum('single_parent_count');
        $total4ps = $barangays->sum('four_ps_count');
        $totalSenior = $barangays->sum('senior_count');

        MunicipalityYearlySummary::updateOrCreate(
            ['municipality' => $municipality, 'year' => $year],
            [
                'total_population' => $totalPopulation,
                'total_households' => $totalHouseholds,
                'total_pwd' => $totalPwd,
                'total_aics' => $totalAics,
                'total_solo_parent' => $totalSoloParent,
                'total_4ps' => $total4ps,
                'total_senior' => $totalSenior,
                'created_at' => now(),
            ]
        );
    }
    
    // Helper function to sync barangay data for a specific year to SocialWelfareProgram table
    private function syncBarangayYearToPrograms($municipality, $year)
    {
        // Get barangay data for this year
        $barangayData = Barangay::where('municipality', $municipality)
            ->where('year', $year)
            ->get();
        
        // Map barangay fields to program types
        $fieldMapping = [
            'PWD_Assistance' => 'pwd_count',
            'AICS' => 'aics_count',
            '4Ps' => 'four_ps_count',
            'Senior_Citizen_Pension' => 'senior_count',
            'Solo_Parent' => 'single_parent_count',
        ];
        
        // Aggregate by program type
        $aggregated = [];
        foreach ($barangayData as $barangay) {
            foreach ($fieldMapping as $programType => $field) {
                if (!isset($aggregated[$programType])) {
                    $aggregated[$programType] = 0;
                }
                $aggregated[$programType] += $barangay->$field ?? 0;
            }
        }
        
        // Update SocialWelfareProgram records
        foreach ($aggregated as $programType => $count) {
            if ($count > 0) {
                SocialWelfareProgram::updateOrCreate(
                    [
                        'municipality' => $municipality,
                        'program_type' => $programType,
                        'year' => $year,
                    ],
                    [
                        'beneficiary_count' => $count,
                        'barangay' => null,
                    ]
                );
            }
        }
    }
}
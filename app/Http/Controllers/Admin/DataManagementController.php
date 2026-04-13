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
        
        $barangays = Barangay::where('municipality', $user->municipality)->count();
        $programs = SocialWelfareProgram::where('municipality', $user->municipality)->count();
        $beneficiaries = SocialWelfareProgram::where('municipality', $user->municipality)->sum('beneficiary_count');
        
        return view('admin.data.dashboard', compact(
            'municipality',
            'barangays',
            'programs',
            'beneficiaries'
        ));
    }

    public function municipality()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->firstOrFail();
        
        $currentYear = date('Y');
        $summary = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', $currentYear)
            ->first();
        
        $total4ps = $summary->total_4ps ?? 0;
        $totalPwd = $summary->total_pwd ?? 0;
        $totalSenior = $summary->total_senior ?? 0;
        $totalAics = $summary->total_aics ?? 0;
        $totalEsa = $summary->total_esa ?? 0;
        $totalSlp = $summary->total_slp ?? 0;
        $totalSoloParent = $summary->total_solo_parent ?? 0;
        
        $years = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        $yearlyData = [];
        foreach ($years as $year) {
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
            'total4ps', 
            'totalPwd', 
            'totalSenior', 
            'totalAics', 
            'totalEsa', 
            'totalSlp', 
            'totalSoloParent',
            'years',
            'yearlyData',
            'trends'
        ));
    }

    public function updateMunicipality(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'male_population' => 'required|integer|min:0',
            'female_population' => 'required|integer|min:0',
            'population_0_19' => 'required|integer|min:0',
            'population_20_59' => 'required|integer|min:0',
            'population_60_100' => 'required|integer|min:0',
            'total_households' => 'required|integer|min:0',
            'single_parent_count' => 'required|integer|min:0',
            'total_4ps' => 'required|integer|min:0',
            'total_pwd' => 'required|integer|min:0',
            'total_senior' => 'required|integer|min:0',
            'total_aics' => 'required|integer|min:0',
            'total_esa' => 'required|integer|min:0',
            'total_slp' => 'required|integer|min:0',
            'total_solo_parent' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $totalPopulation = $request->male_population + $request->female_population;

        $municipality->update([
            'male_population' => $request->male_population,
            'female_population' => $request->female_population,
            'population_0_19' => $request->population_0_19,
            'population_20_59' => $request->population_20_59,
            'population_60_100' => $request->population_60_100,
            'total_households' => $request->total_households,
            'single_parent_count' => $request->single_parent_count,
            'year' => $request->year,
        ]);

        $this->saveYearlySummaryRecord(
            $municipality->name, 
            $request->year, 
            [
                'total_population' => $totalPopulation,
                'total_households' => $request->total_households,
            ],
            [
                'total_4ps' => $request->total_4ps,
                'total_pwd' => $request->total_pwd,
                'total_senior' => $request->total_senior,
                'total_aics' => $request->total_aics,
                'total_esa' => $request->total_esa,
                'total_slp' => $request->total_slp,
                'total_solo_parent' => $request->total_solo_parent,
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

        $query = Barangay::where('municipality', $user->municipality);
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $barangays = $query->orderBy('name')->orderBy('year', 'desc')->get();

        $years = array_merge([2015, 2020], range(date('Y') - 3, date('Y') + 1));
        $years = array_unique($years);
        rsort($years);

        $availableYears = Barangay::where('municipality', $user->municipality)
            ->distinct()->orderByDesc('year')->pluck('year')->toArray();

        return view('admin.data.barangays', compact('barangays', 'municipality', 'years', 'availableYears'));
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
                'male_population'     => intval($request->total_population ?? 0),
                'female_population'   => 0,
                'single_parent_count' => intval($request->single_parent_count ?? 0),
                'pwd_count'           => intval($request->pwd_count ?? 0),
                'aics_count'          => intval($request->aics_count ?? 0),
                'year'                => intval($request->year ?? date('Y')),
            ]);

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
        foreach ($rows as $row) {
            $barangay = Barangay::where('id', $row['id'] ?? null)
                ->where('municipality', $user->municipality)
                ->first();
            if (!$barangay) continue;
            $barangay->update([
                'male_population'     => intval($row['total_population'] ?? 0),
                'female_population'   => 0,
                'single_parent_count' => intval($row['single_parent_count'] ?? 0),
                'pwd_count'           => intval($row['pwd_count'] ?? 0),
                'aics_count'          => intval($row['aics_count'] ?? 0),
                'year'                => intval($row['year'] ?? date('Y')),
            ]);
            $updated++;
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
                    'male_population'            => 0,
                    'female_population'          => 0,
                    'single_parent_count'        => 0,
                    'pwd_count'                  => 0,
                    'aics_count'                 => 0,
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
                'male_population' => 0,
                'female_population' => 0,
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
        
        $years = range(date('Y') - 2, date('Y') + 1);
        
        $query = SocialWelfareProgram::where('municipality', $user->municipality);
        
        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        $programs = $query->orderBy('year', 'desc')->paginate(20);
        
        return view('admin.data.programs', compact('programs', 'programTypes', 'years', 'municipality'));
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $program->update([
            'beneficiary_count' => $request->beneficiary_count,
            'year' => $request->year,
        ]);

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

        $years = array_merge([2015, 2020], range(date('Y') - 3, date('Y') + 1));
        $years = array_unique($years);
        rsort($years);

        // Load admin colors directly
        $adminPrimaryColor = \App\Models\AdminSetting::where('user_id', $user->id)->where('setting_key', 'primary_color')->value('setting_value') ?? '#2C3E8F';
        $adminSecondaryColor = \App\Models\AdminSetting::where('user_id', $user->id)->where('setting_key', 'secondary_color')->value('setting_value') ?? '#FDB913';
        $adminAccentColor = \App\Models\AdminSetting::where('user_id', $user->id)->where('setting_key', 'accent_color')->value('setting_value') ?? '#C41E24';

        return view('admin.data.yearly-data', compact('municipality', 'summaries', 'chartData', 'years', 'adminPrimaryColor', 'adminSecondaryColor', 'adminAccentColor'));
    }

    public function saveYearlySummary(Request $request)
    {
        $user = Auth::user();
        $muniName = $user->municipality;

        $validator = Validator::make($request->all(), [
            'year'             => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'total_population' => 'required|integer|min:0',
            'total_households' => 'required|integer|min:0',
            'total_pwd'        => 'nullable|integer|min:0',
            'total_aics'       => 'nullable|integer|min:0',
            'total_solo_parent'=> 'nullable|integer|min:0',
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
                'total_households'  => $request->total_households,
                'total_4ps'         => $request->total_4ps ?? 0,
                'total_pwd'         => $request->total_pwd ?? 0,
                'total_senior'      => $request->total_senior ?? 0,
                'total_aics'        => $request->total_aics ?? 0,
                'total_esa'         => $request->total_esa ?? 0,
                'total_slp'         => $request->total_slp ?? 0,
                'total_solo_parent' => $request->total_solo_parent ?? 0,
                'created_at'        => now(),
            ]
        );

        return redirect()->route('admin.data.yearly')
            ->with('success', "{$muniName} ({$request->year}) yearly data saved successfully!");
    }

    public function deleteYearlySummary($id)
    {
        $user = Auth::user();
        $summary = MunicipalityYearlySummary::where('id', $id)
            ->where('municipality', $user->municipality)
            ->firstOrFail();
        $summary->delete();
        return redirect()->route('admin.data.yearly')
            ->with('success', 'Yearly record deleted successfully.');
    }
}
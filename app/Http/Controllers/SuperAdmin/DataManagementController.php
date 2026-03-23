<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DataManagementController extends Controller
{
    /**
     * Display data management dashboard
     */
   public function dashboard()
{
    $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();
    
    $totalPrograms = SocialWelfareProgram::count();
    $totalBeneficiaries = SocialWelfareProgram::sum('beneficiary_count');
    $totalBarangays = Barangay::count();
    
    // Fix: Order by id or year instead of created_at
    $recentUpdates = SocialWelfareProgram::with('municipality')
    ->orderBy('year', 'desc')
    ->orderBy('id', 'desc')
    ->take(5)
    ->get();
    
    return view('superadmin.data.dashboard', compact(
        'municipalities',
        'totalPrograms',
        'totalBeneficiaries',
        'totalBarangays',
        'recentUpdates'
    ));
}

    /**
     * Display municipality data management page
     */
    public function municipalities()
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();
        
        return view('superadmin.data.municipalities', compact('municipalities'));
    }

    /**
     * Update municipality data
     */
    public function updateMunicipality(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
    'total_households' => 'required|integer|min:0',
    'male_population' => 'required|integer|min:0',
    'female_population' => 'required|integer|min:0',
    'population_0_19' => 'required|integer|min:0',
    'population_20_59' => 'required|integer|min:0',     // Updated
    'population_60_100' => 'required|integer|min:0',    // Updated
    'single_parent_count' => 'required|integer|min:0',
    'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $municipality = Municipality::findOrFail($id);
        
       $municipality->update([
    'total_households' => $request->total_households,
    'male_population' => $request->male_population,
    'female_population' => $request->female_population,
    'population_0_19' => $request->population_0_19,
    'population_20_59' => $request->population_20_59,   // Updated
    'population_60_100' => $request->population_60_100, // Updated
    'single_parent_count' => $request->single_parent_count,
    'year' => $request->year,
]);

        return redirect()->route('superadmin.data.municipalities')
            ->with('success', 'Municipality data updated successfully!');
    }

    /**
     * Display barangay data management page
     */
    public function barangays(Request $request)
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->pluck('name');
        
        $query = Barangay::query();
        
        if ($request->filled('municipality')) {
            $query->where('municipality', $request->municipality);
        }
        
        $barangays = $query->orderBy('municipality')->orderBy('name')->paginate(20);
        
        return view('superadmin.data.barangays', compact('barangays', 'municipalities'));
    }

    /**
     * Update barangay data
     */
    public function updateBarangay(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'male_population' => 'required|integer|min:0',
            'female_population' => 'required|integer|min:0',
            'population_0_19' => 'required|integer|min:0',
            'population_20_59' => 'required|integer|min:0',
            'population_60_100' => 'required|integer|min:0',
            'single_parent_count' => 'required|integer|min:0',
            'total_households' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $barangay = Barangay::findOrFail($id);
        
        $barangay->update([
            'male_population' => $request->male_population,
            'female_population' => $request->female_population,
            'population_0_19' => $request->population_0_19,
            'population_20_59' => $request->population_20_59,
            'population_60_100' => $request->population_60_100,
            'single_parent_count' => $request->single_parent_count,
            'total_households' => $request->total_households,
            'year' => $request->year,
        ]);

        return redirect()->back()->with('success', 'Barangay data updated successfully!');
    }

    /**
     * Display social welfare programs data management
     */
    public function programs(Request $request)
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->pluck('name');
        
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
        
        $query = SocialWelfareProgram::query();
        
        if ($request->filled('municipality')) {
            $query->where('municipality', $request->municipality);
        }
        
        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        $programs = $query->orderBy('year', 'desc')->paginate(20);
        
        return view('superadmin.data.programs', compact('programs', 'municipalities', 'programTypes', 'years'));
    }

    /**
     * Update social welfare program data
     */
    public function updateProgram(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'beneficiary_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $program = SocialWelfareProgram::findOrFail($id);
        
        $program->update([
            'beneficiary_count' => $request->beneficiary_count,
            'year' => $request->year,
        ]);

        return redirect()->back()->with('success', 'Program data updated successfully!');
    }

    /**
     * Create new program data
     */
    public function createProgram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipality' => 'required|in:Magdalena,Liliw,Majayjay',
            'program_type' => 'required|in:4Ps,Senior_Citizen_Pension,PWD_Assistance,AICS,SLP,ESA,Solo_Parent',
            'beneficiary_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if entry already exists
        $existing = SocialWelfareProgram::where('municipality', $request->municipality)
            ->where('program_type', $request->program_type)
            ->where('year', $request->year)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withErrors(['error' => 'This program data already exists for this municipality and year.'])
                ->withInput();
        }

        SocialWelfareProgram::create([
            'municipality' => $request->municipality,
            'program_type' => $request->program_type,
            'beneficiary_count' => $request->beneficiary_count,
            'year' => $request->year,
            'barangay' => null, // Municipality-level data
        ]);

        return redirect()->back()->with('success', 'Program data created successfully!');
    }

    /**
     * Delete program data
     */
    public function deleteProgram($id)
    {
        $program = SocialWelfareProgram::findOrFail($id);
        $program->delete();
        
        return redirect()->back()->with('success', 'Program data deleted successfully!');
    }
}
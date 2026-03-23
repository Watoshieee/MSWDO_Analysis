<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\Application;
use App\Models\SocialWelfareProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        if (!$municipality) {
            return redirect()->route('dashboard')->with('error', 'No municipality assigned to your account.');
        }

        // Get applications for this municipality
        $applications = Application::where('municipality', $user->municipality)
            ->orderBy('application_date', 'desc')
            ->get();

        // Convert application_date to Carbon
        foreach ($applications as $app) {
            if (is_string($app->application_date)) {
                $app->application_date = \Carbon\Carbon::parse($app->application_date);
            }
        }

        // Statistics
        $totalApplications = $applications->count();
        $pendingApplications = $applications->where('status', 'pending')->count();
        $approvedApplications = $applications->where('status', 'approved')->count();
        $rejectedApplications = $applications->where('status', 'rejected')->count();

        // Applications by program type
        $applicationsByProgram = $applications->groupBy('program_type')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'pending' => $items->where('status', 'pending')->count(),
                    'approved' => $items->where('status', 'approved')->count(),
                ];
            });

        // Barangays
        $barangays = Barangay::where('municipality', $user->municipality)->get();
        
        // Barangay statistics
        $barangayStats = [];
        foreach ($barangays as $barangay) {
            $barangayApps = $applications->where('barangay', $barangay->name);
            $barangayStats[$barangay->name] = [
                'total' => $barangayApps->count(),
                'pending' => $barangayApps->where('status', 'pending')->count(),
                'approved' => $barangayApps->where('status', 'approved')->count(),
                'population' => $barangay->male_population + $barangay->female_population,
                'households' => $barangay->total_households,
            ];
        }
        
        $totalBarangays = $barangays->count();
        $totalPrograms = SocialWelfareProgram::where('municipality', $user->municipality)->count();

        return view('admin.dashboard', compact(
            'municipality',
            'applications',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'applicationsByProgram',
            'barangayStats',
            'totalBarangays',
            'totalPrograms'
        ));
    }

    /**
     * NEW METHOD: Detailed Analysis Page
     * Shows all analytics in one page
     */
    public function detailedAnalysis()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        // Get applications for this municipality
        $applications = Application::where('municipality', $user->municipality)
            ->orderBy('application_date', 'desc')
            ->get();
        
        // Convert dates
        foreach ($applications as $app) {
            if (is_string($app->application_date)) {
                $app->application_date = \Carbon\Carbon::parse($app->application_date);
            }
        }
        
        // Statistics
        $totalApplications = $applications->count();
        $pendingApplications = $applications->where('status', 'pending')->count();
        $approvedApplications = $applications->where('status', 'approved')->count();
        $rejectedApplications = $applications->where('status', 'rejected')->count();
        
        // Applications by program
        $applicationsByProgram = $applications->groupBy('program_type')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'pending' => $items->where('status', 'pending')->count(),
                    'approved' => $items->where('status', 'approved')->count(),
                    'rejected' => $items->where('status', 'rejected')->count(),
                ];
            });
        
        // Barangay statistics
        $barangays = Barangay::where('municipality', $user->municipality)->get();
        $barangayStats = [];
        foreach ($barangays as $barangay) {
            $barangayApps = $applications->where('barangay', $barangay->name);
            $barangayStats[$barangay->name] = [
                'total' => $barangayApps->count(),
                'pending' => $barangayApps->where('status', 'pending')->count(),
                'approved' => $barangayApps->where('status', 'approved')->count(),
                'rejected' => $barangayApps->where('status', 'rejected')->count(),
                'population' => $barangay->male_population + $barangay->female_population,
                'households' => $barangay->total_households,
            ];
        }
        
        return view('admin.detailed-analysis', compact(
            'municipality',
            'applications',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'applicationsByProgram',
            'barangayStats'
        ));
    }

    public function applications(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();

        $query = Application::where('municipality', $user->municipality);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by program type
        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        // Filter by barangay
        if ($request->filled('barangay')) {
            $query->where('barangay', $request->barangay);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }

        $applications = $query->orderBy('application_date', 'desc')->paginate(15);
        
        $programTypes = Application::where('municipality', $user->municipality)
            ->distinct('program_type')
            ->pluck('program_type');
        
        $barangays = Barangay::where('municipality', $user->municipality)->pluck('name');

        return view('admin.applications', compact(
            'applications', 
            'programTypes', 
            'barangays',
            'municipality'
        ));
    }

    /**
 * Show demographic data for the admin's municipality only
 */
public function demographic()
{
    $user = Auth::user();
    $municipality = Municipality::where('name', $user->municipality)->firstOrFail();
    
    // Get demographic data for this municipality only
    $totalPop = $municipality->male_population + $municipality->female_population;
    
    $demographicData = [
        'total_population' => $totalPop,
        'male' => $municipality->male_population,
        'female' => $municipality->female_population,
        'male_pct' => $totalPop > 0 ? round(($municipality->male_population / $totalPop) * 100, 1) : 0,
        'female_pct' => $totalPop > 0 ? round(($municipality->female_population / $totalPop) * 100, 1) : 0,
        'age_0_19' => $municipality->population_0_19,
        'age_20_59' => $municipality->population_20_59,
        'age_60_100' => $municipality->population_60_100,
        'age_0_19_pct' => $totalPop > 0 ? round(($municipality->population_0_19 / $totalPop) * 100, 1) : 0,
        'age_20_59_pct' => $totalPop > 0 ? round(($municipality->population_20_59 / $totalPop) * 100, 1) : 0,
        'age_60_100_pct' => $totalPop > 0 ? round(($municipality->population_60_100 / $totalPop) * 100, 1) : 0,
    ];
    
    // Get barangay data for this municipality
    $barangays = Barangay::where('municipality', $user->municipality)
        ->orderBy('name')
        ->get();
    
    $barangayData = [];
    foreach ($barangays as $barangay) {
        $barangayPop = $barangay->male_population + $barangay->female_population;
        $barangayData[] = [
            'name' => $barangay->name,
            'population' => $barangayPop,
            'male' => $barangay->male_population,
            'female' => $barangay->female_population,
            'households' => $barangay->total_households,
            'single_parents' => $barangay->single_parent_count,
        ];
    }

    return view('admin.demographic', compact(
        'municipality',
        'demographicData',
        'barangayData'
    ));
}

    public function updateApplicationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $application = Application::findOrFail($id);
        
        // Check if admin owns this municipality
        if ($application->municipality !== Auth::user()->municipality) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $oldStatus = $application->status;
        $application->status = $request->status;
        $application->save();

        // Update barangay approved applications count
        if ($oldStatus !== 'approved' && $request->status === 'approved') {
            $barangay = Barangay::where('municipality', $application->municipality)
                ->where('name', $application->barangay)
                ->first();
            
            if ($barangay) {
                $barangay->total_approved_applications += 1;
                $barangay->save();
            }
        }

        // Decrease if changed from approved to something else
        if ($oldStatus === 'approved' && $request->status !== 'approved') {
            $barangay = Barangay::where('municipality', $application->municipality)
                ->where('name', $application->barangay)
                ->first();
            
            if ($barangay && $barangay->total_approved_applications > 0) {
                $barangay->total_approved_applications -= 1;
                $barangay->save();
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Application status updated']);
        }

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }

    public function barangay($name)
    {
        $user = Auth::user();
        $barangay = Barangay::where('municipality', $user->municipality)
            ->where('name', $name)
            ->firstOrFail();

        $applications = Application::where('municipality', $user->municipality)
            ->where('barangay', $name)
            ->orderBy('application_date', 'desc')
            ->get();

        $statistics = [
            'total_applications' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'by_program' => $applications->groupBy('program_type')->map->count(),
            'by_gender' => [
                'male' => $applications->where('gender', 'Male')->count(),
                'female' => $applications->where('gender', 'Female')->count(),
            ],
            'average_age' => $applications->avg('age'),
        ];

        return view('admin.barangay', compact('barangay', 'applications', 'statistics'));
    }
}
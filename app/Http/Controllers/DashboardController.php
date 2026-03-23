<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Application;
use App\Models\SocialWelfareProgram;
use App\Models\Barangay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        Log::info('Dashboard access:', [
            'user_id' => $user->id,
            'role' => $user->role
        ]);
        
        // Redirect based on role
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return $this->userDashboard();
        }
    }

    public function superAdminDashboard()
    {
        $user = Auth::user();
        
        // Double-check authorization
        if (!$user || !$user->isSuperAdmin()) {
            Log::warning('Unauthorized super admin dashboard access:', [
                'user_id' => $user ? $user->id : null,
                'role' => $user ? $user->role : null
            ]);
            abort(403, 'Unauthorized access.');
        }

        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();
        $totalApplications = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();
        $approvedApplications = Application::where('status', 'approved')->count();
        $totalUsers = \App\Models\User::count();
        
        $recentUsers = \App\Models\User::latest()->take(5)->get();
        $recentApplications = Application::with('user')->latest()->take(5)->get();

        return view('superadmin.dashboard', compact(
            'municipalities',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'totalUsers',
            'recentUsers',
            'recentApplications'
        ));
    }

    public function adminDashboard()
    {
        $user = Auth::user();
        
        // Double-check authorization
        if (!$user || !$user->isAdmin()) {
            Log::warning('Unauthorized admin dashboard access:', [
                'user_id' => $user ? $user->id : null,
                'role' => $user ? $user->role : null
            ]);
            abort(403, 'Unauthorized access.');
        }

        $municipality = Municipality::where('name', $user->municipality)->first();
        
        if (!$municipality) {
            return redirect()->route('dashboard')->with('error', 'No municipality assigned to your account.');
        }

        $applications = Application::where('municipality', $user->municipality)
            ->orderBy('application_date', 'desc')
            ->get();

        // Convert application_date to Carbon
        foreach ($applications as $app) {
            if (is_string($app->application_date)) {
                $app->application_date = \Carbon\Carbon::parse($app->application_date);
            }
        }

        $totalApplications = $applications->count();
        $pendingApplications = $applications->where('status', 'pending')->count();
        $approvedApplications = $applications->where('status', 'approved')->count();
        $rejectedApplications = $applications->where('status', 'rejected')->count();

        $applicationsByProgram = $applications->groupBy('program_type')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'pending' => $items->where('status', 'pending')->count(),
                    'approved' => $items->where('status', 'approved')->count(),
                ];
            });

        $barangays = Barangay::where('municipality', $user->municipality)->get();
        
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

    public function userDashboard()
    {
        $user = Auth::user();
        
        // Double-check authorization
        if (!$user || !$user->isUser()) {
            Log::warning('Unauthorized user dashboard access:', [
                'user_id' => $user ? $user->id : null,
                'role' => $user ? $user->role : null
            ]);
            abort(403, 'Unauthorized access.');
        }

        $applications = Application::where('user_id', $user->id)
            ->orderBy('application_date', 'desc')
            ->get();

        foreach ($applications as $app) {
            if (is_string($app->application_date)) {
                $app->application_date = \Carbon\Carbon::parse($app->application_date);
            }
        }

        $totalApplications = $applications->count();
        $pendingApplications = $applications->where('status', 'pending')->count();
        $approvedApplications = $applications->where('status', 'approved')->count();
        $rejectedApplications = $applications->where('status', 'rejected')->count();

        return view('user.dashboard', compact(
            'applications',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications'
        ));
    }
}
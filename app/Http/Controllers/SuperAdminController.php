<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // User statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('role', User::ROLE_ADMIN)->count();
        $totalSuperAdmins = User::where('role', User::ROLE_SUPER_ADMIN)->count();
        $totalRegularUsers = User::where('role', User::ROLE_USER)->count();
        
        // All users for modal
        $allUsers = User::orderBy('id', 'desc')->get();
        
        // Recent users
        $recentUsers = User::orderBy('id', 'desc')->take(5)->get();
        
        // Application statistics
        $totalApplications = \App\Models\Application::count();
        $pendingApplications = \App\Models\Application::where('status', 'pending')->count();
        $approvedApplications = \App\Models\Application::where('status', 'approved')->count();
        
        // All applications for modals
        $allApplications = \App\Models\Application::orderBy('application_date', 'desc')->get();
        $pendingApplicationsList = \App\Models\Application::where('status', 'pending')->orderBy('application_date', 'desc')->get();
        $approvedApplicationsList = \App\Models\Application::where('status', 'approved')->orderBy('application_date', 'desc')->get();
        
        // Recent applications
        $recentApplications = \App\Models\Application::orderBy('application_date', 'desc')->take(5)->get();
        
        // Municipality data
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();
        
        // Data management stats
        $totalPrograms = SocialWelfareProgram::count();
        $totalBeneficiaries = SocialWelfareProgram::sum('beneficiary_count');
        $totalBarangays = Barangay::count();
        
        return view('superadmin.dashboard', compact(
            'totalUsers', 
            'totalAdmins', 
            'totalSuperAdmins', 
            'totalRegularUsers',
            'allUsers',
            'recentUsers',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'allApplications',
            'pendingApplicationsList',
            'approvedApplicationsList',
            'recentApplications',
            'municipalities',
            'totalPrograms',
            'totalBeneficiaries',
            'totalBarangays'
        ));
    }

    public function users()
    {
        $users = User::all();
        $roles = User::getRoles();
        $municipalities = User::getMunicipalities();
        
        return view('superadmin.users', compact('users', 'roles', 'municipalities'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'full_name' => 'required|string|max:100',
            'role' => 'required|in:super_admin,admin,user',
            'municipality' => 'required_if:role,admin|nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'role' => $request->role,
            'municipality' => $request->municipality,
            'status' => $request->status,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('superadmin.users')->with('success', 'User created successfully!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'email' => 'required|string|email|max:100|unique:users,email,' . $id,
            'full_name' => 'required|string|max:100',
            'role' => 'required|in:super_admin,admin,user',
            'municipality' => 'required_if:role,admin|nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'role' => $request->role,
            'municipality' => $request->municipality,
            'status' => $request->status,
        ]);

        return redirect()->route('superadmin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users')->with('error', 'You cannot delete your own account!');
        }
        
        $user->delete();
        
        return redirect()->route('superadmin.users')->with('success', 'User deleted successfully!');
    }
}
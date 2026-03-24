<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $totalApplications = Application::where('user_id', $user->id)->count();
        $pendingCount = Application::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $approvedCount = Application::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();
        
        $recentApplications = Application::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        $announcements = Announcement::where('municipality', null)
            ->orWhere('municipality', '')
            ->latest()
            ->take(3)
            ->get();
        
        return view('user.dashboard', compact(
            'totalApplications',
            'pendingCount',
            'approvedCount',
            'recentApplications',
            'announcements'
        ));
    }
    
    public function programs()
    {
        return view('user.programs');
    }
    
    public function announcements()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('user.announcements', compact('announcements'));
    }
    
    public function myApplications()
    {
        $applications = Application::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('user.applications', compact('applications'));
    }
}
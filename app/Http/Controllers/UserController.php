<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\FileUpload;  // ADD THIs
//use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;  // ADD THIS for Storage

class UserController extends Controller
{
public function dashboard()
{
    $user = Auth::user();
    
    // Check if user is logged in
    if (!$user) {
        return redirect()->route('login');
    }
    
    $totalApplications = Application::where('user_id', $user->id)->count();
    $pendingCount = Application::where('user_id', $user->id)
        ->where('status', 'pending')
        ->count();
    $approvedCount = Application::where('user_id', $user->id)
        ->where('status', 'approved')
        ->count();
    $rejectedCount = Application::where('user_id', $user->id)
        ->where('status', 'rejected')
        ->count();
    
    $recentApplications = Application::where('user_id', $user->id)
        ->orderBy('application_date', 'desc')
        ->take(5)
        ->get();
    
    $announcements = collect();
    
    return view('user.dashboard', compact(
        'totalApplications',
        'pendingCount',
        'approvedCount',
        'rejectedCount',
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
    try {
        $announcements = \App\Models\Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    } catch (\Exception $e) {
        // If table doesn't exist yet, return empty collection
        $announcements = collect();
    }
    
    return view('user.announcements', compact('announcements'));
}
    
public function myRequirements()
{
    $user = Auth::user();
    
    // Get all applications of the user with their file uploads
    $applications = Application::where('user_id', $user->id)
        ->with(['fileMonitoring.fileUploads'])
        ->orderBy('application_date', 'desc')
        ->get();
    
    // Prepare data for requirements view
    $requirementsData = [];
    
    foreach ($applications as $application) {
        $fileMonitoring = $application->fileMonitoring;
        $fileUploads = $fileMonitoring ? $fileMonitoring->fileUploads : collect();
        
        // Determine overall status
        $overallStatus = $application->status; // 'pending', 'approved', 'rejected'
        
        $requirementsData[] = [
            'application' => $application,
            'fileUploads' => $fileUploads,
            'totalRequirements' => $fileUploads->count(),
            'uploadedCount' => $fileUploads->where('file_path', '!=', null)->count(),
            'approvedCount' => $fileUploads->where('status', 'approved')->count(),
            'rejectedCount' => $fileUploads->where('status', 'rejected')->count(),
            'pendingCount' => $fileUploads->where('status', 'pending')->count(),
            'overallStatus' => $overallStatus
        ];
    }
    
    return view('user.my-requirements', compact('requirementsData'));
}

/**
 * Resubmit a rejected requirement
 */
public function resubmitRequirement(Request $request, $fileUploadId)
{
    $user = Auth::user();
    
    $request->validate([
        'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);
    
    $fileUpload = FileUpload::where('id', $fileUploadId)
        ->whereHas('fileMonitoring', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->firstOrFail();
    
    // Delete old file
    if ($fileUpload->file_path && Storage::disk('public')->exists($fileUpload->file_path)) {
        Storage::disk('public')->delete($fileUpload->file_path);
    }
    
    // Upload new file
    $file = $request->file('file');
    $originalName = $file->getClientOriginalName();
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
    $applicationId = $fileUpload->fileMonitoring->application_id;
    $path = $file->storeAs("applications/{$applicationId}/requirements", $filename, 'public');
    
    // Update file upload record
    $fileUpload->update([
        'file_name' => $originalName,
        'file_path' => $path,
        'status' => 'pending',  // Reset to pending
        'remarks' => null,       // Clear old remarks
        'admin_remarks' => null, // Clear admin remarks
        'verified_at' => null,
        'verified_by' => null,
        'uploaded_at' => now()
    ]);
    
    // Update overall status of file monitoring
    $fileMonitoring = $fileUpload->fileMonitoring;
    $totalFiles = $fileMonitoring->fileUploads()->count();
    $pendingFiles = $fileMonitoring->fileUploads()->where('status', 'pending')->count();
    $rejectedFiles = $fileMonitoring->fileUploads()->where('status', 'rejected')->count();
    
    if ($rejectedFiles > 0) {
        $fileMonitoring->overall_status = 'rejected';
    } elseif ($pendingFiles > 0) {
        $fileMonitoring->overall_status = 'pending';
        // Also update application status back to pending
        $fileMonitoring->application->update(['status' => 'pending']);
    } else {
        $fileMonitoring->overall_status = 'in_review';
    }
    $fileMonitoring->save();
    
    return redirect()->back()->with('success', 'Document re-uploaded successfully! Waiting for admin review.');
}
}
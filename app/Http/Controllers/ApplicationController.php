<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Barangay;
use App\Models\ProgramRequirement;
use App\Models\FileMonitoring;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications with filters.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Base query
        $query = Application::query();
        
        // Filter by role
        if ($user->isAdmin()) {
            // Admin can only see their municipality
            $query->where('municipality', $user->municipality);
        } elseif ($user->isUser()) {
            // Regular user can only see their own applications
            $query->where('user_id', $user->id);
        }
        // Super Admin sees all

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('municipality') && $user->isSuperAdmin()) {
            $query->where('municipality', $request->municipality);
        }

        if ($request->filled('barangay')) {
            $query->where('barangay', $request->barangay);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('application_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('application_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Get applications with pagination
        $applications = $query->orderBy('application_date', 'desc')
                              ->paginate(15)
                              ->withQueryString();

        // Convert application_date to Carbon for formatting
        foreach ($applications as $app) {
            if (is_string($app->application_date)) {
                $app->application_date = \Carbon\Carbon::parse($app->application_date);
            }
        }

        // Get filter options
        $programTypes = Application::select('program_type')
            ->distinct()
            ->pluck('program_type');
        
        $statuses = ['pending', 'approved', 'rejected'];
        
        $municipalities = [];
        if ($user->isSuperAdmin()) {
            $municipalities = Application::select('municipality')
                ->distinct()
                ->pluck('municipality');
        }

        // Get barangays based on user role
        if ($user->isAdmin()) {
            $barangays = Barangay::where('municipality', $user->municipality)
                ->pluck('name')
                ->toArray();
        } else {
            $barangays = Barangay::pluck('name')
                ->toArray();
        }

        return view('applications.index', compact(
            'applications', 
            'programTypes', 
            'statuses', 
            'municipalities',
            'barangays'
        ));
    }

    /**
 * Show requirements page for selected program
 */
public function create($programType)
{
    $user = Auth::user();
    
    // Validate program type
    $validPrograms = ['4Ps', 'Senior_Citizen_Pension', 'PWD_Assistance', 'AICS', 'SLP', 'ESA', 'Solo_Parent'];
    if (!in_array($programType, $validPrograms)) {
        return redirect()->back()->with('error', 'Invalid program selected.');
    }
    
    // CHECK: Does the user have any pending or approved application?
    $hasPendingOrApproved = Application::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'approved'])
        ->exists();
    
    if ($hasPendingOrApproved) {
        return redirect()->route('user.my-requirements')
            ->with('error', 'You cannot apply for another program while you have a pending or approved application. Please wait for your current application to be reviewed or rejected.');
    }
    
    // Map program type to requirements program type
    $programTypeMap = [
        '4Ps' => '4Ps',
        'Senior_Citizen_Pension' => 'Senior_Citizen',
        'PWD_Assistance' => 'PWD_New',
        'AICS' => 'AICS_Medical',
        'SLP' => 'SLP',
        'ESA' => 'ESA',
        'Solo_Parent' => 'Solo_Parent',
    ];
    
    $programKey = $programTypeMap[$programType] ?? $programType;
    
    // Get requirements for this program
    $requirements = ProgramRequirement::where('program_type', $programKey)->get();
    
    // Get program name for display
    $programName = str_replace('_', ' ', $programType);
    
    // Return view with requirements only (no application record)
    return view('applications.create', compact('requirements', 'programType', 'programName'));
}

/**
 * Batch upload requirements
 */
public function uploadBatch(Request $request)
{
    try {
        $user = Auth::user();
        
        // CHECK: Does the user have any pending or approved application?
        $hasPendingOrApproved = Application::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        
        if ($hasPendingOrApproved) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot apply for another program while you have a pending or approved application. Please wait for your current application to be reviewed or rejected.'
            ], 400);
        }
        
        $request->validate([
            'program_type' => 'required|string',
            'requirements' => 'required|array',
            'requirement_names' => 'required|array'
        ]);
        
        // GET USER'S MUNICIPALITY
        $municipality = $user->municipality ?? 'Majayjay';
        
        // Create application record
        $application = Application::create([
            'user_id' => $user->id,
            'program_type' => $request->program_type,
            'full_name' => $user->full_name,
            'age' => 0,
            'gender' => 'Male',
            'contact_number' => $user->email ?? '',
            'barangay' => '',
            'municipality' => $municipality,  // SAVE MUNICIPALITY HERE
            'status' => 'pending',
            'application_date' => now(),
            'year' => date('Y'),
            'stage' => 'requirements'
        ]);
        
        // CREATE FILE MONITORING WITH MUNICIPALITY
        $fileMonitoring = FileMonitoring::create([
            'application_id' => $application->id,
            'user_id' => $user->id,  // ADD THIS - user->id ang gagamitin
            'municipality' => $municipality,  // SAVE MUNICIPALITY HERE
            'priority' => 'medium',
            'overall_status' => 'pending'
        ]);
        
        // Upload each file
        foreach ($request->file('requirements') as $requirementName => $file) {
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
            $path = $file->storeAs("applications/{$application->id}/requirements", $filename, 'public');
            
            FileUpload::create([
                'file_monitoring_id' => $fileMonitoring->id,
                'file_name' => $originalName,
                'file_path' => $path,
                'requirement_name' => $requirementName,
                'status' => 'pending',
                'uploaded_at' => now()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully! Your documents are now pending review.'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Batch upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Show the solo parent application form
     */
    public function showSoloParentForm()
    {
        try {
            // Log that we're entering this method
            \Log::info('showSoloParentForm method called');
            
            $user = Auth::user();
            
            // Check if user is logged in
            if (!$user) {
                \Log::error('No authenticated user found');
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }
            
            \Log::info('User ID: ' . $user->id);
            
            // Check if user already has pending application for Solo Parent
            $existing = Application::where('user_id', $user->id)
                ->where('program_type', 'Solo_Parent')
                ->where('status', 'pending')
                ->exists();
                
            if ($existing) {
                \Log::info('User already has pending Solo Parent application');
                return redirect()->route('user.my-applications')
                    ->with('error', 'You already have a pending Solo Parent application.');
            }
            
            // Get barangays from database
            $barangays = Barangay::where('municipality', 'Majayjay')
                ->pluck('name')
                ->toArray();
            
            \Log::info('Barangays found: ' . count($barangays));
            
            // If no barangays found in database, use default list
            if (empty($barangays)) {
                $barangays = [
                    'Alipit', 'Malaking Ambling', 'Munting Ambling', 'Baanan', 'Balanac',
                    'Bucal', 'Buenavista', 'Bungkol', 'Buo', 'Burlungan', 'Cigaras',
                    'Ibabang Atingay', 'Ibabang Butnong', 'Ilayang Atingay', 'Ilayang Butnong',
                    'Ilog', 'Malinao', 'Maravilla', 'Poblacion', 'Sabang', 'Salasad',
                    'Tanawan', 'Tipunan', 'Halayhayin'
                ];
                \Log::info('Using default barangays list');
            }
            
            // Check if the view file exists
            $viewPath = resource_path('views/applications/solo-parent-form.blade.php');
            if (!file_exists($viewPath)) {
                \Log::error('View file not found: ' . $viewPath);
                return response()->json(['error' => 'View file not found'], 500);
            }
            
            \Log::info('Returning view with ' . count($barangays) . ' barangays');
            return view('applications.solo-parent-form', compact('barangays'));
            
        } catch (\Exception $e) {
            \Log::error('Error in showSoloParentForm: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created application in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if this is from the solo parent form (has additional_data)
        if ($request->has('additional_data') && $request->program_type === 'Solo_Parent') {
            return $this->storeSoloParentApplication($request);
        }

        // Original validation for regular applications
        $rules = [
            'program_type' => 'required|in:4Ps,Senior_Citizen_Pension,PWD_Assistance,AICS,SLP,ESA,Solo_Parent',
            'full_name' => 'required|string|max:100',
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:20',
            'barangay' => 'required|string|exists:barangays,name',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];

        // Add municipality validation based on role
        if ($user->isSuperAdmin() || $user->isUser()) {
            $rules['municipality'] = 'required|in:Magdalena,Liliw,Majayjay';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare data
        $data = [
            'user_id' => $user->id,
            'program_type' => $request->program_type,
            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'contact_number' => $request->contact_number,
            'barangay' => $request->barangay,
            'status' => 'pending',
            'application_date' => now(),
            'year' => $request->year,
        ];

        // Set municipality based on role
        if ($user->isAdmin()) {
            $data['municipality'] = $user->municipality;
        } else {
            $data['municipality'] = $request->municipality;
        }

        // Create application
        $application = Application::create($data);

        return redirect()->route('applications.show', $application->id)
            ->with('success', 'Application created successfully!');
    }

    /**
     * Store solo parent application with additional data
     */
    protected function storeSoloParentApplication(Request $request)
    {
        try {
            $user = Auth::user();
            
            \Log::info('Storing Solo Parent Application');
            \Log::info($request->all());
            
            // Validate solo parent form data
            $validator = Validator::make($request->all(), [
                'program_type' => 'required|in:Solo_Parent',
                'additional_data' => 'required|array',
                'additional_data.pangalan' => 'required|string|max:100',
                'additional_data.edad' => 'required|integer|min:0|max:120',
                'additional_data.kasarian' => 'required|in:Male,Female',
                'additional_data.barangay' => 'required|string',
                'additional_data.contact_number' => 'required|string|max:20',
            ]);
            
            if ($validator->fails()) {
                \Log::error('Validation failed: ' . json_encode($validator->errors()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Prepare data for applications table
            $data = [
                'user_id' => $user->id,
                'program_type' => $request->program_type,
                'full_name' => $request->additional_data['pangalan'],
                'age' => $request->additional_data['edad'],
                'gender' => $request->additional_data['kasarian'],
                'contact_number' => $request->additional_data['contact_number'],
                'barangay' => $request->additional_data['barangay'],
                'municipality' => $request->municipality ?? 'Majayjay',
                'status' => 'pending',
                'application_date' => now(),
                'year' => date('Y'),
                'form_data' => json_encode($request->additional_data),
                'stage' => 'requirements'
            ];
            
            // Create application
            $application = Application::create($data);
            
            \Log::info('Application created successfully with ID: ' . $application->id);
            
            // Redirect to requirements upload page
            return redirect()->route('applications.requirements', $application->id)
                ->with('success', 'Application created! Please upload the required documents.');
                
        } catch (\Exception $e) {
            \Log::error('Error storing Solo Parent application: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified application.
     */
    public function show($id)
    {
        $application = Application::findOrFail($id);
        
        // Check authorization
        $user = Auth::user();
        if ($user->isUser() && $application->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        if ($user->isAdmin() && $application->municipality !== $user->municipality) {
            abort(403, 'Unauthorized access.');
        }

        // Convert date
        if (is_string($application->application_date)) {
            $application->application_date = \Carbon\Carbon::parse($application->application_date);
        }

        return view('applications.show', compact('application'));
    }

    /**
     * Show the form for editing the specified application.
     */
    public function edit($id)
    {
        $application = Application::findOrFail($id);
        
        // Check authorization
        $user = Auth::user();
        if ($user->isUser() && $application->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        if ($user->isAdmin() && $application->municipality !== $user->municipality) {
            abort(403, 'Unauthorized access.');
        }

        // Only pending applications can be edited
        if ($application->status !== 'pending' && !$user->isAdmin()) {
            return redirect()->route('applications.show', $id)
                ->with('error', 'Only pending applications can be edited.');
        }

        $programTypes = [
            '4Ps', 'Senior_Citizen_Pension', 'PWD_Assistance', 
            'AICS', 'SLP', 'ESA', 'Solo_Parent'
        ];

        if ($user->isAdmin()) {
            $barangays = Barangay::where('municipality', $user->municipality)
                ->pluck('name', 'name');
        } else {
            $barangays = Barangay::pluck('name', 'name');
        }

        return view('applications.edit', compact('application', 'programTypes', 'barangays'));
    }

    /**
     * Update the specified application in storage.
     */
    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        
        // Check authorization
        $user = Auth::user();
        if ($user->isUser() && $application->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        if ($user->isAdmin() && $application->municipality !== $user->municipality) {
            abort(403, 'Unauthorized access.');
        }

        // Only pending applications can be updated by non-admins
        if ($application->status !== 'pending' && !$user->isAdmin()) {
            return redirect()->route('applications.show', $id)
                ->with('error', 'Only pending applications can be updated.');
        }

        $rules = [
            'program_type' => 'required|in:4Ps,Senior_Citizen_Pension,PWD_Assistance,AICS,SLP,ESA,Solo_Parent',
            'full_name' => 'required|string|max:100',
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:20',
            'barangay' => 'required|string|exists:barangays,name',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update application
        $application->update([
            'program_type' => $request->program_type,
            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'contact_number' => $request->contact_number,
            'barangay' => $request->barangay,
        ]);

        return redirect()->route('applications.show', $id)
            ->with('success', 'Application updated successfully!');
    }

    /**
     * Show requirements upload page after application submission
     */
    public function showRequirements($applicationId)
    {
        $application = Application::with(['fileMonitoring.fileUploads'])->findOrFail($applicationId);
        
        // Check authorization
        $user = Auth::user();
        if ($user->isUser() && $application->user_id !== $user->id) {
            abort(403);
        }
        
        // Complete program type mapping for all programs
        $programTypeMap = [
            // AICS Programs
            'AICS' => 'AICS_Medical',
            'AICS_Medical' => 'AICS_Medical',
            'AICS_Burial' => 'AICS_Burial',
            
            // Solo Parent
            'Solo_Parent' => 'Solo_Parent',
            
            // PWD Programs
            'PWD_Assistance' => 'PWD_New',
            'PWD_New' => 'PWD_New',
            'PWD_Renewal' => 'PWD_Renewal',
            
            // 4Ps
            '4Ps' => '4Ps',
            
            // Senior Citizen
            'Senior_Citizen_Pension' => 'Senior_Citizen',
            'Senior_Citizen' => 'Senior_Citizen',
            
            // SLP and ESA (if they have requirements, add them)
            'SLP' => 'SLP',
            'ESA' => 'ESA',
        ];
        
        $programKey = $programTypeMap[$application->program_type] ?? $application->program_type;
        
        // Get requirements for this program
        $requirements = ProgramRequirement::where('program_type', $programKey)->get();
        
        // Get or create file monitoring record
        $fileMonitoring = $application->fileMonitoring;
        if (!$fileMonitoring) {
            $fileMonitoring = FileMonitoring::create([
                'application_id' => $application->id,
                'priority' => 'medium',
                'overall_status' => 'pending'
            ]);
            
            // Create file upload entries for each requirement
            foreach ($requirements as $req) {
                FileUpload::create([
                    'file_monitoring_id' => $fileMonitoring->id,
                    'requirement_name' => $req->requirement_name,
                    'status' => 'pending',
                    'uploaded_at' => null
                ]);
            }
        }
        
        // Load file uploads
        $fileUploads = FileUpload::where('file_monitoring_id', $fileMonitoring->id)->get();
        
        return view('applications.requirements', compact('application', 'requirements', 'fileUploads', 'fileMonitoring'));
    }

    /**
     * Upload requirement file
     */
    public function uploadRequirement(Request $request, $applicationId)
    {
        $request->validate([
            'requirement_name' => 'required|string',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);
        
        $application = Application::findOrFail($applicationId);
        $user = Auth::user();
        
        // Check authorization
        if ($user->isUser() && $application->user_id !== $user->id) {
            abort(403);
        }
        
        $fileMonitoring = FileMonitoring::where('application_id', $applicationId)->firstOrFail();
        
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
        $path = $file->storeAs("applications/{$applicationId}/requirements", $filename, 'public');
        
        // Update file upload record
        $fileUpload = FileUpload::where('file_monitoring_id', $fileMonitoring->id)
            ->where('requirement_name', $request->requirement_name)
            ->first();
        
        if ($fileUpload) {
            // Delete old file if exists
            if ($fileUpload->file_path && Storage::disk('public')->exists($fileUpload->file_path)) {
                Storage::disk('public')->delete($fileUpload->file_path);
            }
            
            $fileUpload->update([
                'file_name' => $originalName,
                'file_path' => $path,
                'status' => 'pending',
                'remarks' => null,
                'uploaded_at' => now()
            ]);
        } else {
            FileUpload::create([
                'file_monitoring_id' => $fileMonitoring->id,
                'file_name' => $originalName,
                'file_path' => $path,
                'requirement_name' => $request->requirement_name,
                'status' => 'pending',
                'uploaded_at' => now()
            ]);
        }
        
        // Update overall status
        $fileMonitoring->updateOverallStatus();
        
        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    /**
     * Delete requirement file
     */
    public function deleteRequirement(Request $request, $applicationId)
    {
        $request->validate([
            'requirement_name' => 'required|string'
        ]);
        
        $application = Application::findOrFail($applicationId);
        $user = Auth::user();
        
        if ($user->isUser() && $application->user_id !== $user->id) {
            abort(403);
        }
        
        $fileMonitoring = FileMonitoring::where('application_id', $applicationId)->firstOrFail();
        
        $fileUpload = FileUpload::where('file_monitoring_id', $fileMonitoring->id)
            ->where('requirement_name', $request->requirement_name)
            ->first();
        
        if ($fileUpload) {
            if ($fileUpload->file_path && Storage::disk('public')->exists($fileUpload->file_path)) {
                Storage::disk('public')->delete($fileUpload->file_path);
            }
            
            $fileUpload->update([
                'file_name' => null,
                'file_path' => null,
                'status' => 'pending',
                'uploaded_at' => null
            ]);
        }
        
        $fileMonitoring->updateOverallStatus();
        
        return redirect()->back()->with('success', 'File removed successfully.');
    }

    /**
     * Remove the specified application from storage.
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        
        // Check authorization
        $user = Auth::user();
        if ($user->isUser() && $application->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        if ($user->isAdmin() && $application->municipality !== $user->municipality) {
            abort(403, 'Unauthorized access.');
        }

        // Only pending applications can be deleted
        if ($application->status !== 'pending') {
            return redirect()->route('applications.index')
                ->with('error', 'Only pending applications can be deleted.');
        }

        // Permanent delete (not soft delete)
        $application->forceDelete();

        return redirect()->route('applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    /**
     * Update application status (for admins only).
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Validate request
            $request->validate([
                'status' => 'required|in:pending,approved,rejected'
            ]);

            // Find application
            $application = Application::findOrFail($id);
            
            // Check authorization
            $user = Auth::user();
            if (!$user->isAdmin() && !$user->isSuperAdmin()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized. Only admins can update status.'
                ], 403);
            }
            
            // Check if admin owns this municipality
            if ($user->isAdmin() && $application->municipality !== $user->municipality) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized. You can only update applications in your municipality.'
                ], 403);
            }

            // Store old status for comparison
            $oldStatus = $application->status;
            
            // Update status
            $application->status = $request->status;
            $application->save();

            // Update barangay approved count if status changed to approved
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

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Application status updated to ' . $request->status . ' successfully!',
                    'status' => $request->status,
                    'old_status' => $oldStatus
                ]);
            }

            // Redirect for non-AJAX requests
            return redirect()->back()->with('success', 'Application status updated successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Application not found: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Application not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Status update error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
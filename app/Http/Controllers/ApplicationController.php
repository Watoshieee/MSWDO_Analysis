<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
     * Show the form for creating a new application.
     */
    public function create()
{
    $user = Auth::user();
    
    // Get program types from enum
    $programTypes = [
        '4Ps' => '4Ps',
        'Senior_Citizen_Pension' => 'Senior Citizen Pension',
        'PWD_Assistance' => 'PWD Assistance',
        'AICS' => 'AICS',
        'SLP' => 'SLP',
        'ESA' => 'ESA',
        'Solo_Parent' => 'Solo Parent'
    ];

    // Get years (current year and next year)
    $currentYear = date('Y');
    $years = [
        $currentYear => $currentYear,
        $currentYear + 1 => $currentYear + 1
    ];

    // Get barangays based on user role
    if ($user->isAdmin()) {
        // Admin can only select barangays from their municipality
        $barangays = Barangay::where('municipality', $user->municipality)
            ->pluck('name', 'name');
        $municipality = $user->municipality;
        $municipalities = [$user->municipality => $user->municipality];
    } elseif ($user->isSuperAdmin()) {
        // Super admin can select any municipality and barangay
        $barangays = Barangay::pluck('name', 'name');
        $municipalities = [
            'Magdalena' => 'Magdalena',
            'Liliw' => 'Liliw',
            'Majayjay' => 'Majayjay'
        ];
    } else {
        // Regular user can select any municipality and barangay
        $barangays = Barangay::pluck('name', 'name');
        $municipalities = [
            'Magdalena' => 'Magdalena',
            'Liliw' => 'Liliw',
            'Majayjay' => 'Majayjay'
        ];
    }

    return view('applications.create', compact(
        'programTypes', 
        'barangays', 
        'municipalities',
        'years',
        'municipality' ?? null
    ));
}

    /**
     * Store a newly created application in storage.
     */
    public function store(Request $request)
{
    $user = Auth::user();

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

        $application->delete();

        return redirect()->route('applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    /**
     * Update application status (for admins only).
     * FIXED VERSION - Returns proper JSON responses
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
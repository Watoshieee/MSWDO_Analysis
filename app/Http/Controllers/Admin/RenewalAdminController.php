<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RenewalRequest;
use App\Models\Appointment;
use App\Mail\AppointmentStatusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RenewalAdminController extends Controller
{
    /**
     * List all renewal requests for admin's municipality (GET /admin/renewals)
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $query = RenewalRequest::with(['user', 'appointment'])
            ->where('municipality', $admin->municipality);

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $renewals = $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')")
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($renewals->map(function ($r) {
                return [
                    'id'               => $r->id,
                    'user_id'          => $r->user_id,
                    'applicant_name'   => $r->user ? $r->user->full_name : 'N/A',
                    'contact_number'   => $r->user ? ($r->user->mobile_number ?? $r->user->phone_number ?? 'N/A') : 'N/A',
                    'barangay'         => $r->user ? ($r->user->barangay ?? 'N/A') : 'N/A',
                    'program_type'     => $r->program_type,
                    'program_label'    => $r->program_type === 'PWD' ? 'PWD Renewal' : 'Solo Parent Renewal',
                    'status'           => $r->status,
                    'status_badge'     => $r->status_badge,
                    'appointment_id'   => $r->appointment_id,
                    'appointment_date' => $r->appointment ? Carbon::parse($r->appointment->appointment_date)->format('F d, Y') : 'N/A',
                    'appointment_time' => $r->appointment ? ($r->appointment->formatted_time ?? Carbon::parse($r->appointment->appointment_time)->format('h:i A')) : 'N/A',
                    'appt_status'      => $r->appointment ? $r->appointment->status : 'N/A',
                    'id_file_name'     => $r->id_file_name,
                    'id_file_url'      => route('admin.renewals.id-file', $r->id),
                    'user_notes'       => $r->user_notes,
                    'admin_remarks'    => $r->admin_remarks,
                    'created_at'       => $r->created_at ? $r->created_at->format('M d, Y h:i A') : 'N/A',
                ];
            }));
        }

        return response()->json($renewals);
    }

    /**
     * Admin securely views applicant's uploaded renewal ID document (GET /admin/renewals/{id}/id-file)
     */
    public function serveIdFile($id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        // Municipality isolation strictly enforced
        $renewal = RenewalRequest::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $filePath = $renewal->id_file_path;
        if (!$filePath) {
            abort(404, 'No file stored for this renewal.');
        }

        $candidates = [
            Storage::disk('public')->path($filePath),
            base_path('storage/app/public/' . $filePath),
            public_path('storage/' . $filePath),
        ];

        $fullPath = null;
        foreach ($candidates as $cand) {
            if (file_exists($cand) && is_file($cand)) {
                $fullPath = $cand;
                break;
            }
        }

        if (!$fullPath) {
            abort(404, 'Renewal ID document file not found.');
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        $filename = $renewal->id_file_name ?: basename($filePath);

        return response()->file($fullPath, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Admin approves renewal request & confirms appointment (POST /admin/renewals/{id}/approve)
     */
    public function approve(Request $request, $id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $renewal = RenewalRequest::with(['appointment', 'user'])
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        if ($renewal->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'This renewal ID is already ' . $renewal->status . '.'], 422);
        }

        $remarks = $request->input('admin_remarks', 'Renewal ID approved by admin');

        $renewal->update([
            'status'        => 'approved',
            'reviewed_by'   => $admin->id,
            'reviewed_at'   => now(),
            'admin_remarks' => $remarks,
        ]);

        // Send bell notification to user
        try {
            $progLabel = $renewal->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
            $notifType = $renewal->program_type === 'PWD' ? 'pwd' : 'solo_parent';
            DB::table('notifications')->insert([
                'user_id'    => $renewal->user_id,
                'type'       => $notifType,
                'title'      => '✅ ' . $progLabel . ' Renewal ID Approved',
                'body'       => 'Your ' . $progLabel . ' renewal ID has been verified and approved by MSWDO. Your appointment is now awaiting final confirmation.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Renewal ID approval notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Renewal ID approved successfully. You may now review and approve the appointment.'
        ]);
    }

    /**
     * Admin rejects renewal request (POST /admin/renewals/{id}/reject)
     */
    public function reject(Request $request, $id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'admin_remarks' => 'required|string|max:500',
        ]);

        $renewal = RenewalRequest::with('appointment')
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $remarks = $request->input('admin_remarks');

        DB::transaction(function () use ($admin, $renewal, $remarks) {
            $renewal->update([
                'status'        => 'rejected',
                'reviewed_by'   => $admin->id,
                'reviewed_at'   => now(),
                'admin_remarks' => $remarks,
            ]);

            // Reject appointment — held slot is immediately released
            if ($renewal->appointment) {
                $renewal->appointment->update([
                    'status'      => 'rejected',
                    'admin_notes' => $remarks,
                ]);
            }
        });

        // Send email to user
        if ($renewal->appointment) {
            try {
                Mail::to($renewal->user->email)->send(new AppointmentStatusMail($renewal->appointment, 'rejected'));
            } catch (\Exception $e) {
                Log::error('Renewal rejection email failed: ' . $e->getMessage());
            }
        }

        // Send bell notification
        try {
            $progLabel = $renewal->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
            $notifType = $renewal->program_type === 'PWD' ? 'pwd' : 'solo_parent';
            DB::table('notifications')->insert([
                'user_id'    => $renewal->user_id,
                'type'       => $notifType,
                'title'      => '❌ ' . $progLabel . ' Renewal Request Rejected',
                'body'       => 'Your ' . $progLabel . ' renewal request was not approved. Reason: ' . $remarks,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Renewal rejection notification failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Renewal request rejected. The appointment slot has been released.']);
    }
}

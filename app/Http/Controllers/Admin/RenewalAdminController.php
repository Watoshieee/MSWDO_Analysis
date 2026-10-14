<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RenewalRequest;
use App\Models\Appointment;
use App\Mail\AppointmentStatusMail;
use App\Mail\RenewalIdApprovedMail;
use App\Mail\RenewalIdRejectedMail;
use App\Services\OneSignalService;
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

        $progLabel = $renewal->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
        $notifType = $renewal->program_type === 'PWD' ? 'pwd' : 'solo_parent';

        // Determine next step supported by existing workflow
        $appt = $renewal->appointment;
        if ($appt && $appt->status === 'confirmed') {
            $formattedDate = Carbon::parse($appt->appointment_date)->format('F d, Y');
            $formattedTime = Carbon::parse($appt->appointment_time)->format('h:i A');
            $nextStep = "Your renewal appointment is confirmed for {$formattedDate} at {$formattedTime}. Please bring your existing ID card, supporting documents, and appointment slip on your scheduled date.";
        } elseif ($appt) {
            $formattedDate = Carbon::parse($appt->appointment_date)->format('F d, Y');
            $formattedTime = Carbon::parse($appt->appointment_time)->format('h:i A');
            $nextStep = "Your renewal appointment on {$formattedDate} at {$formattedTime} is awaiting final confirmation by the admin. Please keep your existing ID card and documents ready.";
        } else {
            $nextStep = "Please check your portal dashboard for further instructions.";
        }

        // For PWD no-appointment renewal: tell user to proceed with requirements
        if (!$renewal->appointment_id) {
            $nextStep = 'Your PWD ID has been verified! You may now proceed to your PWD application and submit your requirements.';
        }

        $notifTitle = $progLabel . ' Renewal ID Approved';
        $notifBody  = 'Your ' . $progLabel . ' renewal Valid ID has been approved by MSWDO. ' . $nextStep;

        // In-app & Push notification
        try {
            $notifId = DB::table('notifications')->insertGetId([
                'user_id'    => $renewal->user_id,
                'type'       => $notifType,
                'title'      => $notifTitle,
                'body'       => $notifBody,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (class_exists(OneSignalService::class)) {
                OneSignalService::sendPush(
                    $renewal->user_id,
                    $notifTitle,
                    $notifBody,
                    $notifType,
                    $notifId,
                    ['renewal_id' => $renewal->id, 'program_type' => $renewal->program_type]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Renewal ID approval notification failed: ' . $e->getMessage());
        }

        // Email
        if ($renewal->user && !empty($renewal->user->email)) {
            try {
                Mail::to($renewal->user->email)->send(new RenewalIdApprovedMail($renewal));
            } catch (\Throwable $e) {
                Log::error('Renewal ID approval email failed: ' . $e->getMessage());
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Renewal ID approved successfully.']);
        }

        return redirect()->route('admin.requirements')->with('success', 'PWD renewal ID approved. User has been notified.');
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

            // Note: Appointment is preserved (not rejected/cancelled) so user can re-upload a replacement ID
        });

        // Send bell notification to user
        try {
            $progLabel = $renewal->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
            $notifType = $renewal->program_type === 'PWD' ? 'pwd' : 'solo_parent';
            $bodyText = $renewal->appointment_id
                ? 'Your Valid ID was rejected. Reason: ' . $remarks . '. Please upload a new Valid ID. Your existing appointment has been preserved.'
                : 'Your PWD renewal ID was rejected. Reason: ' . $remarks . '. Please upload a new valid PWD ID to resubmit.';
            DB::table('notifications')->insert([
                'user_id'    => $renewal->user_id,
                'type'       => $notifType,
                'title'      => $progLabel . ' Renewal ID Rejected',
                'body'       => $bodyText,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Renewal rejection notification failed: ' . $e->getMessage());
        }

        // Send rejection email
        $renewal->load('user');
        if ($renewal->user && !empty($renewal->user->email)) {
            try {
                Mail::to($renewal->user->email)->send(new RenewalIdRejectedMail($renewal));
            } catch (\Throwable $e) {
                Log::error('Renewal ID rejection email failed: ' . $e->getMessage());
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Renewal ID rejected. User has been notified.']);
        }

        return redirect()->route('admin.requirements')->with('success', 'PWD renewal ID rejected. User has been notified.');
    }

    /**
     * Archive (soft delete) a renewal request (DELETE /admin/renewals/{id}/archive)
     */
    public function archive($id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403);
        }

        $renewal = RenewalRequest::with('user')
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $name = $renewal->user->full_name ?? 'applicant';
        $renewal->delete(); // soft delete via SoftDeletes trait

        return redirect()->route('admin.requirements')
            ->with('success', 'Renewal request for "' . $name . '" has been archived.');
    }

    /**
     * Restore an archived renewal request (PATCH /admin/renewals/{id}/restore)
     */
    public function restore($id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403);
        }

        $renewal = RenewalRequest::onlyTrashed()
            ->with('user')
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $renewal->restore();

        return redirect()->route('admin.requirements')
            ->with('success', 'Renewal request for "' . ($renewal->user->full_name ?? 'applicant') . '" has been restored.');
    }

    /**
     * Permanently delete an archived renewal request (DELETE /admin/renewals/{id}/force-delete)
     */
    public function forceDelete($id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403);
        }

        $renewal = RenewalRequest::onlyTrashed()
            ->with('user')
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $name = $renewal->user->full_name ?? 'applicant';

        if (!empty($renewal->id_file_path)) {
            try {
                Storage::disk('public')->delete($renewal->id_file_path);
            } catch (\Throwable $e) {
                Log::warning('Failed to delete renewal ID file: ' . $e->getMessage());
            }
        }

        $renewal->forceDelete();

        return redirect()->route('admin.requirements')
            ->with('success', 'Renewal request for "' . $name . '" has been permanently deleted.');
    }
}

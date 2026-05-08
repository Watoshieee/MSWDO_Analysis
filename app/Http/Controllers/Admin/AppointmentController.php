<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Application;
use App\Mail\AppointmentStatusMail;
use App\Mail\SoloParentEligibilityMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * List all appointments for admin's municipality (JSON for AJAX embed in requirements page)
     */
    public function index()
    {
        $admin = Auth::user();

        $appointments = Appointment::with('user')
            ->where('municipality', $admin->municipality)
            ->orderByRaw("FIELD(status,'pending','confirmed','validated','rejected','cancelled')")
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return response()->json($appointments->map(function ($a) {
            // Load linked solo parent application for id_status (Solo_Parent only)
            $soloApp = ($a->program_type === 'Solo_Parent' && $a->solo_parent_app_id)
                ? Application::select('id', 'id_status', 'id_ready_at')
                    ->find($a->solo_parent_app_id)
                : null;

            return [
                'id'                 => $a->id,
                'program_type'       => $a->program_type,
                'user_name'          => $a->user?->full_name ?? 'N/A',
                'user_email'         => $a->user?->email ?? '',
                'date'               => $a->appointment_date->format('M d, Y'),
                'day'                => $a->appointment_date->format('l'),
                'time'               => $a->formatted_time,
                'interview_type'     => $a->interview_label,
                'status'             => $a->status,
                'status_badge'       => $a->status_badge,
                'user_notes'         => $a->user_notes ?? '',
                'admin_notes'        => $a->admin_notes ?? '',
                'cancel_reason'      => $a->cancel_reason ?? '',
                'cancellation_status' => $a->cancellation_status ?? null,
                'solo_parent_app_id' => $a->solo_parent_app_id,
                'id_status'          => $soloApp?->id_status ?? null,
                'reschedule_request_date'    => $a->reschedule_date?->format('M d, Y') ?? null,
                'reschedule_request_time'    => $a->reschedule_time ? \Carbon\Carbon::createFromFormat('H:i', $a->reschedule_time)->format('h:i A') : null,
                'reschedule_request_reason'  => $a->reschedule_reason ?? '',
                'reschedule_status'          => $a->reschedule_status ?? null,
                'reschedule_admin_notes'     => $a->reschedule_admin_notes ?? '',
            ];
        }));
    }

    /**
     * Confirm an appointment
     */
    public function confirm(Request $request, $id)
    {
        $admin = Auth::user();
        $appt  = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $appt->update([
            'status'      => 'confirmed',
            'admin_notes' => $request->input('admin_notes'),
        ]);


        // Email user
        try {
            Mail::to($appt->user->email)->send(new AppointmentStatusMail($appt, 'confirmed'));
        } catch (\Exception $e) {
            Log::error('Appointment confirm email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Appointment approved and user notified.']);
    }

    /**
     * Validate an appointment:
     *  - Creates a Solo_Parent Application record
     *  - Creates a FileMonitoring record so requirements can be uploaded
     *  - Updates appointment status to 'validated'
     *  - Emails user that they are eligible
     */
    public function validate(Request $request, $id)
    {
        $admin = Auth::user();
        $appt  = Appointment::with('user')
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->whereIn('program_type', ['Solo_Parent', 'AICS_Medical', 'AICS_Burial'])
            ->where('status', 'confirmed')
            ->firstOrFail();

        // ── Solo Parent: existing behavior ────────────────────────────────
        if ($appt->program_type === 'Solo_Parent') {
            // Prevent duplicate applications
            if ($appt->solo_parent_app_id) {
                return response()->json(['success' => false, 'message' => 'Already validated.'], 422);
            }

            $application = Application::create([
                'user_id'          => $appt->user_id,
                'program_type'     => 'Solo_Parent',
                'municipality'     => $appt->municipality,
                'barangay'         => $appt->user->barangay ?? '',
                'full_name'        => $appt->user->full_name,
                'age'              => $appt->user->age ?? 0,
                'gender'           => null,  // nullable — not collected at appointment booking
                'contact_number'   => $appt->user->mobile_number ?? $appt->user->contact_number ?? '',
                'status'           => 'pending',
                'application_date' => now(),
                'year'             => now()->year,
            ]);

            \App\Models\FileMonitoring::create([
                'application_id' => $application->id,
                'user_id'        => $appt->user_id,
                'municipality'   => $appt->municipality,
                'overall_status' => 'pending',
                'priority'       => 'medium',
            ]);

            $appt->update([
                'status'             => 'validated',
                'solo_parent_app_id' => $application->id,
                'validated_at'       => now(),
                'admin_notes'        => $request->input('admin_notes', $appt->admin_notes),
            ]);

            try {
                Mail::to($appt->user->email)->send(new SoloParentEligibilityMail($appt, $application));
            } catch (\Exception $e) {
                Log::error('Solo Parent eligibility email failed: ' . $e->getMessage());
            }

            return response()->json([
                'success'        => true,
                'message'        => 'Applicant marked eligible. User has been notified and can now submit requirements.',
                'application_id' => $application->id,
            ]);
        }

        // ── AICS: mark validated (document upload gate is handled in Mobile API) ──
        // We do NOT create an Application/FileMonitoring here, because AICS uploads
        // are submitted via the shared MobileApiController which already creates them.
        $appt->update([
            'status'       => 'validated',
            'validated_at' => now(),
            'admin_notes' => $request->input('admin_notes', $appt->admin_notes),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment marked as validated. User can now submit AICS requirements.'
        ]);
    }

    /**
     * Reject an appointment
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['admin_notes' => 'required|string|max:500']);

        $admin = Auth::user();
        $appt  = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $appt->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        // Email user
        try {
            Mail::to($appt->user->email)->send(new AppointmentStatusMail($appt, 'rejected'));
        } catch (\Exception $e) {
            Log::error('Appointment reject email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Appointment rejected and user notified.']);
    }

    // ── Archive (soft delete) ────────────────────────────────────────────────
    public function archive($id)
    {
        $admin = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();
        $appt->delete();
        return response()->json(['success' => true, 'message' => 'Appointment archived.']);
    }

    // ── Restore from archive ─────────────────────────────────────────────────
    public function restore($id)
    {
        $admin = Auth::user();
        $appt = Appointment::onlyTrashed()
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();
        $appt->restore();
        return response()->json(['success' => true, 'message' => 'Appointment restored.']);
    }

    // ── Permanent delete ─────────────────────────────────────────────────────
    public function forceDelete($id)
    {
        $admin = Auth::user();
        $appt = Appointment::onlyTrashed()
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();
        $appt->forceDelete();
        return response()->json(['success' => true, 'message' => 'Appointment permanently deleted.']);
    }

    // ── List archived appointments (JSON) ────────────────────────────────────
    public function archived()
    {
        $admin = Auth::user();
        $appts = Appointment::onlyTrashed()
            ->with('user')
            ->where('municipality', $admin->municipality)
            ->orderBy('deleted_at', 'desc')
            ->get();

        return response()->json($appts->map(fn($a) => [
            'id'             => $a->id,
            'program_type'   => $a->program_type,
            'user_name'      => $a->user?->name ?? '—',
            'user_email'     => $a->user?->email ?? '—',
            'date'           => $a->appointment_date?->format('M d, Y') ?? '—',
            'day'            => $a->appointment_date?->format('l') ?? '',
            'time'           => \Carbon\Carbon::createFromFormat('H:i', $a->appointment_time)->format('h:i A'),
            'interview_type' => $a->interview_label,
            'status_badge'   => $a->status_badge,
            'archived_at'    => $a->deleted_at?->format('M d, Y') ?? '—',
        ]));
    }

    // ── Approve reschedule request ───────────────────────────────────────────
    public function approveReschedule($id)
    {
        $admin = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('reschedule_status', 'pending')
            ->firstOrFail();

        $appt->update([
            'appointment_date'   => $appt->reschedule_date,
            'appointment_time'   => $appt->reschedule_time,
            'status'             => 'approved',
            'reschedule_status'  => 'approved',
            'reschedule_date'    => null,
            'reschedule_time'    => null,
            'reschedule_reason'  => null,
            'admin_notes'        => null, // Clear admin notes to avoid showing "Rescheduled by Admin"
        ]);

        try {
            Mail::to($appt->user->email)->send(new \App\Mail\RescheduleResponseMail($appt, 'approved'));
        } catch (\Exception $e) {
            Log::error('Reschedule approval email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Reschedule approved.']);
    }

    // ── Reject reschedule request ────────────────────────────────────────────
    public function rejectReschedule(Request $request, $id)
    {
        $admin = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('reschedule_status', 'pending')
            ->firstOrFail();

        $appt->update([
            'reschedule_status'      => 'rejected',
            'reschedule_admin_notes' => $request->input('admin_notes', ''),
            'reschedule_date'        => null,
            'reschedule_time'        => null,
            'reschedule_reason'      => null,
        ]);

        try {
            Mail::to($appt->user->email)->send(new \App\Mail\RescheduleResponseMail($appt, 'rejected'));
        } catch (\Exception $e) {
            Log::error('Reschedule rejection email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Reschedule rejected.']);
    }

    // ── Admin direct reschedule ──────────────────────────────────────────────
    public function adminReschedule(Request $request, $id)
    {
        $admin = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $request->validate([
            'new_date'   => 'required|date',
            'new_time'   => 'required|in:' . implode(',', Appointment::availableSlots()),
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $date = $request->new_date;
        if (\Carbon\Carbon::parse($date)->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Date must be a weekday.'], 422);
        }

        if (Appointment::slotCount($date, $request->new_time, $admin->municipality) >= Appointment::maxPerSlot()) {
            return response()->json(['success' => false, 'message' => 'That time slot is full.'], 422);
        }

        // Build admin notes to indicate reschedule
        $adminNotes = $request->input('admin_notes', '');
        if (empty($adminNotes)) {
            $adminNotes = 'Rescheduled by admin';
        }

        // Keep current status if confirmed/approved, otherwise set to confirmed
        $newStatus = in_array($appt->status, ['confirmed', 'approved', 'validated']) ? $appt->status : 'confirmed';

        $appt->update([
            'appointment_date' => $date,
            'appointment_time' => $request->new_time,
            'admin_notes'      => $adminNotes,
            'status'           => $newStatus,
        ]);

        try {
            Mail::to($appt->user->email)->send(new \App\Mail\AdminRescheduleMail($appt));
        } catch (\Exception $e) {
            Log::error('Admin reschedule email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Appointment rescheduled by admin.']);
    }

    // ── Approve cancellation request ─────────────────────────────────────────
    public function approveCancellation($id)
    {
        $admin = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('cancellation_status', 'pending')
            ->firstOrFail();

        $appt->update([
            'status'               => 'cancelled',
            'cancellation_status'  => 'approved',
        ]);

        try {
            Mail::to($appt->user->email)->send(new \App\Mail\CancellationResponseMail($appt, 'approved'));
        } catch (\Exception $e) {
            Log::error('Cancellation approval email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Cancellation approved.']);
    }

    // ── Reject cancellation request ──────────────────────────────────────────
    public function rejectCancellation(Request $request, $id)
    {
        $admin = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('cancellation_status', 'pending')
            ->firstOrFail();

        $appt->update([
            'cancellation_status'      => 'rejected',
            'cancellation_admin_notes' => $request->input('admin_notes', ''),
            'cancel_reason'            => null,
            'cancellation_requested_at' => null,
        ]);

        try {
            Mail::to($appt->user->email)->send(new \App\Mail\CancellationResponseMail($appt, 'rejected'));
        } catch (\Exception $e) {
            Log::error('Cancellation rejection email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Cancellation rejected.']);
    }
}

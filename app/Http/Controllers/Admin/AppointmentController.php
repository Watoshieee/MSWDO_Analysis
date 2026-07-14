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

            // Load linked AICS application for id_status (AICS_Medical / AICS_Burial)
            $aicsApp = in_array($a->program_type, ['AICS_Medical', 'AICS_Burial'])
                ? Application::select('id', 'id_status')
                    ->where('user_id', $a->user_id)
                    ->where('program_type', $a->program_type)
                    ->latest('id')
                    ->first()
                : null;

            $idStatus = $soloApp?->id_status ?? $aicsApp?->id_status ?? null;

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
                'aics_app_id'        => $aicsApp?->id,
                'id_status'          => $idStatus,
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

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'solo_parent',
                'title'      => '✅ Appointment Confirmed',
                'body'       => 'Your ' . str_replace('_', ' ', $appt->program_type) . ' appointment on ' . \Carbon\Carbon::parse($appt->appointment_date)->format('F d, Y') . ' has been confirmed by the admin.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Appointment confirm bell notification failed: ' . $e->getMessage());
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

        /** @var \App\Models\Appointment $appt */

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

            // Bell notification for user
            try {
                \DB::table('notifications')->insert([
                    'user_id'    => $appt->user_id,
                    'type'       => 'solo_parent',
                    'title'      => '🏆 You Are Eligible for Solo Parent ID',
                    'body'       => 'Congratulations! You passed the eligibility check. Please log in and submit your requirements to proceed.',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error('Solo Parent eligibility bell notification failed: ' . $e->getMessage());
            }

            return response()->json([
                'success'        => true,
                'message'        => 'Applicant marked eligible. User has been notified and can now submit requirements.',
                'application_id' => $application->id,
            ]);
        }

        // ── AICS: mark validated ──────────────────────────────────────────────
        $appt->update([
            'status'       => 'validated',
            'validated_at' => now(),
            'admin_notes'  => $request->input('admin_notes', $appt->admin_notes),
        ]);

        $aicsLabel = $appt->program_type === 'AICS_Burial' ? 'AICS Burial Assistance' : 'AICS Medical Assistance';

        // Email user
        try {
            $application = Application::where('user_id', $appt->user_id)
                ->where('program_type', $appt->program_type)
                ->latest('id')
                ->first();
            if ($appt->user && $appt->user->email) {
                Mail::to($appt->user->email)->send(new \App\Mail\AicsStatusMail(
                    $application ?? new Application(['program_type' => $appt->program_type, 'municipality' => $appt->municipality, 'full_name' => $appt->user->full_name]),
                    $appt->user,
                    'validated'
                ));
            }
        } catch (\Exception $e) {
            Log::error('AICS validated email failed: ' . $e->getMessage());
        }

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'aics',
                'title'      => '🏆 Eligible for ' . $aicsLabel,
                'body'       => 'Congratulations! Your eligibility assessment is complete. Please log in and submit your requirements to proceed with your ' . $aicsLabel . ' application.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('AICS validated bell notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment marked as validated. User has been notified and can now submit AICS requirements.'
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

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'solo_parent',
                'title'      => '❌ Appointment Rejected',
                'body'       => 'Your ' . str_replace('_', ' ', $appt->program_type) . ' appointment has been rejected.' . ($appt->admin_notes ? ' Reason: ' . $appt->admin_notes : ''),
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Appointment reject bell notification failed: ' . $e->getMessage());
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
            'status'             => 'confirmed',
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

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'solo_parent',
                'title'      => '🔄 Reschedule Request Approved',
                'body'       => 'Your reschedule request has been approved. Your appointment is now on ' . \Carbon\Carbon::parse($appt->appointment_date)->format('F d, Y') . '.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Reschedule approval bell notification failed: ' . $e->getMessage());
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

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'solo_parent',
                'title'      => '🔄 Reschedule Request Rejected',
                'body'       => 'Your reschedule request was not approved.' . ($appt->reschedule_admin_notes ? ' Reason: ' . $appt->reschedule_admin_notes : '') . ' Your original appointment remains.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Reschedule rejection bell notification failed: ' . $e->getMessage());
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
            'new_date'    => 'required|date|after:today',
            'new_time'    => 'required|in:' . implode(',', Appointment::availableSlots()),
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $date   = $request->new_date;
        $parsed = \Carbon\Carbon::parse($date);

        // Must be a weekday
        if ($parsed->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Reschedule date must be a weekday (Mon–Fri).'], 422);
        }

        // Must not be more than 2 months from today
        if ($parsed->isAfter(now()->addMonths(2))) {
            return response()->json(['success' => false, 'message' => 'Reschedule date cannot be more than 2 months from today.'], 422);
        }

        if (Appointment::slotCount($date, $request->new_time, $admin->municipality) >= Appointment::maxPerSlot()) {
            return response()->json(['success' => false, 'message' => 'That time slot is full.'], 422);
        }

        // Build admin notes to indicate reschedule
        $adminNotes = $request->input('admin_notes', '');
        if (empty($adminNotes)) {
            $adminNotes = 'Rescheduled by admin';
        }

        // Preserve the appointment status — do NOT auto-confirm a pending appointment.
        // Only keep validated/confirmed if already there; pending stays pending.
        $allowedStatuses = ['pending', 'confirmed', 'validated'];
        $newStatus = in_array($appt->status, $allowedStatuses) ? $appt->status : 'pending';

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

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'solo_parent',
                'title'      => '🔄 Appointment Rescheduled by Admin',
                'body'       => 'The admin has rescheduled your appointment to ' . \Carbon\Carbon::parse($appt->appointment_date)->format('F d, Y') . '. Please check your updated appointment details.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Admin reschedule bell notification failed: ' . $e->getMessage());
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

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'solo_parent',
                'title'      => '🚫 Cancellation Approved',
                'body'       => 'Your appointment cancellation request has been approved. Your appointment has been cancelled.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Cancellation approval bell notification failed: ' . $e->getMessage());
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

        // Bell notification for user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $appt->user_id,
                'type'       => 'solo_parent',
                'title'      => '🚫 Cancellation Request Rejected',
                'body'       => 'Your appointment cancellation request was not approved.' . ($appt->cancellation_admin_notes ? ' Reason: ' . $appt->cancellation_admin_notes : '') . ' Your appointment remains active.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Cancellation rejection bell notification failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Cancellation rejected.']);
    }
}

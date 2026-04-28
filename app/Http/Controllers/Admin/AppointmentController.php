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
            ->where('program_type', 'Solo_Parent')
            ->orderByRaw("FIELD(status,'pending','confirmed','validated','rejected','cancelled')")
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return response()->json($appointments->map(function ($a) {
            // Load linked solo parent application for id_status
            $soloApp = $a->solo_parent_app_id
                ? Application::select('id', 'id_status', 'id_ready_at')
                    ->find($a->solo_parent_app_id)
                : null;

            return [
                'id'                 => $a->id,
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
                'solo_parent_app_id' => $a->solo_parent_app_id,
                'id_status'          => $soloApp?->id_status ?? null,
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
            ->where('program_type', 'Solo_Parent')
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

        return response()->json(['success' => true, 'message' => 'Appointment confirmed and user notified.']);
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
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'confirmed')
            ->firstOrFail();

        // Prevent duplicate applications
        if ($appt->solo_parent_app_id) {
            return response()->json(['success' => false, 'message' => 'Already validated.'], 422);
        }

        // Create the Solo Parent Application record
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

        // Create FileMonitoring so requirements can be uploaded immediately
        \App\Models\FileMonitoring::create([
            'application_id' => $application->id,
            'user_id'        => $appt->user_id,
            'municipality'   => $appt->municipality,
            'overall_status' => 'pending',
            'priority'       => 'medium',
        ]);

        // Update the appointment
        $appt->update([
            'status'             => 'validated',
            'solo_parent_app_id' => $application->id,
            'validated_at'       => now(),
            'admin_notes'        => $request->input('admin_notes', $appt->admin_notes),
        ]);

        // Email user — eligibility confirmed + requirements link
        try {
            Mail::to($appt->user->email)->send(new SoloParentEligibilityMail($appt, $application));
        } catch (\Exception $e) {
            Log::error('Solo Parent eligibility email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success'        => true,
            'message'        => 'Appointment validated. User has been notified and can now submit requirements.',
            'application_id' => $application->id,
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
            ->where('program_type', 'Solo_Parent')
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
            ->where('program_type', 'Solo_Parent')
            ->orderBy('deleted_at', 'desc')
            ->get();

        return response()->json($appts->map(fn($a) => [
            'id'             => $a->id,
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
}

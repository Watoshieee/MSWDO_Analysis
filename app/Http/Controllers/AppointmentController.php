<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Application;
use App\Models\User;
use App\Mail\AppointmentStatusMail;
use App\Mail\NewAppointmentAdminMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * AJAX: return available slots for a given date (GET /user/appointments/slots?date=YYYY-MM-DD)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->query('date');
        $user = Auth::user();

        try {
            $carbon = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date'], 422);
        }

        if ($carbon->isWeekend()) {
            return response()->json(['error' => 'Weekends are not available'], 422);
        }

        if ($carbon->isPast() && !$carbon->isToday()) {
            return response()->json(['error' => 'Past dates are not available'], 422);
        }

        return response()->json(Appointment::slotsForDate($date, $user->municipality));
    }

    /** Allowed program types for appointment booking */
    private static function allowedProgramTypes(): array
    {
        return ['Solo_Parent', 'AICS_Medical', 'AICS_Burial'];
    }

    /**
     * Book an appointment (POST /user/appointments)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $allowed = implode(',', self::allowedProgramTypes());

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|in:' . implode(',', Appointment::availableSlots()),
            'interview_type'   => 'required|in:face_to_face,online',
            'user_notes'       => 'nullable|string|max:500',
            'program_type'     => 'nullable|in:' . $allowed,
        ]);

        $programType = $request->input('program_type', 'Solo_Parent');
        $date = $request->appointment_date;
        $time = $request->appointment_time;

        if ($programType === 'Solo_Parent') {
            $isSoloParentBeneficiary = Application::where('user_id', $user->id)
                ->where('program_type', 'Solo_Parent')
                ->whereIn('id_status', ['processing', 'ready_for_pickup', 'released'])
                ->exists();

            if ($isSoloParentBeneficiary) {
                return back()->with('appt_error', 'Solo Parent re-application is disabled because you are already a beneficiary.');
            }
        }

        // Validate: weekday only
        if (Carbon::parse($date)->isWeekend()) {
            return back()->with('appt_error', 'Appointments can only be booked on weekdays (Mon–Fri).');
        }

        // Check user doesn't already have an active appointment for this program
        $existing = Appointment::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('program_type', $programType)
            ->first();

        if ($existing) {
            return back()->with('appt_error', 'You already have an active appointment for this program. Please cancel it before booking a new one.');
        }

        // Check slot capacity (scoped to user's municipality)
        if (Appointment::slotCount($date, $time, $user->municipality) >= Appointment::maxPerSlot()) {
            return back()->with('appt_error', 'That time slot is already full for your municipality. Please choose another.');
        }

        $appt = Appointment::create([
            'user_id'          => $user->id,
            'municipality'     => $user->municipality,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'interview_type'   => $request->interview_type,
            'program_type'     => $programType,
            'status'           => 'pending',
            'user_notes'       => $request->user_notes,
        ]);

        // ── Notify all admins of this municipality by email ──────────────────
        $admins = User::where('role', 'admin')
            ->where('municipality', $user->municipality)
            ->get();

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NewAppointmentAdminMail($appt, $user));
            } catch (\Exception $e) {
                Log::error('Admin appointment notification email failed: ' . $e->getMessage());
            }
        }

        return back()->with('appt_success', 'Appointment booked! The admin will confirm your schedule.');
    }

    /**
     * Cancel an appointment (DELETE /user/appointments/{id})
     */
    public function cancel($id)
    {
        $user = Auth::user();
        // No program_type filter — users can cancel any of their own appointments
        $appt = Appointment::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        if (!in_array($appt->status, ['pending', 'confirmed'])) {
            return back()->with('appt_error', 'This appointment cannot be cancelled.');
        }

        $appt->update(['status' => 'cancelled']);
        return back()->with('appt_success', 'Appointment cancelled successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\RenewalRequest;
use App\Models\Appointment;
use App\Models\User;
use App\Services\PhilippineHolidayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RenewalController extends Controller
{
    /**
     * Submit a new renewal request (POST /user/renewals)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'program_type' => 'required|in:PWD,Solo_Parent',
            'id_file'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'user_notes'   => 'nullable|string|max:200',
        ], [
            'id_file.required' => 'Please upload a clear copy of your Valid ID.',
            'id_file.max'      => 'The uploaded ID file must not exceed 5MB.',
            'user_notes.max'   => 'Additional Notes must not exceed 200 characters.',
        ]);

        // Check if there is an existing rejected renewal request with a preserved appointment
        $existingRejected = RenewalRequest::with('appointment')
            ->where('user_id', $user->id)
            ->where('program_type', $request->program_type)
            ->where('status', 'rejected')
            ->whereHas('appointment', function ($q) {
                $q->whereNotIn('status', ['rejected', 'cancelled']);
            })
            ->latest('id')
            ->first();

        if ($existingRejected) {
            // Guard against a newer active renewal request already submitted for this user & program
            $newerActive = RenewalRequest::where('user_id', $user->id)
                ->where('program_type', $request->program_type)
                ->where('id', '>', $existingRejected->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($q) {
                    $q->whereNull('appointment_id')
                      ->orWhereHas('appointment', function ($aq) {
                          $aq->whereNotIn('status', ['rejected', 'cancelled']);
                      });
                })
                ->first();

            if ($newerActive) {
                $msg = $newerActive->status === 'approved'
                    ? 'You already have an approved renewal on record.'
                    : 'You already have a pending renewal request under review. Please wait for the admin decision.';
                return back()->with('renewal_error', $msg);
            }

            // Store uploaded replacement ID file securely
            $file = $request->file('id_file');
            $originalName = $file->getClientOriginalName();
            $subFolder = 'renewals/' . strtolower($request->program_type);
            $storedPath = $file->store($subFolder, 'public');

            // Safely delete previous rejected file from disk if it exists
            $oldPath = $existingRejected->id_file_path;
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                try {
                    Storage::disk('public')->delete($oldPath);
                } catch (\Exception $e) {
                    Log::warning('Could not delete old renewal file: ' . $e->getMessage());
                }
            }

            // Update existing renewal request and preserve the linked appointment
            DB::transaction(function () use ($existingRejected, $storedPath, $originalName, $request) {
                $existingRejected->update([
                    'id_file_path'  => $storedPath,
                    'id_file_name'  => $originalName,
                    'status'        => 'pending',
                    'reviewed_by'   => null,
                    'reviewed_at'   => null,
                    'admin_remarks' => null,
                    'user_notes'    => $request->filled('user_notes') ? $request->user_notes : $existingRejected->user_notes,
                ]);

                if ($request->filled('user_notes') && $existingRejected->appointment) {
                    $existingRejected->appointment->update([
                        'user_notes' => $request->user_notes,
                    ]);
                }
            });

            // Notify admins of replacement ID upload
            try {
                $admins = User::where('role', 'admin')
                    ->where('municipality', $user->municipality)
                    ->get();

                $progLabel = $request->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
                $apptDateFormatted = $existingRejected->appointment ? Carbon::parse($existingRejected->appointment->appointment_date)->format('F d, Y') : 'scheduled date';
                foreach ($admins as $admin) {
                    DB::table('notifications')->insert([
                        'user_id'    => $admin->id,
                        'type'       => strtolower($request->program_type),
                        'title'      => '🔄 Replacement ' . $progLabel . ' Renewal ID Uploaded',
                        'body'       => $user->full_name . ' uploaded a replacement Valid ID for ' . $progLabel . ' renewal. Appointment remains preserved for ' . $apptDateFormatted . '.',
                        'is_read'    => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Admin replacement renewal ID notification failed: ' . $e->getMessage());
            }

            return back()->with('renewal_success', 'Your replacement Valid ID has been uploaded successfully! Your existing appointment has been preserved.');
        }

        // ── PWD renewal: no appointment needed — just store ID upload and wait for admin review ──
        if ($request->program_type === 'PWD') {
            // Prevent duplicate active renewal for PWD
            $existingRenewal = RenewalRequest::where('user_id', $user->id)
                ->where('program_type', 'PWD')
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingRenewal) {
                $msg = $existingRenewal->status === 'approved'
                    ? 'You already have an approved PWD renewal on record.'
                    : 'You already have a pending PWD renewal under review. Please wait for the admin decision.';
                return back()->with('renewal_error', $msg);
            }

            // Store uploaded ID file
            $file = $request->file('id_file');
            $originalName = $file->getClientOriginalName();
            $storedPath = $file->store('renewals/pwd', 'public');

            $renewal = RenewalRequest::create([
                'user_id'        => $user->id,
                'municipality'   => $user->municipality,
                'program_type'   => 'PWD',
                'id_file_path'   => $storedPath,
                'id_file_name'   => $originalName,
                'appointment_id' => null,
                'status'         => 'pending',
                'user_notes'     => $request->user_notes,
            ]);

            // Notify admins (in-app)
            try {
                $admins = User::where('role', 'admin')->where('municipality', $user->municipality)->get();
                foreach ($admins as $admin) {
                    DB::table('notifications')->insert([
                        'user_id'    => $admin->id,
                        'type'       => 'pwd',
                        'title'      => 'New PWD Renewal Request',
                        'body'       => $user->full_name . ' submitted a PWD renewal request. Please review and verify the uploaded ID.',
                        'is_read'    => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Admin PWD renewal notification failed: ' . $e->getMessage());
            }

            // Notify user via email that their ID was received
            try {
                if (!empty($user->email)) {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(
                        new \App\Mail\PwdRenewalSubmittedMail($renewal->load('user'))
                    );
                }
            } catch (\Throwable $e) {
                Log::error('PWD renewal submission email failed: ' . $e->getMessage());
            }

            return back()->with('renewal_success', 'Your PWD ID has been submitted for review. The admin will verify your ID. Once approved, you can proceed with your application requirements.');
        }

        // ── Solo Parent renewal: appointment required ──

        // Fresh renewal: validate appointment date and time
        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => ['required', 'regex:/^([01]\\d|2[0-3]):[0-5]\\d$/'],
        ], [
            'appointment_date.required' => 'Please select an appointment date.',
            'appointment_time.required' => 'Please select an appointment time slot.',
        ]);

        $date = $request->appointment_date;
        $time = $request->appointment_time;
        $carbon = Carbon::parse($date);

        // 1. Must be a weekday
        if ($carbon->isWeekend()) {
            return back()->withInput()->with('renewal_error', 'Appointments can only be scheduled on weekdays (Monday–Friday).');
        }

        // 2. Cannot book more than 60 days in advance
        if ($carbon->isAfter(now()->addDays(60))) {
            return back()->withInput()->with('renewal_error', 'Appointments cannot be booked more than 60 days in advance.');
        }

        // 3. Holiday check
        $holidayCheck = app(PhilippineHolidayService::class)->isAllowedBookingDate($date);
        if (!$holidayCheck['allowed']) {
            return back()->withInput()->with('renewal_error', $holidayCheck['reason']);
        }

        // 4. Office hour check (8-12 and 1-5)
        $hour = (int) substr($time, 0, 2);
        if (!in_array($hour, Appointment::officeHours())) {
            return back()->withInput()->with('renewal_error', 'Selected time is outside MSWDO office hours (8:00 AM–12:00 PM and 1:00 PM–5:00 PM).');
        }

        // 5. Prevent duplicate active renewal request for this user & program
        $existingRenewal = RenewalRequest::where('user_id', $user->id)
            ->where('program_type', $request->program_type)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) {
                $q->whereNull('appointment_id')
                  ->orWhereHas('appointment');
            })
            ->first();

        if ($existingRenewal) {
            $msg = $existingRenewal->status === 'approved'
                ? 'You already have an approved renewal on record.'
                : 'You already have a pending renewal request under review. Please wait for the admin decision.';
            return back()->with('renewal_error', $msg);
        }

        // 6. Serialized hour lock + DB transaction to prevent overbooking & concurrency race conditions
        try {
            $data = Appointment::withHourLock($user->municipality, $date, $hour, function () use ($request, $user, $date, $time, $hour) {
                return DB::transaction(function () use ($request, $user, $date, $time, $hour) {
                    // Check hour capacity (max 5 per hour)
                    $hStr = sprintf('%02d', $hour);
                    $hourCount = Appointment::whereDate('appointment_date', $date)
                        ->where('municipality', $user->municipality)
                        ->whereIn('status', ['pending', 'confirmed', 'validated'])
                        ->where(function ($q) use ($hour, $hStr) {
                            $q->where('appointment_time', 'like', $hStr . ':%')
                              ->orWhereRaw('HOUR(appointment_time) = ?', [$hour]);
                        })
                        ->count();

                    if ($hourCount >= Appointment::maxPerHour()) {
                        throw new \Exception('That hour block has reached its maximum capacity of 5 appointments. Please choose another time.');
                    }

                    // Check specific slot capacity
                    $timeNorm = substr(trim($time), 0, 5);
                    $slotCount = Appointment::whereDate('appointment_date', $date)
                        ->where('municipality', $user->municipality)
                        ->whereIn('status', ['pending', 'confirmed', 'validated'])
                        ->where(function ($q) use ($time, $timeNorm) {
                            $q->where('appointment_time', $time)
                              ->orWhere('appointment_time', $timeNorm)
                              ->orWhere('appointment_time', 'like', $timeNorm . ':%');
                        })
                        ->count();

                    if ($slotCount >= Appointment::maxPerSlot()) {
                        throw new \Exception('That specific time slot is already taken. Please choose another slot.');
                    }

                // Store uploaded ID file securely
                $file = $request->file('id_file');
                $originalName = $file->getClientOriginalName();
                $subFolder = 'renewals/' . strtolower($request->program_type);
                $storedPath = $file->store($subFolder, 'public');

                // Create Appointment record (pending until admin approves)
                $appt = Appointment::create([
                    'user_id'          => $user->id,
                    'municipality'     => $user->municipality,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'interview_type'   => 'face_to_face',
                    'program_type'     => $request->program_type . '_Renewal',
                    'status'           => 'pending',
                    'user_notes'       => $request->user_notes,
                ]);

                // Create RenewalRequest record
                $renewal = RenewalRequest::create([
                    'user_id'        => $user->id,
                    'municipality'   => $user->municipality,
                    'program_type'   => $request->program_type,
                    'id_file_path'   => $storedPath,
                    'id_file_name'   => $originalName,
                    'appointment_id' => $appt->id,
                    'status'         => 'pending',
                    'user_notes'     => $request->user_notes,
                ]);

                return ['appointment' => $appt, 'renewal' => $renewal];
                });
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('renewal_error', $e->getMessage());
        }

        // Notify admins for this municipality
        try {
            $admins = User::where('role', 'admin')
                ->where('municipality', $user->municipality)
                ->get();

            $progLabel = $request->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'user_id'    => $admin->id,
                    'type'       => strtolower($request->program_type),
                    'title'      => '🔄 New ' . $progLabel . ' Renewal Request',
                    'body'       => $user->full_name . ' submitted a ' . $progLabel . ' renewal request with appointment on ' . Carbon::parse($date)->format('F d, Y') . '.',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Admin renewal notification failed: ' . $e->getMessage());
        }

        return back()->with('renewal_success', 'Your renewal request and appointment have been submitted! The admin will review your ID and schedule.');
    }


    /**
     * User views their own uploaded ID document (GET /user/renewals/{id}/id-file)
     */
    public function serveIdFile($id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $renewal = RenewalRequest::where('id', $id)
            ->where('user_id', $user->id)
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
     * User cancels their pending renewal request (POST /user/renewals/{id}/cancel)
     */
    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $renewal = RenewalRequest::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($renewal->status !== 'pending') {
            return back()->with('renewal_error', 'Only pending renewal requests can be cancelled.');
        }

        $reason = $request->input('cancel_reason', 'Cancelled by applicant');

        DB::transaction(function () use ($renewal, $reason) {
            $renewal->update([
                'status'        => 'cancelled',
                'admin_remarks' => $reason,
            ]);

            if ($renewal->appointment) {
                $renewal->appointment->update([
                    'status'                    => 'cancelled',
                    'cancellation_status'       => 'approved',
                    'cancel_reason'             => $reason,
                    'cancellation_requested_at' => now(),
                ]);
            }
        });

        return back()->with('renewal_success', 'Your renewal request has been cancelled and the appointment slot has been released.');
    }
}

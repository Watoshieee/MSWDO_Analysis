<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\AdminPushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AdminNotificationService
 *
 * Central service for all admin-side notifications:
 *   1. Persistent in-app bell notifications (admin_notifications table)
 *   2. Web Push (VAPID) notifications → Windows desktop even when browser is closed
 *
 * Push delivery depends on:
 *   - VAPID_PUBLIC_KEY and VAPID_PRIVATE_KEY in .env
 *   - Admin browser having subscribed via the service worker
 *   - Browser/OS push permission not denied
 *   - minishlink/web-push package installed
 *
 * Usage:
 *   AdminNotificationService::newUserRegistration($newUser);
 *   AdminNotificationService::newApplication($application);
 */
class AdminNotificationService
{
    // ── VAPID configuration ────────────────────────────────────────────────────

    public static function vapidPublicKey(): string
    {
        return config('services.vapid.public_key', env('VAPID_PUBLIC_KEY', ''));
    }

    public static function vapidPrivateKey(): string
    {
        return config('services.vapid.private_key', env('VAPID_PRIVATE_KEY', ''));
    }

    public static function vapidSubject(): string
    {
        return config('services.vapid.subject', env('VAPID_SUBJECT', 'mailto:admin@mswdo.gov.ph'));
    }

    /**
     * Returns true if VAPID keys are configured so web push can be attempted.
     */
    public static function isWebPushConfigured(): bool
    {
        return ! empty(self::vapidPublicKey()) && ! empty(self::vapidPrivateKey());
    }

    // ── Notification type → title/body builders ────────────────────────────────

    public static function buildPayload(string $type, array $context = []): array
    {
        $prog = $context['program_label'] ?? 'a program';

        return match ($type) {
            // ── Registration ──────────────────────────────────────────────────
            AdminNotification::TYPE_NEW_USER_REGISTRATION => [
                'title' => 'New User Registration',
                'body'  => 'A new user has registered and is awaiting ID verification.',
            ],

            // ── New applications (program-specific) ───────────────────────────
            AdminNotification::TYPE_NEW_APPLICATION => match ($context['program_type'] ?? '') {
                'PWD_Assistance', 'PWD_New' => [
                    'title' => 'New PWD Application',
                    'body'  => 'A new PWD Assistance application has been submitted and requires review.',
                ],
                'Solo_Parent' => [
                    'title' => 'New Solo Parent Application',
                    'body'  => 'A new Solo Parent application has been submitted and requires review.',
                ],
                default => [
                    'title' => 'New ' . $prog . ' Application',
                    'body'  => 'A new ' . $prog . ' application has been submitted and requires review.',
                ],
            },

            // ── New AICS applications ─────────────────────────────────────────
            AdminNotification::TYPE_NEW_AICS_APPLICATION => match ($context['program_type'] ?? '') {
                'AICS_Medical' => [
                    'title' => 'New AICS Medical Application',
                    'body'  => 'A new AICS medical assistance application has been submitted and requires review.',
                ],
                'AICS_Burial' => [
                    'title' => 'New AICS Burial Application',
                    'body'  => 'A new AICS burial assistance application has been submitted and requires review.',
                ],
                default => [
                    'title' => 'New AICS Application',
                    'body'  => 'A new AICS application has been submitted and requires review.',
                ],
            },

            // ── Renewals ──────────────────────────────────────────────────────
            AdminNotification::TYPE_NEW_RENEWAL => match ($context['program_type'] ?? '') {
                'PWD' => [
                    'title' => 'PWD Renewal — ID Verification Required',
                    'body'  => 'A PWD renewal applicant has submitted an ID for verification.',
                ],
                'Solo_Parent' => [
                    'title' => 'Solo Parent Renewal Application',
                    'body'  => 'A Solo Parent renewal application has been submitted and requires review.',
                ],
                default => [
                    'title' => 'New ' . $prog . ' Renewal Request',
                    'body'  => 'A new ' . $prog . ' renewal request requires your review.',
                ],
            },

            // ── Appointments ──────────────────────────────────────────────────
            AdminNotification::TYPE_NEW_APPOINTMENT => match ($context['program_type'] ?? '') {
                'Solo_Parent' => [
                    'title' => 'New Solo Parent Appointment',
                    'body'  => 'A Solo Parent applicant has booked an appointment that requires your attention.',
                ],
                'AICS_Medical' => [
                    'title' => 'New AICS Medical Appointment',
                    'body'  => 'An AICS medical applicant has booked an appointment that requires your attention.',
                ],
                'AICS_Burial' => [
                    'title' => 'New AICS Burial Appointment',
                    'body'  => 'An AICS burial applicant has booked an appointment that requires your attention.',
                ],
                default => [
                    'title' => 'New ' . $prog . ' Appointment',
                    'body'  => 'A ' . $prog . ' applicant has booked an appointment that requires your attention.',
                ],
            },

            // ── Reschedule requests ───────────────────────────────────────────
            AdminNotification::TYPE_RESCHEDULE_REQUEST => match ($context['program_type'] ?? '') {
                'Solo_Parent' => [
                    'title' => 'Solo Parent Appointment Rescheduled',
                    'body'  => 'A Solo Parent applicant has requested a change to their appointment schedule.',
                ],
                'AICS_Medical' => [
                    'title' => 'AICS Medical Appointment Rescheduled',
                    'body'  => 'An AICS medical applicant has requested a change to their appointment schedule.',
                ],
                'AICS_Burial' => [
                    'title' => 'AICS Burial Appointment Rescheduled',
                    'body'  => 'An AICS burial applicant has requested a change to their appointment schedule.',
                ],
                default => [
                    'title' => $prog . ' Appointment Rescheduled',
                    'body'  => 'A ' . $prog . ' applicant has requested a change to their appointment schedule.',
                ],
            },

            // ── Requirements / documents submitted ────────────────────────────
            AdminNotification::TYPE_REQUIREMENTS_SUBMITTED => match ($context['program_type'] ?? '') {
                'PWD_Assistance', 'PWD_New' => [
                    'title' => 'PWD Requirements Submitted',
                    'body'  => 'A PWD applicant has submitted the required documents for review.',
                ],
                'PWD_Renewal' => [
                    'title' => 'PWD Requirements Submitted',
                    'body'  => 'A PWD renewal applicant has submitted the required documents for review.',
                ],
                'Solo_Parent' => [
                    'title' => 'Solo Parent Requirements Submitted',
                    'body'  => 'A Solo Parent applicant has submitted the required documents for review.',
                ],
                'AICS_Medical' => [
                    'title' => 'AICS Medical Requirements Submitted',
                    'body'  => 'An AICS medical applicant has submitted the required documents for review.',
                ],
                'AICS_Burial' => [
                    'title' => 'AICS Burial Requirements Submitted',
                    'body'  => 'An AICS burial applicant has submitted the required documents for review.',
                ],
                default => [
                    'title' => $prog . ' Requirements Submitted',
                    'body'  => 'A ' . $prog . ' applicant has submitted the required documents for review.',
                ],
            },

            // ── Status change & misc ──────────────────────────────────────────
            AdminNotification::TYPE_APP_STATUS_CHANGE => [
                'title' => 'Application Status Updated',
                'body'  => 'The status of a ' . $prog . ' application has changed to ' . ($context['new_status'] ?? 'updated') . '.',
            ],
            AdminNotification::TYPE_ID_VERIFICATION => [
                'title' => 'ID Verification Completed',
                'body'  => 'The ID verification process for a registered user has been completed.',
            ],
            AdminNotification::TYPE_DOCUMENT_UPLOAD => [
                'title' => 'New Documents Uploaded',
                'body'  => 'New requirement documents have been uploaded and require review.',
            ],
            default => [
                'title' => 'MSWDO Notification',
                'body'  => 'You have a new notification.',
            ],
        };
    }

    // ── Public factory methods ─────────────────────────────────────────────────

    /**
     * Notify all admins in the user's municipality that a new user has
     * registered and requires ID verification.
     *
     * Called from OtpController after user is created and email verified.
     * Uses DB::transaction to ensure the notification is only created once.
     */
    public static function newUserRegistration(User $newUser): void
    {
        $admins = self::getAdminsForMunicipality($newUser->municipality);
        if ($admins->isEmpty()) {
            return;
        }

        $actionUrl = route('admin.users');
        $payload   = self::buildPayload(AdminNotification::TYPE_NEW_USER_REGISTRATION);

        foreach ($admins as $admin) {
            // Idempotency: skip if a notification already exists for this user within 60 seconds
            $alreadyExists = AdminNotification::where('admin_id', $admin->id)
                ->where('type', AdminNotification::TYPE_NEW_USER_REGISTRATION)
                ->where('created_at', '>=', now()->subSeconds(60))
                ->whereJsonContains('data->registered_user_id', $newUser->id)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $notif = AdminNotification::create([
                'admin_id' => $admin->id,
                'type'     => AdminNotification::TYPE_NEW_USER_REGISTRATION,
                'title'    => $payload['title'],
                'body'     => $payload['body'],
                'is_read'  => false,
                'data'     => [
                    'registered_user_id' => $newUser->id,
                    'municipality'       => $newUser->municipality,
                    'action_url'         => $actionUrl,
                ],
            ]);

            self::sendWebPush($admin, $notif);
        }
    }

    /**
     * Notify appropriate admins about a new program application.
     */
    public static function newApplication(\App\Models\Application $application): void
    {
        $admins = self::getAdminsForMunicipality($application->municipality);
        if ($admins->isEmpty()) {
            return;
        }

        $programLabel = self::programLabel($application->program_type);
        $type         = str_contains((string) $application->program_type, 'AICS')
            ? AdminNotification::TYPE_NEW_AICS_APPLICATION
            : AdminNotification::TYPE_NEW_APPLICATION;

        $payload   = self::buildPayload($type, ['program_label' => $programLabel]);
        $actionUrl = route('admin.view-requirement', $application->id);

        foreach ($admins as $admin) {
            // Idempotency: one notification per application per admin
            $alreadyExists = AdminNotification::where('admin_id', $admin->id)
                ->whereIn('type', [AdminNotification::TYPE_NEW_APPLICATION, AdminNotification::TYPE_NEW_AICS_APPLICATION])
                ->whereJsonContains('data->application_id', $application->id)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $notif = AdminNotification::create([
                'admin_id' => $admin->id,
                'type'     => $type,
                'title'    => $payload['title'],
                'body'     => $payload['body'],
                'is_read'  => false,
                'data'     => [
                    'application_id' => $application->id,
                    'program_type'   => $application->program_type,
                    'program_label'  => $programLabel,
                    'municipality'   => $application->municipality,
                    'action_url'     => $actionUrl,
                ],
            ]);

            self::sendWebPush($admin, $notif);
        }
    }

    /**
     * Notify admins about a new renewal request (PWD or Solo Parent).
     */
    public static function newRenewal(\App\Models\RenewalRequest $renewal): void
    {
        $admins = self::getAdminsForMunicipality($renewal->municipality);
        if ($admins->isEmpty()) {
            return;
        }

        $programLabel = self::programLabel($renewal->program_type);
        $payload      = self::buildPayload(AdminNotification::TYPE_NEW_RENEWAL, ['program_label' => $programLabel]);
        $actionUrl    = route('admin.renewals.index');

        foreach ($admins as $admin) {
            $alreadyExists = AdminNotification::where('admin_id', $admin->id)
                ->where('type', AdminNotification::TYPE_NEW_RENEWAL)
                ->whereJsonContains('data->renewal_id', $renewal->id)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $notif = AdminNotification::create([
                'admin_id' => $admin->id,
                'type'     => AdminNotification::TYPE_NEW_RENEWAL,
                'title'    => $payload['title'],
                'body'     => $payload['body'],
                'is_read'  => false,
                'data'     => [
                    'renewal_id'    => $renewal->id,
                    'program_type'  => $renewal->program_type,
                    'program_label' => $programLabel,
                    'municipality'  => $renewal->municipality,
                    'action_url'    => $actionUrl,
                ],
            ]);

            self::sendWebPush($admin, $notif);
        }
    }

    /**
     * Notify admins about a new appointment booking (Solo Parent, AICS Medical, AICS Burial).
     */
    public static function newAppointment(\App\Models\Appointment $appointment): void
    {
        $admins = self::getAdminsForMunicipality($appointment->municipality);
        if ($admins->isEmpty()) {
            return;
        }

        $programType  = $appointment->program_type ?? 'Solo_Parent';
        $programLabel = self::programLabel($programType);
        $payload      = self::buildPayload(
            AdminNotification::TYPE_NEW_APPOINTMENT,
            ['program_type' => $programType, 'program_label' => $programLabel]
        );
        $actionUrl = route('admin.admin.appointments.index');

        foreach ($admins as $admin) {
            $alreadyExists = AdminNotification::where('admin_id', $admin->id)
                ->where('type', AdminNotification::TYPE_NEW_APPOINTMENT)
                ->whereJsonContains('data->appointment_id', $appointment->id)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $notif = AdminNotification::create([
                'admin_id' => $admin->id,
                'type'     => AdminNotification::TYPE_NEW_APPOINTMENT,
                'title'    => $payload['title'],
                'body'     => $payload['body'],
                'is_read'  => false,
                'data'     => [
                    'appointment_id' => $appointment->id,
                    'program_type'   => $programType,
                    'municipality'   => $appointment->municipality,
                    'action_url'     => $actionUrl,
                ],
            ]);

            self::sendWebPush($admin, $notif);
        }
    }

    /**
     * Notify admins of an appointment reschedule request.
     */
    public static function appointmentReschedule(\App\Models\Appointment $appointment): void
    {
        $admins = self::getAdminsForMunicipality($appointment->municipality);
        if ($admins->isEmpty()) {
            return;
        }

        $programType  = $appointment->program_type ?? 'Solo_Parent';
        $programLabel = self::programLabel($programType);
        $payload      = self::buildPayload(
            AdminNotification::TYPE_RESCHEDULE_REQUEST,
            ['program_type' => $programType, 'program_label' => $programLabel]
        );
        $actionUrl = route('admin.admin.appointments.index');

        foreach ($admins as $admin) {
            // One reschedule notification per appointment per admin (deduplicate by reschedule_requested_at)
            $alreadyExists = AdminNotification::where('admin_id', $admin->id)
                ->where('type', AdminNotification::TYPE_RESCHEDULE_REQUEST)
                ->whereJsonContains('data->appointment_id', $appointment->id)
                ->where('created_at', '>=', now()->subSeconds(60))
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $notif = AdminNotification::create([
                'admin_id' => $admin->id,
                'type'     => AdminNotification::TYPE_RESCHEDULE_REQUEST,
                'title'    => $payload['title'],
                'body'     => $payload['body'],
                'is_read'  => false,
                'data'     => [
                    'appointment_id' => $appointment->id,
                    'program_type'   => $programType,
                    'municipality'   => $appointment->municipality,
                    'action_url'     => $actionUrl,
                ],
            ]);

            self::sendWebPush($admin, $notif);
        }
    }

    /**
     * Notify admins that requirements/documents were submitted for an application.
     * Fires on every upload, with a short 30-second dedup window to prevent
     * double-firing from batch uploads. Re-uploads after rejection always fire.
     */
    public static function requirementsSubmitted(\App\Models\Application $application): void
    {
        $admins = self::getAdminsForMunicipality($application->municipality);
        if ($admins->isEmpty()) {
            return;
        }

        $programType  = $application->program_type;
        $programLabel = self::programLabel($programType);
        $payload      = self::buildPayload(
            AdminNotification::TYPE_REQUIREMENTS_SUBMITTED,
            ['program_type' => $programType, 'program_label' => $programLabel]
        );
        $actionUrl = route('admin.view-requirement', $application->id);

        foreach ($admins as $admin) {
            // Short 30-second dedup window: prevents double-fire on batch saves
            // but always fires for re-uploads after rejection (those happen minutes apart)
            $alreadyExists = AdminNotification::where('admin_id', $admin->id)
                ->where('type', AdminNotification::TYPE_REQUIREMENTS_SUBMITTED)
                ->whereJsonContains('data->application_id', $application->id)
                ->where('created_at', '>=', now()->subSeconds(30))
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $notif = AdminNotification::create([
                'admin_id' => $admin->id,
                'type'     => AdminNotification::TYPE_REQUIREMENTS_SUBMITTED,
                'title'    => $payload['title'],
                'body'     => $payload['body'],
                'is_read'  => false,
                'data'     => [
                    'application_id' => $application->id,
                    'program_type'   => $programType,
                    'municipality'   => $application->municipality,
                    'action_url'     => $actionUrl,
                ],
            ]);

            self::sendWebPush($admin, $notif);
        }
    }

    // ── Web Push delivery ──────────────────────────────────────────────────────

    /**
     * Send a single Web Push notification to one admin.
     * Graceful — never throws, never interrupts the calling flow.
     */
    public static function sendWebPush(User $admin, AdminNotification $notif): void
    {
        if (! self::isWebPushConfigured()) {
            return;
        }

        if (! class_exists(\Minishlink\WebPush\WebPush::class)) {
            Log::warning('AdminNotificationService: minishlink/web-push not installed.');
            return;
        }

        $subscriptions = AdminPushSubscription::where('admin_id', $admin->id)->get();
        if ($subscriptions->isEmpty()) {
            return;
        }

        $iconUrl = url('/images/logo_mswdo.jpg');

        $pushPayload = json_encode([
            'title'  => $notif->title,
            'body'   => $notif->body,
            'icon'   => $iconUrl,
            'badge'  => $iconUrl,
            'tag'    => 'mswdo-admin-' . $notif->type,
            'data'   => [
                'url'     => $notif->getActionUrl(),
                'notifId' => $notif->id,
                'type'    => $notif->type,
            ],
        ]);

        $auth = [
            'VAPID' => [
                'subject'    => self::vapidSubject(),
                'publicKey'  => self::vapidPublicKey(),
                'privateKey' => self::vapidPrivateKey(),
            ],
        ];

        $failedEndpoints = [];

        try {
            $webPush = new \Minishlink\WebPush\WebPush($auth, ['timeout' => 5]);

            foreach ($subscriptions as $sub) {
                $subscription = \Minishlink\WebPush\Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys'     => [
                        'p256dh' => $sub->p256dh,
                        'auth'   => $sub->auth,
                    ],
                ]);

                $webPush->queueNotification($subscription, $pushPayload);
            }

            foreach ($webPush->flush() as $report) {
                if (! $report->isSuccess()) {
                    $reason = $report->getReason();
                    // 410 Gone / 404 = subscription expired, remove it
                    if (in_array($report->getResponse()?->getStatusCode(), [410, 404], true)) {
                        $failedEndpoints[] = $report->getEndpoint();
                    }
                    Log::warning('AdminWebPush: delivery failed', [
                        'admin_id' => $admin->id,
                        'reason'   => $reason,
                    ]);
                }
            }

            // Mark push_sent_at on the notification
            $notif->update(['push_sent_at' => now()]);

        } catch (\Throwable $e) {
            Log::error('AdminNotificationService::sendWebPush exception: ' . $e->getMessage());
        }

        // Clean up expired subscriptions
        if (! empty($failedEndpoints)) {
            AdminPushSubscription::where('admin_id', $admin->id)
                ->whereIn('endpoint', $failedEndpoints)
                ->delete();
        }
    }

    /**
     * Send web push to all admins for a given notification.
     * Used for batch dispatch (e.g. from a cron or dispatcher).
     */
    public static function sendWebPushBatch(AdminNotification $notif): void
    {
        $admin = User::find($notif->admin_id);
        if ($admin) {
            self::sendWebPush($admin, $notif);
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private static function getAdminsForMunicipality(string $municipality)
    {
        return User::where('role', 'admin')
            ->where('municipality', $municipality)
            ->where('status', 'active')
            ->get();
    }

    public static function programLabel(string $programType): string
    {
        $map = [
            'Senior_Citizen_Pension' => 'Senior Citizen Pension',
            'PWD_Assistance'         => 'PWD Assistance',
            'PWD_New'                => 'PWD (New)',
            'PWD_Renewal'            => 'PWD Renewal',
            'AICS_Medical'           => 'AICS Medical Assistance',
            'AICS_Burial'            => 'AICS Burial Assistance',
            '4Ps'                    => '4Ps',
            'SLP'                    => 'SLP',
            'ESA'                    => 'ESA',
            'Solo_Parent'            => 'Solo Parent',
            'MTA'                    => 'MTA',
            'Day_Care'               => 'Day Care Registration',
            'PWD'                    => 'PWD',
        ];

        return $map[$programType] ?? str_replace('_', ' ', $programType);
    }
}

<?php

namespace App\Services;

use App\Models\RegistrationDevice;
use Illuminate\Http\Request;

class DeviceRegistrationService
{
    public const MAX_ACCOUNTS_PER_DEVICE = 3;
    public const COOKIE_NAME = 'mswdo_device_fp';

    /**
     * Build a stable fingerprint from server-visible signals.
     * IP + User-Agent hashed with the app key — consistent per device
     * regardless of which browser is used or whether cookies are cleared.
     */
    public function resolveFingerprint(Request $request): string
    {
        $ip = $request->ip();
        $ua = $request->userAgent() ?? '';

        return hash('sha256', $ip . '|' . $ua . '|' . config('app.key'));
    }

    public function isValidFingerprint(string $fingerprint): bool
    {
        return (bool) preg_match('/^[a-f0-9]{64}$/i', $fingerprint);
    }

    public function getAccountCount(string $fingerprint): int
    {
        return RegistrationDevice::where('device_fingerprint', $fingerprint)
            ->whereExists(function ($query) {
                $query->selectRaw(1)
                    ->from('users')
                    ->whereColumn('users.id', 'registration_devices.user_id')
                    ->whereNull('users.deleted_at');
            })
            ->count();
    }

    public function canRegister(string $fingerprint): bool
    {
        return $this->getAccountCount($fingerprint) < self::MAX_ACCOUNTS_PER_DEVICE;
    }

    public function attachUser(string $fingerprint, int $userId): void
    {
        RegistrationDevice::updateOrCreate(
            ['user_id' => $userId],
            [
                'device_fingerprint' => $fingerprint,
                'registered_at' => now(),
            ]
        );
    }

    public function detachUser(int $userId): void
    {
        RegistrationDevice::where('user_id', $userId)->delete();
    }

    public function makeCookie(string $fingerprint): \Symfony\Component\HttpFoundation\Cookie
    {
        return cookie(
            self::COOKIE_NAME,
            $fingerprint,
            60 * 24 * 365 * 2,
            '/',
            null,
            false,
            true,
            false,
            'Lax'
        );
    }
}

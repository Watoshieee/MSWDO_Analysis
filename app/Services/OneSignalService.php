<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * OneSignal Immediate Push Notification Service
 *
 * Dispatches push notifications immediately to mobile devices via OneSignal REST API.
 * Ensures notifications arrive within 1-3 seconds instead of waiting for cron intervals.
 * Marks push_sent_at on the existing notification record so push_dispatcher.php does not duplicate.
 */
class OneSignalService
{
    private const API_URL = 'https://api.onesignal.com/notifications';
    private const DEFAULT_APP_ID = '3db6828d-49af-4f5a-8d89-ff0b90749aec';
    private const ANDROID_CHANNEL_ID = 'mswdo_updates';
    private const ANDROID_ACCENT_COLOR = 'FF1A5276';
    private const TIMEOUT_SECONDS = 3;

    /**
     * Send immediate push notification to a single user.
     *
     * @param int|string $userId Laravel user ID (matches OneSignal external_id)
     * @param string $title Notification title
     * @param string $body Notification message body
     * @param string $type Notification type (e.g. 'solo_parent', 'pwd', 'aics', 'announcement')
     * @param int|null $notificationId ID of the already created row in notifications table
     * @param array $extraData Additional payload data
     * @return bool True if OneSignal accepted the push, false otherwise
     */
    public static function sendPush(
        int|string $userId,
        string $title,
        string $body,
        string $type = 'general',
        ?int $notificationId = null,
        array $extraData = []
    ): bool {
        $appId  = config('services.onesignal.app_id', env('ONESIGNAL_APP_ID', self::DEFAULT_APP_ID));
        $apiKey = config('services.onesignal.api_key', env('ONESIGNAL_API_KEY', ''));

        if (empty($apiKey)) {
            Log::warning('OneSignalService: Missing ONESIGNAL_API_KEY. Push skipped.');
            return false;
        }

        $cleanTitle = self::cleanEmojiTitle($title);

        $payload = [
            'app_id'               => $appId,
            'target_channel'       => 'push',
            'include_aliases'      => ['external_id' => [(string) $userId]],
            'headings'             => ['en' => $cleanTitle],
            'contents'             => ['en' => $body],
            'data'                 => array_merge([
                'type'            => $type,
                'notification_id' => $notificationId,
            ], $extraData),
            'android_channel_id'   => self::ANDROID_CHANNEL_ID,
            'priority'             => 10,
            'ios_badgeType'        => 'Increase',
            'ios_badgeCount'       => 1,
            'android_accent_color' => self::ANDROID_ACCENT_COLOR,
        ];

        $success = self::executeRequest($payload, $apiKey);

        // If push succeeded and we have a database notification ID, mark push_sent_at
        // so push_dispatcher.php does not duplicate it!
        if ($success && $notificationId) {
            try {
                DB::table('notifications')
                    ->where('id', $notificationId)
                    ->update(['push_sent_at' => now()]);
            } catch (\Throwable $e) {
                // Ignore gracefully if column does not exist or DB connection is transient
            }
        }

        return $success;
    }

    /**
     * Send immediate push notification to all users or users within a specific municipality.
     *
     * @param string $title
     * @param string $body
     * @param string $type
     * @param string|null $municipality Optional municipality filter (null or 'all' sends to all)
     * @param array $extraData
     * @return bool
     */
    public static function sendToAll(
        string $title,
        string $body,
        string $type = 'announcement',
        ?string $municipality = null,
        array $extraData = []
    ): bool {
        $appId  = config('services.onesignal.app_id', env('ONESIGNAL_APP_ID', self::DEFAULT_APP_ID));
        $apiKey = config('services.onesignal.api_key', env('ONESIGNAL_API_KEY', ''));

        if (empty($apiKey)) {
            Log::warning('OneSignalService: Missing ONESIGNAL_API_KEY. Push skipped.');
            return false;
        }

        $cleanTitle = self::cleanEmojiTitle($title);

        $payload = [
            'app_id'               => $appId,
            'target_channel'       => 'push',
            'headings'             => ['en' => $cleanTitle],
            'contents'             => ['en' => $body],
            'data'                 => array_merge(['type' => $type], $extraData),
            'android_channel_id'   => self::ANDROID_CHANNEL_ID,
            'priority'             => 10,
            'ios_badgeType'        => 'Increase',
            'ios_badgeCount'       => 1,
            'android_accent_color' => self::ANDROID_ACCENT_COLOR,
        ];

        // If specific municipality, target only users in that municipality via external_id
        if ($municipality && strtolower($municipality) !== 'all') {
            try {
                $userIds = User::where('role', 'user')
                    ->where('municipality', $municipality)
                    ->pluck('id')
                    ->map(fn($id) => (string) $id)
                    ->toArray();

                if (empty($userIds)) {
                    return true; // No users in this municipality, nothing to push
                }

                $payload['include_aliases'] = ['external_id' => $userIds];
            } catch (\Throwable $e) {
                Log::error('OneSignalService: Failed to query users for municipality', ['error' => $e->getMessage()]);
                $payload['included_segments'] = ['Subscribed Users'];
            }
        } else {
            // All users broadcast
            $payload['included_segments'] = ['Subscribed Users'];
        }

        return self::executeRequest($payload, $apiKey);
    }

    /**
     * Executes non-blocking cURL request to OneSignal REST API.
     */
    private static function executeRequest(array $payload, string $apiKey): bool
    {
        try {
            $ch = curl_init(self::API_URL);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Key ' . $apiKey,
                ],
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_TIMEOUT        => self::TIMEOUT_SECONDS,
                CURLOPT_CONNECTTIMEOUT => 2,
            ]);

            $response  = curl_exec($ch);
            $curlError = curl_error($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($curlError) {
                Log::warning('OneSignalService cURL error: ' . $curlError);
                return false;
            }

            if ($httpCode !== 200 && $httpCode !== 201) {
                Log::warning('OneSignalService HTTP error', [
                    'status'   => $httpCode,
                    'response' => $response,
                ]);
                return false;
            }

            $decoded = json_decode($response, true);
            return isset($decoded['id']);
        } catch (\Throwable $e) {
            Log::error('OneSignalService exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Strips corrupted UTF-8 mojibake while preserving standard emojis and readable text.
     */
    private static function cleanEmojiTitle(string $title): string
    {
        $clean = str_replace(['o.', '?O', '?3'], '', $title);
        return trim($clean);
    }
}

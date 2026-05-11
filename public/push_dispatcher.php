<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 *  MSWDO OneSignal Push Notification Dispatcher
 *  File: public/push_dispatcher.php
 *
 *  HOW IT WORKS
 *  ─────────────────────────────────────────────────────────────────
 *  This script runs as a cron job on Hostinger every minute:
 *
 *    * * * * * php /path/to/your/public/push_dispatcher.php >> /dev/null 2>&1
 *
 *  It queries the existing `notifications` table for rows that have
 *  NOT yet been pushed (push_sent_at IS NULL) and calls the OneSignal
 *  REST API to deliver a real push notification to the user's device.
 *
 *  IMPORTANT CONSTRAINTS
 *  ─────────────────────────────────────────────────────────────────
 *  • Does NOT modify any admin or backend notification logic.
 *  • Does NOT change how notifications are created — only reads them.
 *  • Only adds a `push_sent_at` column to track dispatch state.
 *
 *  SETUP STEPS (run once)
 *  ─────────────────────────────────────────────────────────────────
 *  1. Upload this file to: /public_html/push_dispatcher.php
 *  2. Run the migration SQL below in phpMyAdmin (or Artisan):
 *       ALTER TABLE notifications ADD COLUMN push_sent_at TIMESTAMP NULL DEFAULT NULL;
 *  3. Set up cron in Hostinger panel:
 *       php /home/u123456789/domains/laguna.mswdo.org/public_html/push_dispatcher.php
 *  4. Done — push notifications will fire within 1 minute of creation.
 * ═══════════════════════════════════════════════════════════════════
 */

// ── Security: block direct browser access ───────────────────────────────────
if (PHP_SAPI !== 'cli' && !isset($_GET['cron_key'])) {
    http_response_code(403);
    exit('Forbidden');
}
if (isset($_GET['cron_key']) && $_GET['cron_key'] !== 'REPLACE_WITH_SECURE_RANDOM_KEY') {
    http_response_code(403);
    exit('Invalid key');
}

// ── Configuration ────────────────────────────────────────────────────────────
define('ONESIGNAL_APP_ID',   '3db6828d-49af-4f5a-8d89-ff0b90749aec');
define('ONESIGNAL_API_KEY',  'REPLACE_WITH_YOUR_ONESIGNAL_REST_API_KEY');  // From OneSignal Dashboard → Settings → Keys
define('ONESIGNAL_API_URL',  'https://onesignal.com/api/v1/notifications');
define('BATCH_SIZE', 50); // Max notifications to process per run

// ── Database connection ──────────────────────────────────────────────────────
// Load from Laravel's .env so we don't hardcode credentials
$envPath = __DIR__ . '/../.env';
$env = [];
if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
    }
}

$dbHost = $env['DB_HOST']     ?? '127.0.0.1';
$dbPort = $env['DB_PORT']     ?? '3306';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
        $dbUser, $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log('[PushDispatcher] DB connection failed: ' . $e->getMessage());
    exit(1);
}

// ── Ensure push_sent_at column exists (idempotent) ───────────────────────────
try {
    $pdo->exec("ALTER TABLE notifications ADD COLUMN IF NOT EXISTS push_sent_at TIMESTAMP NULL DEFAULT NULL");
} catch (PDOException $e) {
    // Column may already exist — that's fine
}

// ── Fetch undelivered notifications ─────────────────────────────────────────
$stmt = $pdo->prepare("
    SELECT n.id, n.user_id, n.title, n.body, n.type
    FROM   notifications n
    WHERE  n.push_sent_at IS NULL
    ORDER  BY n.created_at ASC
    LIMIT  :limit
");
$stmt->bindValue(':limit', BATCH_SIZE, PDO::PARAM_INT);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($notifications)) {
    echo '[PushDispatcher] No pending notifications.' . PHP_EOL;
    exit(0);
}

echo '[PushDispatcher] Found ' . count($notifications) . ' pending notification(s).' . PHP_EOL;

// ── Group by user_id for batch efficiency ────────────────────────────────────
$byUser = [];
foreach ($notifications as $n) {
    $byUser[$n['user_id']][] = $n;
}

// ── Send each notification via OneSignal REST API ────────────────────────────
$sentIds = [];

foreach ($byUser as $userId => $items) {
    foreach ($items as $notif) {
        $success = sendOneSignalPush(
            userId:  (string) $userId,
            title:   $notif['title'],
            body:    $notif['body'],
            type:    $notif['type'],
            notifId: $notif['id']
        );

        if ($success) {
            $sentIds[] = $notif['id'];
            echo "[PushDispatcher] Sent #{$notif['id']} → user {$userId}: {$notif['title']}" . PHP_EOL;
        } else {
            echo "[PushDispatcher] FAILED #{$notif['id']} → user {$userId}" . PHP_EOL;
        }
    }
}

// ── Mark sent notifications ──────────────────────────────────────────────────
if (!empty($sentIds)) {
    $placeholders = implode(',', array_fill(0, count($sentIds), '?'));
    $update = $pdo->prepare(
        "UPDATE notifications SET push_sent_at = NOW() WHERE id IN ($placeholders)"
    );
    $update->execute($sentIds);
    echo '[PushDispatcher] Marked ' . count($sentIds) . ' as sent.' . PHP_EOL;
}

// ── OneSignal REST API call ──────────────────────────────────────────────────
function sendOneSignalPush(string $userId, string $title, string $body, string $type, int $notifId): bool
{
    // Target by external_user_id (= Laravel user.id) set by the Flutter app on login
    $payload = [
        'app_id'            => ONESIGNAL_APP_ID,
        'target_channel'    => 'push',
        'include_aliases'   => ['external_id' => [$userId]],
        'headings'          => ['en' => $title],
        'contents'          => ['en' => $body],
        'data'              => ['type' => $type, 'notification_id' => $notifId],
        'android_channel_id'=> 'mswdo_updates',
        // Android notification priority
        'priority'          => 10,
        // Badge increment (iOS)
        'ios_badgeType'     => 'Increase',
        'ios_badgeCount'    => 1,
        // Small icon color (matches AC.primary #1A5276)
        'android_accent_color' => 'FF1A5276',
    ];

    $ch = curl_init(ONESIGNAL_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Key ' . ONESIGNAL_API_KEY,
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_TIMEOUT        => 15,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        error_log("[PushDispatcher] OneSignal HTTP $httpCode: $response");
        return false;
    }

    $decoded = json_decode($response, true);
    // OneSignal returns {"id": "...", "recipients": N}
    // recipients == 0 means device is not subscribed yet (not an error)
    return isset($decoded['id']);
}

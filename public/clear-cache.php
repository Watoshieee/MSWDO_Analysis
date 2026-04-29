<?php
/**
 * TEMPORARY cache-clearing script for Hostinger deployment.
 * UPLOAD THIS TO: public_html/public/clear-cache.php
 * RUN IT ONCE AT: https://mswdo.org/clear-cache.php
 * DELETE IT AFTER USE.
 */

// Simple security token — change this before uploading
$token = $_GET['token'] ?? '';
if ($token !== 'mswdo-clear-2026') {
    die('Unauthorized. Add ?token=mswdo-clear-2026 to the URL.');
}

$basePath = dirname(__DIR__); // public_html/

echo '<pre>';
echo "Base path: {$basePath}\n\n";

// 1. Clear route cache
$routeFiles = glob($basePath . '/bootstrap/cache/routes*.php') ?: [];
foreach ($routeFiles as $file) {
    if (unlink($file)) {
        echo "✓ Deleted route cache: " . basename($file) . "\n";
    } else {
        echo "✗ Could not delete: " . basename($file) . "\n";
    }
}
if (empty($routeFiles)) {
    echo "ℹ No route cache files found (good — not cached).\n";
}

// 2. Clear config cache
$configCache = $basePath . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    unlink($configCache) ? print("✓ Deleted config cache\n") : print("✗ Could not delete config cache\n");
} else {
    echo "ℹ No config cache found.\n";
}

// 3. Clear compiled views
$viewCacheDir = $basePath . '/storage/framework/views';
$viewFiles = glob($viewCacheDir . '/*.php') ?: [];
$count = 0;
foreach ($viewFiles as $vf) {
    if (unlink($vf)) $count++;
}
echo "✓ Cleared {$count} compiled views\n";

// 4. Show resolved paths for diagnosis
echo "\n--- PATH DIAGNOSIS ---\n";
echo "base_path()    : {$basePath}\n";
echo "storage/app/public exists: " . (is_dir($basePath . '/storage/app/public') ? 'YES' : 'NO') . "\n";
echo "public/storage exists: " . (file_exists($basePath . '/public/storage') ? 'YES (symlink or dir)' : 'NO') . "\n";

// 5. Show a sample file_upload path if DB is reachable
echo "\n--- CHECKING FILE UPLOAD PATH ---\n";
try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    $fu = \App\Models\FileUpload::whereNotNull('file_path')->latest()->first();
    if ($fu) {
        $fp = $fu->file_path;
        echo "Latest file_path in DB: {$fp}\n";
        echo "Disk path: " . \Illuminate\Support\Facades\Storage::disk('public')->path($fp) . "\n";
        echo "file_exists via disk:   " . (\Illuminate\Support\Facades\Storage::disk('public')->exists($fp) ? 'YES ✓' : 'NO ✗') . "\n";
        echo "file_exists via base_path: " . (file_exists($basePath . '/storage/app/public/' . $fp) ? 'YES ✓' : 'NO ✗') . "\n";
    } else {
        echo "No file_upload records with file_path found.\n";
    }

    // List registered routes containing 'serve'
    echo "\n--- SERVE ROUTES ---\n";
    $routes = app('router')->getRoutes();
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'serve') || str_contains($route->uri(), 'files')) {
            echo "ROUTE: [{$route->methods()[0]}] /{$route->uri()} → " . $route->getActionName() . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error during bootstrap: " . $e->getMessage() . "\n";
}

echo "\n--- DONE ---\n";
echo 'DELETE this file now! Visit: <a href="/clear-cache.php">Reload</a> after deleting.' . "\n";
echo '</pre>';

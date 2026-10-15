<?php

// Send bootstrap failures to function logs without exposing them to visitors.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', '/dev/stderr');
error_reporting(E_ALL);

// Vercel's application bundle is read-only; Laravel writes compiled views
// and other temporary files under the function's writable /tmp directory.
$storagePath = sys_get_temp_dir().'/psctv-storage';
foreach (['framework/cache/data', 'framework/sessions', 'framework/views', 'bootstrap-cache', 'logs'] as $directory) {
    $path = $storagePath.'/'.$directory;
    if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
        throw new RuntimeException('Unable to create Laravel runtime directory.');
    }
}

// The public portfolio preview must work without a provisioned database.
// These can still be overridden by Vercel environment variables.
foreach (['SESSION_DRIVER' => 'cookie', 'CACHE_STORE' => 'array', 'QUEUE_CONNECTION' => 'sync', 'LOG_CHANNEL' => 'stderr', 'APP_DEBUG' => 'false'] as $name => $default) {
    if (getenv($name) === false) {
        $_ENV[$name] = $default;
        $_SERVER[$name] = $default;
        putenv($name.'='.$default);
    }
}

// Provider manifests also need writable paths before Laravel can boot.
foreach (['APP_PACKAGES_CACHE' => 'packages.php', 'APP_SERVICES_CACHE' => 'services.php', 'APP_CONFIG_CACHE' => 'config.php', 'APP_ROUTES_CACHE' => 'routes.php', 'APP_EVENTS_CACHE' => 'events.php'] as $name => $file) {
    $path = $storagePath.'/bootstrap-cache/'.$file;
    $_ENV[$name] = $path;
    $_SERVER[$name] = $path;
    putenv($name.'='.$path);
}

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
putenv('LARAVEL_STORAGE_PATH='.$storagePath);
$_ENV['VIEW_COMPILED_PATH'] = $storagePath.'/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $storagePath.'/framework/views';
putenv('VIEW_COMPILED_PATH='.$storagePath.'/framework/views');

try {
    require __DIR__.'/../public/index.php';
} catch (Throwable $exception) {
    // Keep the first failure visible even when Laravel's error renderer fails.
    for ($cause = $exception; $cause !== null; $cause = $cause->getPrevious()) {
        error_log('PSCTV bootstrap: '.get_class($cause).': '.$cause->getMessage());
    }
    http_response_code(500);
    echo 'The service is temporarily unavailable.';
}



<?php

// Vercel's application bundle is read-only; Laravel writes compiled views
// and other temporary files under the function's writable /tmp directory.
$storagePath = sys_get_temp_dir().'/psctv-storage';
foreach (['framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $directory) {
    $path = $storagePath.'/'.$directory;
    if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
        throw new RuntimeException('Unable to create Laravel runtime directory.');
    }
}

// The public portfolio preview must work without a provisioned database.
// These can still be overridden by Vercel environment variables.
foreach (['SESSION_DRIVER' => 'cookie', 'CACHE_STORE' => 'array', 'QUEUE_CONNECTION' => 'sync'] as $name => $default) {
    if (getenv($name) === false) {
        $_ENV[$name] = $default;
        $_SERVER[$name] = $default;
        putenv($name.'='.$default);
    }
}

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
putenv('LARAVEL_STORAGE_PATH='.$storagePath);
$_ENV['VIEW_COMPILED_PATH'] = $storagePath.'/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $storagePath.'/framework/views';
putenv('VIEW_COMPILED_PATH='.$storagePath.'/framework/views');

require __DIR__.'/../public/index.php';

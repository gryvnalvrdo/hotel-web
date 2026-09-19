<?php
// api/index.php

// Create necessary Laravel storage directories in /tmp
$dirs = [
    '/tmp/app',
    '/tmp/framework/cache/data',
    '/tmp/framework/sessions',
    '/tmp/framework/testing',
    '/tmp/framework/views',
    '/tmp/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Copy SQLite database to /tmp to avoid read-only filesystem errors
if (!file_exists('/tmp/database.sqlite')) {
    copy(__DIR__ . '/../database/database.sqlite', '/tmp/database.sqlite');
}

// Set custom paths for Laravel cache files in /tmp
$cacheFiles = [
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
];

foreach ($cacheFiles as $key => $path) {
    $_ENV[$key] = $path;
    $_SERVER[$key] = $path;
    putenv("$key=$path");
}

// Override DB path for Laravel
$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
$_SERVER['DB_DATABASE'] = '/tmp/database.sqlite';
putenv('DB_DATABASE=/tmp/database.sqlite');

// Hardcode critical env vars to prevent Vercel empty string errors
$defaultEnvs = [
    'VERCEL' => '1',
    'DB_CONNECTION' => 'sqlite',
    'SESSION_LIFETIME' => '120',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'LOG_CHANNEL' => 'stderr',
    'CACHE_DRIVER' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'QUEUE_CONNECTION' => 'sync',
];

foreach ($defaultEnvs as $k => $v) {
    if (empty($_ENV[$k])) {
        $_ENV[$k] = $v;
        $_SERVER[$k] = $v;
        putenv("$k=$v");
    }
}

// Force HTTPS for asset() URL generation (Vercel terminates SSL at the edge)
$_SERVER['HTTPS'] = 'on';

// Hardcode Supabase Database Connection
$_ENV['DB_CONNECTION'] = 'pgsql';
$_SERVER['DB_CONNECTION'] = 'pgsql';
putenv('DB_CONNECTION=pgsql');

$dbUrl = 'postgresql://postgres:alvrdo331851@db.cphrxiakrsbmqkgnxtks.supabase.co:5432/postgres';
$_ENV['DATABASE_URL'] = $dbUrl;
$_SERVER['DATABASE_URL'] = $dbUrl;
putenv("DATABASE_URL=$dbUrl");

require __DIR__ . '/../public/index.php';

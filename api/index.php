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

// Override DB path for Laravel
$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
$_SERVER['DB_DATABASE'] = '/tmp/database.sqlite';
putenv('DB_DATABASE=/tmp/database.sqlite');

// Tell Laravel we are in Vercel
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';
putenv('VERCEL=1');

require __DIR__ . '/../public/index.php';

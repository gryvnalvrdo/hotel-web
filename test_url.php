<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$room = App\Models\Room::with('images')->find(2);
$img = $room->images->first()?->file_path;
$img = preg_replace('/^public\//', '', $img);
$img = ltrim($img, '/');
if (!str_starts_with($img, 'images/') && !str_starts_with($img, 'storage/')) {
    $img = 'images/rooms/' . basename($img);
}
echo asset($img) . "\n";

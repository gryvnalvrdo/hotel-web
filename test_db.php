<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();
\ = DB::table('room_images')->limit(5)->get();
foreach(\ as \) {
    echo \->file_path . \"\n\";
}

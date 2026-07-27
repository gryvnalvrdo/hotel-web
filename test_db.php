<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach ($tables as $table) {
    foreach ($table as $key => $name) {
        if ($name == 'migrations') continue;
        $create = Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE " . $name . "")[0];
        foreach ($create as $k => $v) {
            if ($k == 'Create Table') echo $v . "\n\n";
        }
    }
}

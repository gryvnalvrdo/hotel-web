<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
    }

    
    public function boot(): void
    {
        try {
            if (Schema::hasTable('rooms')) {
                Schema::table('rooms', function (Blueprint $table) {
                    if (!Schema::hasColumn('rooms', 'price')) {
                        $table->unsignedBigInteger('price')->default(1500000);
                    }
                    if (!Schema::hasColumn('rooms', 'capacity')) {
                        $table->unsignedInteger('capacity')->default(2);
                    }
                    if (!Schema::hasColumn('rooms', 'total_rooms')) {
                        $table->unsignedInteger('total_rooms')->default(20);
                    }
                    if (!Schema::hasColumn('rooms', 'short_description')) {
                        $table->text('short_description')->nullable();
                    }
                    if (!Schema::hasColumn('rooms', 'full_description')) {
                        $table->text('full_description')->nullable();
                    }
                    if (!Schema::hasColumn('rooms', 'floor_location')) {
                        $table->string('floor_location')->nullable()->default('Lantai 3 - 8 (Tower Utama)');
                    }
                });
            }

            if (Schema::hasTable('room_facilities')) {
                Schema::table('room_facilities', function (Blueprint $table) {
                    if (!Schema::hasColumn('room_facilities', 'icon')) {
                        $table->string('icon', 100)->nullable()->default('bi bi-check2-circle');
                    }
                });
            }

            if (Schema::hasTable('bookings')) {
                Schema::table('bookings', function (Blueprint $table) {
                    if (!Schema::hasColumn('bookings', 'room_details')) {
                        $table->text('room_details')->nullable();
                    }
                });
                try {
                    \Illuminate\Support\Facades\DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status VARCHAR(50) DEFAULT 'unpaid'");
                } catch (\Throwable $e) {}
            }
        } catch (\Throwable $e) {
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rooms')) {
            Schema::table('rooms', function (Blueprint $table) {
                if (!Schema::hasColumn('rooms', 'price')) {
                    $table->unsignedBigInteger('price')->default(1500000);
                }
                if (!Schema::hasColumn('rooms', 'capacity')) {
                    $table->unsignedInteger('capacity')->default(2);
                }
                if (!Schema::hasColumn('rooms', 'short_description')) {
                    $table->text('short_description')->nullable();
                }
                if (!Schema::hasColumn('rooms', 'full_description')) {
                    $table->text('full_description')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('rooms')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->dropColumn(['price', 'capacity', 'short_description', 'full_description']);
            });
        }
    }
};

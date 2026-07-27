<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom status ke tabel bookings yang sudah ada.
     * status: pending | confirmed | cancelled
     * payment_status: unpaid | paid | refunded
     * midtrans_order_id: ID unik untuk Midtrans
     * midtrans_token: Snap token dari Midtrans
     */
    public function up(): void
    {
        // Hanya tambah kolom jika tabel sudah ada (untuk database lama)
        if (!Schema::hasTable('bookings')) {
            return; // Tabel akan dibuat oleh migration 000002
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                      ->default('pending')
                      ->after('notes');
            }
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])
                      ->default('unpaid')
                      ->after('status');
            }
            if (!Schema::hasColumn('bookings', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'midtrans_token')) {
                $table->string('midtrans_token')->nullable()->after('midtrans_order_id');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('bookings')) {
            return;
        }
        Schema::table('bookings', function (Blueprint $table) {
            $cols = ['status', 'payment_status', 'midtrans_order_id', 'midtrans_token'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('bookings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

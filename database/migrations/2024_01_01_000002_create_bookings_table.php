<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel bookings dari scratch.
     * Tabel ini belum ada di database Web Hotel lama.
     */
    public function up(): void
    {
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('room_id');   // pakai int biasa, sesuai tabel rooms lama
                $table->string('name');
                $table->string('email');
                $table->string('phone', 30);
                $table->date('checkin');
                $table->date('checkout');
                $table->unsignedTinyInteger('guests')->default(1);
                $table->unsignedSmallInteger('nights')->default(1);
                $table->unsignedSmallInteger('room_count')->default(1);
                $table->unsignedBigInteger('total_price')->default(0);
                $table->text('notes')->nullable();
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
                $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
                $table->string('midtrans_order_id')->nullable();
                $table->string('midtrans_token')->nullable();
                $table->timestamps();

                $table->index(['room_id', 'checkin', 'checkout']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

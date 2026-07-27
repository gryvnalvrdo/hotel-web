<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100);
            $table->string('short_description', 255)->nullable();
            $table->integer('capacity')->nullable();
            $table->text('full_description')->nullable();
            $table->unsignedBigInteger('price')->default(1500000);
            $table->unsignedInteger('total_rooms')->default(20);
            $table->string('floor_location', 255)->default('Lantai 3 - 8 (Tower Utama)');
        });

        Schema::create('room_images', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('room_id');
            $table->string('file_path', 255);
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });

        Schema::create('room_facilities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('room_id');
            $table->enum('category', ['utama', 'kamar', 'bathroom']);
            $table->string('facility_name', 100);
            $table->string('icon', 100)->default('bi bi-check2-circle');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });

        Schema::create('featured_rooms', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('room_id');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });

        Schema::create('conference_rooms', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255);
            $table->decimal('width', 6, 2)->nullable();
            $table->decimal('length', 6, 2)->nullable();
            $table->integer('capacity')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('conference_room_images', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('room_id');
            $table->string('image_path', 255);
            $table->integer('display_order')->default(0);
            $table->foreign('room_id')->references('id')->on('conference_rooms')->onDelete('cascade');
        });

        Schema::create('home_slider', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('image_path', 255);
        });

        Schema::create('home_facilities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 100);
            $table->text('description')->nullable();
        });

        Schema::create('home_facility_images', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('facility_id');
            $table->string('image_path', 255);
            $table->foreign('facility_id')->references('id')->on('home_facilities')->onDelete('cascade');
        });

        Schema::create('footer_branding', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('hotel_name', 255);
            $table->text('tagline')->nullable();
        });

        Schema::create('footer_bottom', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('text');
        });

        Schema::create('footer_social', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('platform', 50);
            $table->string('url', 255);
            $table->string('icon_class', 100);
            $table->integer('display_order')->default(0);
        });

        Schema::create('footer_partners', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100);
            $table->string('url', 255);
            $table->string('logo_path', 255);
            $table->integer('display_order')->default(0);
        });

        Schema::create('footer_contact', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('type', 50);
            $table->text('value');
            $table->string('icon_class', 100)->nullable();
            $table->integer('display_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_contact');
        Schema::dropIfExists('footer_partners');
        Schema::dropIfExists('footer_social');
        Schema::dropIfExists('footer_bottom');
        Schema::dropIfExists('footer_branding');
        Schema::dropIfExists('home_facility_images');
        Schema::dropIfExists('home_facilities');
        Schema::dropIfExists('home_slider');
        Schema::dropIfExists('conference_room_images');
        Schema::dropIfExists('conference_rooms');
        Schema::dropIfExists('featured_rooms');
        Schema::dropIfExists('room_facilities');
        Schema::dropIfExists('room_images');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('admins');
    }
};

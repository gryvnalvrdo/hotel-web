<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('special_request')->nullable()->after('status');
            $table->string('promo_code')->nullable()->after('special_request');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('promo_code');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['special_request', 'promo_code', 'discount_amount']);
        });
    }
};

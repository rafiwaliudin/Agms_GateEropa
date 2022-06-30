<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrcodeExpiryDateToQrCodeVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('qr_code_vehicles', 'qrcode_expiry_date')) return;
        Schema::table('qr_code_vehicles', function (Blueprint $table) {
            $table->dateTime('qrcode_expiry_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qr_code_vehicles', function (Blueprint $table) {
        });
    }
}

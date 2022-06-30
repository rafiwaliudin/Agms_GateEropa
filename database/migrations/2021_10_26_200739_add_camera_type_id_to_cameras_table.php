<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCameraTypeIdToCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('cameras', 'camera_type_id')) return;
        Schema::table('cameras', function (Blueprint $table) {
            $table->unsignedInteger('camera_type_id');
        });
        if (Schema::hasColumn('cameras', 'prefix_port')) return;
        Schema::table('cameras', function (Blueprint $table) {
            $table->unsignedInteger('prefix_port');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->dropColumn('camera_type_id');
            $table->dropColumn('prefix_port');
        });
    }
}

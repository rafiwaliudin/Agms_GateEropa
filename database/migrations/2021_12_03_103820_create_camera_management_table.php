<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCameraManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camera_management', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('camera_name');
            $table->string('ws_url')->nullable;
            $table->integer('prefix_port')->nullable;
            $table->string('longitue');
            $table->string('latitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('camera_management');
    }
}

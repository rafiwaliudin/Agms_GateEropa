<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentialGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('residential_gates')) return; 
        Schema::create('residential_gates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->unsignedBigInteger('cluster_id');
            $table->string('ip_mysql')->nullable();
            $table->string('ip_mongo')->nullable();
            $table->string('ip_mata')->nullable();
            $table->string('ip_dc_data')->nullable();
            $table->string('ip_dc_image')->nullable();
            $table->string('port_mysql')->nullable();
            $table->string('port_mongo')->nullable();
            $table->string('port_mata')->nullable();
            $table->string('port_dc_data')->nullable();
            $table->string('port_dc_image')->nullable();
            $table->string('ip_dvr')->nullable();
            $table->string('port_dvr')->nullable();
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
        Schema::dropIfExists('residential_gates');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('gates')) return; 
        Schema::create('gates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->string('license_plate');
            $table->unsignedInteger('occupant_id');
            $table->unsignedInteger('visitor_id')->nullable();
            $table->string('gate_number')->nullable();
            $table->string('gate_type')->nullable();
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
        Schema::dropIfExists('gates');
    }
}

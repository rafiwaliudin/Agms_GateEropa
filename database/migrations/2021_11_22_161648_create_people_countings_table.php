<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleCountingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('people_countings')) return; 
        Schema::create('people_countings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->string('license_plate');
            $table->unsignedInteger('occupant_id')->nullable();
            $table->unsignedInteger('visitor_id')->nullable();
            $table->unsignedInteger('residential_gate_id')->nullable();
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
        Schema::dropIfExists('people_countings');
    }
}

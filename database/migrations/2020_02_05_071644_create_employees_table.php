<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::dropIfExists('employees'); */
        if(Schema::hasTable('employees')) return; 
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->bigInteger('nik')->nullable();
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedInteger('gender_id')->nullable();
            $table->unsignedInteger('blood_type_id')->nullable();
            $table->unsignedInteger('religion_id')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('position_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('photo');
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('employees');
    }
}

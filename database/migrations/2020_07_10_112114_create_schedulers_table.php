<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::dropIfExists('schedulers'); */
        if(Schema::hasTable('schedulers')) return; 
        Schema::create('schedulers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('scheduler_id')->nullable();
            $table->string('name');
            $table->string('email_to');
            $table->string('email_cc_1')->nullable();
            $table->string('email_cc_2')->nullable();
            $table->string('email_cc_3')->nullable();
            $table->string('email_cc_4')->nullable();
            $table->string('email_cc_5')->nullable();
            $table->time('schedule_time');
            $table->integer('range');
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
        Schema::dropIfExists('schedulers');
    }
}

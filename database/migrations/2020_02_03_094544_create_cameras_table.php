<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::dropIfExists('cameras'); */
        if(Schema::hasTable('cameras')) return; 
        Schema::create('cameras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedInteger('camera_type_id');
            $table->string('input_link')->nullable();
            $table->integer('type_proc')->nullable();
            $table->integer('prefix_port')->nullable();
            $table->string('target_objects')->nullable();
            $table->integer('n_input')->default(1);
            $table->integer('n_counting_lines')->nullable();
            $table->string('counting_lines')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('screenshot')->nullable();
            $table->text('address')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('id_proc')->nullable();
            $table->integer('impression')->default(0);
            $table->string('intruder_mask')->nullable();
            $table->time('intruder_time_start')->nullable();
            $table->time('intruder_time_end')->nullable();
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
        Schema::dropIfExists('cameras');
    }
}

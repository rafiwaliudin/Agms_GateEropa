<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::dropIfExists('data_tokens'); */
        if(Schema::hasTable('data_tokens')) return; 
        Schema::create('data_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('token');
            $table->dateTime('start_token');
            $table->dateTime('end_token');
            $table->boolean('status');
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
        Schema::dropIfExists('data_tokens');
    }
}

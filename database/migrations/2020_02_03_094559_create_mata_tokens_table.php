<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::dropIfExists('mata_tokens'); */
        if(Schema::hasTable('mata_tokens')) return; 
        Schema::create('mata_tokens', function (Blueprint $table) {
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
        Schema::dropIfExists('mata_tokens');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOccupantIdToGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('gates', 'occupant_id')) return;
        Schema::table('gates', function (Blueprint $table) {
            $table->dropColumn('occupant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gates', function (Blueprint $table) {
            $table->unsignedInteger('occupant_id');
        });
    }
}

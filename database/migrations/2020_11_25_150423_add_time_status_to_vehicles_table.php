<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeStatusToVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vehicles', 'release_status')) {
            Schema::table('vehicles', function (Blueprint $table) {
                    $table->string('release_status')->nullable();
                });            
        }
        if (!Schema::hasColumn('vehicles', 'time_status')) {
            Schema::table('vehicles', function (Blueprint $table) {
                    $table->dateTime('time_status')->nullable();
                });            
        }
        if (!Schema::hasColumn('vehicles', 'position_status')) {
            Schema::table('vehicles', function (Blueprint $table) {
                    $table->string('position_status')->nullable();
                });            
        }        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('release_status');
            $table->dropColumn('time_status');
            $table->dropColumn('position_status');
        });
    }
}

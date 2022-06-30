<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWsToCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('cameras', 'ip_ws')) {
            Schema::table('cameras', function (Blueprint $table) {
                    $table->string("ip_ws")->nullable();
                });
        }
        if (!Schema::hasColumn('cameras', 'port_ws')) {
            Schema::table('cameras', function (Blueprint $table) {
                    $table->string("port_ws")->nullable();
                });
        }
        if (!Schema::hasColumn('cameras', 'portal_status')) {
            Schema::table('cameras', function (Blueprint $table) {
                    $table->string("portal_status")->nullable();
                });
        }
        if (!Schema::hasColumn('cameras', 'residential_gate_id')) {
            Schema::table('cameras', function (Blueprint $table) {
                    $table->unsignedBigInteger("residential_gate_id")->nullable();
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
        Schema::table('cameras', function (Blueprint $table) {
            $table->dropColumn("ip_ws");
            $table->dropColumn("port_ws");
            $table->dropColumn("portal_status");
            $table->dropColumn("residential_gate_id");
        });
    }
}

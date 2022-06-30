<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpRaspiToResidentialGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('residential_gates', 'ip_raspi')) {
            Schema::table('residential_gates', function (Blueprint $table) {
                    $table->string('ip_raspi')->nullable();
                });
        }
        if (!Schema::hasColumn('residential_gates', 'port_raspi')) {
            Schema::table('residential_gates', function (Blueprint $table) {
                    $table->string('port_raspi')->nullable();
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
        Schema::table('residential_gates', function (Blueprint $table) {
            $table->dropColumn("ip_raspi");
            $table->dropColumn("port_raspi");
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyGateNumberToGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('gates', 'gate_number')) return;
        if (!Schema::hasColumn('gates', 'gate_type')) return;
        Schema::table('gates', function (Blueprint $table) {
            $table->unsignedBigInteger('gate_number')->change();
            $table->renameColumn('gate_number', 'residential_gate_id');
            $table->renameColumn('gate_type', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('gates', 'residential_gate_id')) return;
        if (!Schema::hasColumn('gates', 'status')) return;
        Schema::table('gates', function (Blueprint $table) {
            $table->string('residential_gate_id')->nullable()->change();
            $table->renameColumn('residential_gate_id', 'gate_number');
            $table->renameColumn('status', 'gate_type');
        });
    }
}

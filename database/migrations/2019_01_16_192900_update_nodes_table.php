<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->dropColumn('port');
            $table->dropColumn('nodePort');
            $table->dropColumn('chordPort');
            $table->dropColumn('wsPort');
            $table->dropColumn('version');
            $table->dropColumn('time');
            $table->dropColumn('services');
            $table->dropColumn('relay');
            $table->dropColumn('txnCnt');
            $table->dropColumn('rxTxnCnt');
            $table->dropColumn('chordID');
            $table->renameColumn('jsonPort', 'jsonRpcPort');
            $table->renameColumn('softwareVersion', 'version');
            $table->integer('httpProxyPort')->nullable();
            $table->integer('websocketPort')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

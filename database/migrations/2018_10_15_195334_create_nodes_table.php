<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->BigInteger('id')->unsigned()->unique()->index();
            $table->string('label')->nullable()->default("");
            $table->integer('state');
            $table->string('syncState');
            $table->integer('port');
            $table->integer('nodePort');
            $table->integer('chordPort');
            $table->integer('jsonPort');
            $table->integer('wsPort');
            $table->string('addr');
            $table->BigInteger('time');
            $table->integer('version');
            $table->integer('services');
            $table->boolean('relay');
            $table->BigInteger('height');
            $table->BigInteger('txnCnt');
            $table->BigInteger('rxTxnCnt');
            $table->string('chordID');
            $table->string('softwareVersion');
            $table->BigInteger('latestBlockHeight');
            $table->boolean('online');
            $table->integer('user_id');
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
        Schema::dropIfExists('nodes');
    }
}

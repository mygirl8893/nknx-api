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
            $table->increments('id')->index();
            $table->string('label')->nullable()->default("");
            $table->integer('state')->nullable();
            $table->string('syncState')->nullable();
            $table->integer('port')->nullable();
            $table->integer('nodePort')->nullable();
            $table->integer('chordPort')->nullable();
            $table->integer('jsonPort')->nullable();
            $table->integer('wsPort')->nullable();
            $table->string('addr')->nullable();
            $table->BigInteger('time')->nullable();
            $table->integer('version')->nullable();
            $table->integer('services')->nullable();
            $table->boolean('relay')->nullable();
            $table->BigInteger('height')->nullable();
            $table->BigInteger('txnCnt')->nullable();
            $table->BigInteger('rxTxnCnt')->nullable();
            $table->string('chordID')->nullable();
            $table->string('softwareVersion')->nullable();
            $table->BigInteger('latestBlockHeight')->nullable();
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

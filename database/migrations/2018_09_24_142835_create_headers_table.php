<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chordId')->default("");
            $table->string('hash');
            $table->BigInteger('height');
            $table->string('prevBlockHash');
            $table->string('signature');
            $table->string('signer');
            $table->timestamp('timestamp');
            $table->string('transactionsRoot');
            $table->integer('version');
            $table->string('winningHash');
            $table->integer('winningHashType');
            $table->integer('block_id');
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
        Schema::dropIfExists('headers');
    }
}

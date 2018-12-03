<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('hash')->index();
            $table->BigInteger('height')->index();
            $table->string('prevBlockHash')->index();
            $table->string('nextBlockHash')->default("");
            $table->string('signature');
            $table->string('signer')->index();
            $table->integer('timestamp')->index();
            $table->string('transactionsRoot');
            $table->integer('version');
            $table->string('winningHash');
            $table->integer('winningHashType');
            $table->string('code');
            $table->string('parameter');
            $table->integer('transaction_count');
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
        Schema::dropIfExists('blocks');
    }
}

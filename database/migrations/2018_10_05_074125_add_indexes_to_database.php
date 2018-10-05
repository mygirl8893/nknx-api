<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blocks', function (Blueprint $table){
            $table->index(['id','hash']);
        });
        Schema::table('headers', function (Blueprint $table){
            $table->index(['id','height','signer','timestamp']);
        });
        Schema::table('outputs', function (Blueprint $table){
            $table->index(['id','address']);
        });
        Schema::table('payloads', function (Blueprint $table){
            $table->index(['id']);
        });
        Schema::table('programs', function (Blueprint $table){
            $table->index(['id']);
        });
        Schema::table('transactions', function (Blueprint $table){
            $table->index(['id','hash','txType']);
        });
        Schema::table('attributes', function (Blueprint $table){
            $table->index(['id']);
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

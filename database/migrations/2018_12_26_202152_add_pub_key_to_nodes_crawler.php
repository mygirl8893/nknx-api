<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPubKeyToNodesCrawler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crawled_nodes', function (Blueprint $table) {
            $table->string('pk')->nullable();
            $table->integer('port')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crawled_nodes', function (Blueprint $table) {
            $table->dropColumn('pk');
            $table->dropColumn('port');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorNodeCrawlerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('crawler_temp_nodes');
        Schema::table('cached_nodes', function (Blueprint $table){
            $table->index(['ip']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('crawler_temp_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pk')->unique();
            $table->string('ip');
            $table->integer('port');
            $table->integer('state')->default(0);
            $table->string('continent_code')->nullable();
            $table->string('continent_name')->nullable();
            $table->string('country_code2')->nullable();
            $table->string('country_code3')->nullable();
            $table->string('country_name')->nullable();
            $table->string('country_capital')->nullable();
            $table->string('state_prov')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->decimal('latitude',10,8)->nullable();
            $table->decimal('longitude',11,8)->nullable();
            $table->string('is_eu')->nullable();
            $table->string('calling_code')->nullable();
            $table->string('country_tld')->nullable();
            $table->string('isp')->nullable();
            $table->string('connection_type')->nullable();
            $table->string('organization')->nullable();
            $table->string('geoname_id')->nullable();
            $table->timestamps();
        });
    }
}

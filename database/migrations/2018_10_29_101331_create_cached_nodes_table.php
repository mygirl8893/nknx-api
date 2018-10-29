<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCachedNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cached_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cached_nodes');
    }
}

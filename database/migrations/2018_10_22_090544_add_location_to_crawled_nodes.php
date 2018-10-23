<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToCrawledNodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crawled_nodes', function($table) {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crawled_nodes', function($table) {
            $table->dropColumn('continent_code');
            $table->dropColumn('continent_name');
            $table->dropColumn('country_code2');
            $table->dropColumn('country_code3');
            $table->dropColumn('country_name');
            $table->dropColumn('country_capital');
            $table->dropColumn('state_prov');
            $table->dropColumn('district');
            $table->dropColumn('city');
            $table->dropColumn('zipcode');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->dropColumn('is_eu');
            $table->dropColumn('calling_code');
            $table->dropColumn('country_tld');
            $table->dropColumn('isp');
            $table->dropColumn('connection_type');
            $table->dropColumn('organization');
            $table->dropColumn('geoname_id');
        });
    }
}

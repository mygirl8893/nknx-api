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
            $table->string('country_code')->nullable();
            $table->string('country_name')->nullable();
            $table->string('region_code')->nullable();
            $table->string('region_name')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->decimal('latitude',10,8)->nullable();
            $table->decimal('longitude',11,8)->nullable();
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
            $table->dropColumn('country_code');
            $table->dropColumn('country_name');
            $table->dropColumn('region_code');
            $table->dropColumn('region_name');
            $table->dropColumn('city');
            $table->dropColumn('zip');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}

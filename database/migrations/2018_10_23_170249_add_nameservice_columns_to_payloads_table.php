<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameserviceColumnsToPayloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payloads', function (Blueprint $table) {
            $table->text('sigChain')->nullable()->change();
            $table->string('submitter')->nullable()->change();
            $table->string('name')->nullable()->index();
            $table->string('registrant')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payloads', function (Blueprint $table) {
            $table->text('sigChain')->nullable(false)->change();
            $table->string('submitter')->nullable(false)->change();
            $table->dropColumn('name');
            $table->dropColumn('registrant');
            //
        });
    }
}

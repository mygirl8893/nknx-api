<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotifiedToNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->timestamp('notified_offline')->nullable();
            $table->timestamp('notified_outdated')->nullable();
            $table->timestamp('notified_stucked')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('notified_offline');
            $table->dropColumn('notified_outdated');
            $table->dropColumn('notified_stucked');
        });
    }
}

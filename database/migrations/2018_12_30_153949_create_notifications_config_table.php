<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->boolean('nodeOffline');
            $table->boolean('nodeOutdated');
            $table->boolean('nodeStucked');
            $table->boolean('weeklyMiningOutput');
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
        Schema::dropIfExists('notifications_config');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllOtherTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name_player');
            $table->string('family_player');
            $table->string('city');
            $table->integer('plaer_from_id');
        });

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_id');
            $table->integer('user_id');
            $table->integer('player_id');
            $table->smallInteger('goal_for');
            $table->smallInteger('goal_away');
            $table->char('result', 4);
            $table->char('type', 1);
        });

        Schema::create('play-off', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user_po');
            $table->integer('id_player_po');
            $table->smallInteger('for_po');
            $table->smallInteger('away_po');
        			
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

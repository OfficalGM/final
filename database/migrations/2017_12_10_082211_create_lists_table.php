<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lists', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('orderlists_id')->unsigned();
            $table->foreign('orderlists_id')->references('id')->on('orderlists');
            $table->integer('food_id')->unsigned();
            $table->foreign('food_id')->references('food_id')->on('foodlists');
            $table->integer('amount');
            $table->integer('money');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lists');
    }
}

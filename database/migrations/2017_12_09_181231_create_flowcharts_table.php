<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowchartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flowcharts', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('flowchart_set');
            $table->integer('flowchart_make');
            $table->integer('flowchart_way');
            $table->integer('flowchart_done');
            $table->text('exception');
            $table->string('time_set');
            $table->string('time_make');
            $table->string('time_way');
            $table->string('time_done');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('flowcharts');
    }
}

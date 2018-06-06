<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('email')->unique();
            $table->string('shop_name');
            $table->string('city',10);
            $table->string('address');
            $table->string('password',255);
            $table->integer('active');
            $table->timestamps('created_at');
            $table->integer('moring');
            $table->integer('afternoon');
            $table->integer('night');
            $table->integer('midnight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shops');
    }
}

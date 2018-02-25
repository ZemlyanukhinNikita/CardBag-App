<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewTableTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique();
            $table->integer('network_id')->unsigned();
            $table->string('token')->unique();
            $table->string('uid')->unique();
            $table->timestamps();
        });

        Schema::table('tokens', function ($table) {
            $table->foreign('network_id')->references('id')->on('networks');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tokens');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewTableAccessTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid');
            $table->integer('network_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->dateTime('expires_at')->nullable();

            $table->unique(['user_id', 'network_id']);
            $table->unique(['name', 'uid']);
        });

        Schema::table('access_tokens', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('network_id')->references('id')->on('networks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('access_tokens');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_identity')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('network_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('user_networks', function ($table) {
            $table->foreign('network_id')->references('id')->on('networks');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_networks', function (Blueprint $table) {
            $table->dropColumn('user_identity');
        });
    }
}

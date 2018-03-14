<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_datas', function (Blueprint $table) {
            $table->string('uid')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('network_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('user_datas', function ($table) {
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
        Schema::table('user_datas', function (Blueprint $table) {
            $table->dropColumn('uid');
        });
    }
}

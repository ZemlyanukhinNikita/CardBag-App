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
            $table->string('token')->unique()->nullable();
            $table->integer('network_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('tokens', function ($table) {
            $table->foreign('network_id')->references('id')->on('networks');
        });
    }
}

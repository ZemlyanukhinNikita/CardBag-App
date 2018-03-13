<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsInTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uid')->nullable();
            $table->integer('network_id')->unsigned()->nullable();
        });

        Schema::table('users', function ($table) {
            $table->foreign('network_id')->references('id')->on('networks');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uid');
        });
    }
}


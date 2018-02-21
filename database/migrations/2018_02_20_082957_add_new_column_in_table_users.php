<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnInTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uid')->nullable()->unique();
            $table->string('full_name')->nullable();
            $table->integer('network_id')->unsigned()->nullable();
        });

        Schema::table('users', function ($table) {
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uid');
            $table->dropColumn('full_name');
        });
    }
}

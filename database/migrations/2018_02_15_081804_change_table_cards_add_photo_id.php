<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableCardsAddPhotoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->integer('front_photo')->unsigned()->nullable()->change();
            $table->integer('back_photo')->unsigned()->nullable()->change();
        });

        Schema::table('cards', function ($table) {
            $table->foreign('front_photo')->references('id')->on('photos');
            $table->foreign('back_photo')->references('id')->on('photos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('front_photo')->change();
            $table->string('back_photo')->change();
        });
    }
}


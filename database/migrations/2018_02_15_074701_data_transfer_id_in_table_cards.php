<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DataTransferIdInTableCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function () {
            $photos = DB::table('photos')->select('id', 'filename')->get();

            foreach ($photos as $photo) {
                DB::table('cards')
                    ->where('front_photo', env('APP_URL') . 'storage/' . $photo->filename)
                    ->update([
                        'front_photo' => $photo->id,
                    ]);

                DB::table('cards')
                    ->where('back_photo', env('APP_URL') . 'storage/' . $photo->filename)
                    ->update([
                        'back_photo' => $photo->id,
                    ]);
            }
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
            //
        });
    }
}

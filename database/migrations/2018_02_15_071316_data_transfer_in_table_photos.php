<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DataTransferInTablePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos', function () {
            $photos = DB::table('cards')->select('id', 'front_photo', 'back_photo', 'user_id', 'created_at', 'updated_at')->get();
            foreach ($photos as $photo) {
                DB::table('photos')->insert(
                    [
                        ['user_id' => $photo->user_id, 'filename' => basename($photo->front_photo),
                            'created_at' => $photo->created_at, 'updated_at' => $photo->updated_at],
                        ['user_id' => $photo->user_id, 'filename' => basename($photo->back_photo),
                            'created_at' => $photo->created_at, 'updated_at' => $photo->updated_at],
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
        Schema::table('photos', function (Blueprint $table) {
            //
        });
    }
}

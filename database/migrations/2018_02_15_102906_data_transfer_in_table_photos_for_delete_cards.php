<?php

use App\Card;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DataTransferInTablePhotosForDeleteCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos', function () {
            $cards = Card::withTrashed()->get();
            foreach ($cards as $card) {
                DB::table('photos')
                    ->where('id', $card->front_photo)
                    ->update([
                        'deleted_at' => $card->deleted_at,
                    ]);

                DB::table('photos')
                    ->where('id', $card->back_photo)
                    ->update([
                        'deleted_at' => $card->deleted_at,
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

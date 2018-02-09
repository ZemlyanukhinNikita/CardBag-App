<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class GenerateUuidsInTableCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function () {
            $results = DB::table('cards')->select('id', 'uuid')->get();

            foreach ($results as $result) {
                DB::table('cards')
                    ->where('id', $result->id)
                    ->update([
                        'uuid' => Uuid::uuid4()
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
    }
}

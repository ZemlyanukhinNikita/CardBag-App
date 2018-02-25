<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSocialNetworksInTableNetworks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('networks', function () {
            DB::table('networks')->insert(
                [
                    ['name' => "Vk"],
                    ['name' => "Google"],
                    ['name' => "Facebook"],
                    ['name' => "Firebase"],
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('networks', function (Blueprint $table) {

        });
    }
}

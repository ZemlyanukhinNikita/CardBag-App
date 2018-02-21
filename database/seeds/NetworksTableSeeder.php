<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NetworksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('networks')->insert(
            [
                ['name' => "Vk"],
                ['name' => "Google"],
                ['name' => "Facebook"],
                ['name' => "Firebase"],
            ]
        );
    }
}

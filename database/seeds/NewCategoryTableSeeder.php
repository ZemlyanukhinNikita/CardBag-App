<?php

use Illuminate\Database\Seeder;

class NewCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            [
                ['title' => "Супермаркеты"],
            ]);
        DB::update("update categories set title = 'Книги, искуcство и кино' where title = ?", ['Книги, искуство и кино']);
    }
}

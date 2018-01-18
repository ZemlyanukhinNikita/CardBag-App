<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
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
                ['title' => "Кафе, рестораны и бары"],
                ['title' => "Одежда, обувь, аксессуары"],
                ['title' => "Красота"],
                ['title' => "Электроника"],
                ['title' => "Авто"],
                ['title' => "Спорт"],
                ['title' => "Развлечения и хобби"],
                ['title' => "Услуги и сервис"],
                ['title' => "Цветы и подарки"],
                ['title' => "Путешествия"],
                ['title' => "Ювелирные украшения"],
                ['title' => "Дом и ремонт"],
                ['title' => "Игрушки"],
                ['title' => "Образование"],
                ['title' => "Книги, искуство и кино"],
                ['title' => "Другое"]
            ]
        );
    }
}

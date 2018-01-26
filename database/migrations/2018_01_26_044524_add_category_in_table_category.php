<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCategoryInTableCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function () {
            DB::table('categories')->insert(
                [
                    ['title' => "Супермаркеты"],
                ]);
            DB::update("update categories set title = 'Книги, искусство и кино' where title = ?", ['Книги, искуство и кино']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function () {
            DB::table('categories')->where('title', 'Супермаркеты')->delete();
            DB::update("update categories set title = 'Книги, искуство и кино' where title = ?", ['Книги, искусство и кино']);
        });
    }
}

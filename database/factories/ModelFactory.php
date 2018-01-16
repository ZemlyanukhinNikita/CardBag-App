<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Card::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->unique(),
        'category_id' => $faker->numberBetween(1, 16),
        'front_foto' => $faker->imageUrl(),
        'back_foto' => $faker->imageUrl(),
        'discount' => $faker->randomFloat(1, 0, 100)
    ];
});
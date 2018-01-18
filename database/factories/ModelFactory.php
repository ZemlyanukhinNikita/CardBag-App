<?php

use Faker\Generator as Faker;

/**
 * Factory definition for model Cards.
 */
/** @var $factory */
$factory->define(App\Card::class, function (Faker $faker) {
    return [
        'title' => $faker->name(),
        'category_id' => $faker->numberBetween(1, 16),
        'front_photo' => $faker->imageUrl(),
        'back_photo' => $faker->imageUrl(),
        'discount' => $faker->randomFloat(1, 0, 100)
    ];
});

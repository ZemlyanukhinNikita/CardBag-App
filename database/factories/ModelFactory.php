<?php

use App\Card;
use App\User;
use Faker\Generator as Faker;

/**
 * Factory definition for model Cards.
 */
/** @var $factory */
$factory->define(Card::class, function (Faker $faker) {
    return [
        'title' => $faker->name(),
        'category_id' => $faker->numberBetween(1, 16),
        'front_photo' => $faker->imageUrl(),
        'back_photo' => $faker->imageUrl(),
        'discount' => $faker->numberBetween(0, 100)
    ];
});

/**
 * Factory definition for model UserFactory.
 */
$factory->define(User::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
    ];
});

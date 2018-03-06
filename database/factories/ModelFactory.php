<?php

use Faker\Generator as Faker;

/**
 * Factory definition for model Cards.
 */
/** @var $factory */

$factory->define(\App\User::class, function (Faker $faker) {
    return [
        'full_name' => $faker->name()

    ];
});

$factory->define(\App\Token::class, function (Faker $faker) {
    return [
        'token' => $faker->uuid,
        'uid' => $faker->uuid,
        'network_id' => 4,
        'user_id' => 1
    ];
});

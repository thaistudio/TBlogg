<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\comment;
use Faker\Generator as Faker;

$factory->define(comment::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class),
        'comment' => $faker->sentence,
    ];
});

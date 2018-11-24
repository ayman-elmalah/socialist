<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
      'details' => $faker->paragraphs(rand(3, 10), true),
    ];
});

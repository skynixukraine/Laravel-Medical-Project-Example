<?php

use Faker\Generator as Faker;

$factory->define(App\Symptom::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'order' => $faker->numberBetween(10,100)
    ];
});
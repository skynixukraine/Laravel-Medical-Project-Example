<?php

declare(strict_types=1);

use App\Models\DoctorTitle;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/**
 * @var Factory $factory
 */
$factory->define(DoctorTitle::class, function (Faker $faker) {
    return ['name' => $faker->unique()->title];
});

<?php

declare(strict_types=1);

use App\Models\Doctor;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @var Factory $factory
 */
$factory->define(App\Models\Doctor::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone_number' => $faker->unique()->phoneNumber,
        'password' => 'secret',
        'status' => Doctor::STATUS_ACTIVATED,
        'email_verified_at' => now(),
        'description' => $faker->realText(1000)
    ];
});

<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'user_id' => \App\Models\User::generateUserID(),
        'photo' => null,
        'gender' => $faker->randomElement(['m', 'f']),
        'title' => $faker->randomElement([null, 'Dr.']),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birthday' => $faker->dateTimeThisCentury()->format('Y-m-d'),
        'birthplace' => $faker->city,
        'street' => $faker->streetAddress,
        'zip' => $faker->postcode,
        'city' => $faker->city,
        'country' => $faker->randomElement(['DE','DE','DE','DE','AT','CH']),
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'graduation_year' => $faker->year,
        'reason_for_application' => $faker->text,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => null,
        'status' => 'confirmed'
    ];
});

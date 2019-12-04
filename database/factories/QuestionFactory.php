<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [];
});

$factory->state(App\Question::class, 'open', function (Faker $faker) {

    $submission_id = rand(1,10);
    $submission = \App\Submission::find($submission_id);

    $asked_minutes_before_due = rand(20, 60);
    $created_at  = (new \Carbon\Carbon($submission->due_at))->subMinutes($asked_minutes_before_due + rand(20,60));

    return [
        'submission_id'         => $submission_id,
        'asked_by_user_id'      => 1,
        'question'              => $faker->realText(350),
        'created_at'            => $created_at,
        'updated_at'            => $created_at
    ];

});

$factory->state(App\Question::class, 'answered', function (Faker $faker) {

    $submission_id = rand(1,10);
    $submission = \App\Submission::find($submission_id);
    $answered_minutes_before_due = rand(60, 300);
    $created_at  = (new \Carbon\Carbon($submission->due_at))->subMinutes($answered_minutes_before_due + rand(20,60));
    $answered_at = (new \Carbon\Carbon($submission->due_at))->subMinutes($answered_minutes_before_due);

    return [
        'submission_id'         => $submission_id,
        'asked_by_user_id'      => 1,
        'question'              => $faker->realText(350),
        'answer'                => $faker->realText(350),
        'created_at'            => $created_at,
        'updated_at'            => $answered_at,
        'answered_at'           => $answered_at
    ];
});

<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Submission::class, function (Faker $faker) {

    $responsetime = 48;
    $created_hours_ago = rand(-$responsetime, 0);
    $created_at = \Carbon\Carbon::now()->subHours($created_hours_ago);
    $due_at = \Carbon\Carbon::now()->subHours($created_hours_ago)->addHours($responsetime);

    // 20% chance, that a submission is currently assigned
    $status = $faker->randomElement(['open', 'open', 'open', 'open', 'assigned']);
    $assigned_to_user_id = ($status == "open") ? null : $faker->numberBetween(1, 10);
    $assigned_at         = ($status == "open") ? null : \Carbon\Carbon::now()->subHours($created_hours_ago)->addHours(1);

    // 20% chance, that since = 'andere Angabe' was selected
    $since = $faker->randomElement(['weniger als zwei Tage', 'zwischen 2 bis 6 Tagen', 'zwischen 1 bis 4 Wochen', 'länger als 1 Monat', 'chronisch/permanent', 'andere Angabe']);
    $sinceOther = ($since == 'andere Angabe') ? $faker->word : null;

    $medium    = $faker->randomElement(['web','ios','android']);
    $device_id = ($medium == "web") ? null : str_random(64);

    $treated = $faker->boolean;
    $treatment = ($treated) ? $faker->realText(100) : null;

    return [
        'side'                  => $faker->randomElement(['einseitig', 'beidseitig', 'nicht sicher']),
        'other_symptoms'        => $faker->word, // fixme: now every submission will have other_symptoms, although symtom "sonsitges" might not be assigned
        'affected_area'         => $faker->words(2,true),
        'treated'               => $treated,
        'treatment'             => $treatment,
        'description'           => $faker->realText(350), // important: this is now used for "additional information"
        'since'                 => $since,
        'since_other'           => $sinceOther,
        'city'                  => $faker->city,
        'country'               => 'DE', // $faker->country,
        'responsetime'          => $responsetime,
        'gender'                => $faker->randomElement(['m', 'f']),
        'age'                   => $faker->numberBetween(8, 80),
        'email'                 => $faker->email,
        'submission_id'         => \App\Models\Submission::generateSubmissionID(),
        'created_at'            => $created_at,
        'updated_at'            => $created_at,
        'due_at'                => $due_at,
        'status'                => $status,
        'assigned_to_user_id'   => $assigned_to_user_id,
        'assigned_at'           => $assigned_at,
        'medium'                => $medium,
        'device_id'             => $device_id,
        'closeup_image_id'      => str_random(15),
        'overview_image_id'     => str_random(15),
        'partner_id'            => rand(1,3)
    ];
});

$factory->state(App\Models\Submission::class, 'answered', function ($faker) {

    $responsetime = 48;
    $created_hours_ago = rand(0, 240);
    $created_at = \Carbon\Carbon::now()->subHours($created_hours_ago);
    $assigned_after_minutes = rand(0, $responsetime*40);
    $responded_after_minutes = rand($assigned_after_minutes, $responsetime*60);
    $answered_at = \Carbon\Carbon::now()->subHours($created_hours_ago)->addMinutes($responded_after_minutes);
    $feedback_provided = rand(0,1);
    if ($feedback_provided) {
        $stars = $faker->numberBetween(1, 5);
        $feedback = $faker->realText(100);
    }
    else {
        $stars = null;
        $feedback = null;
    }
    $assigned_at = \Carbon\Carbon::now()->subHours($created_hours_ago)->addMinutes($assigned_after_minutes);
    $due_at = \Carbon\Carbon::now()->subHours($created_hours_ago)->addHours($responsetime);

    $diagnosis_possible = rand(0,1);
    if ($diagnosis_possible) {
        $diagnosis = $faker->realText(50);
    }
    else {
        $diagnosis = null;
    }
    $did_recommend_medicine = rand(0,1);
    if ($did_recommend_medicine) {
        $recommended_medicine = $faker->realText(50);
    }
    else {
        $recommended_medicine = null;
    }

    return [
        'created_at'             => $created_at,
        'updated_at'             => $created_at,
        'answer'                 => $faker->realText(400),
        'assigned_to_user_id'    => $faker->numberBetween(1, 10),
        'assigned_at'            => $assigned_at,
        'answered_at'            => $answered_at,
        'stars'                  => $stars,
        'feedback'               => $feedback,
        'due_at'                 => $due_at,
        'status'                 => 'answered',
        'diagnosis_possible'     => $diagnosis_possible,
        'diagnosis'              => $diagnosis,
        'requires_doctors_visit' => rand(0,1),
        'did_recommend_medicine' => $did_recommend_medicine,
        'recommended_medicine'   => $recommended_medicine
    ];
});

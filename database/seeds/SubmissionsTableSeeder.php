<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubmissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('submissions')->truncate();
        DB::table('submission_symptom')->truncate();

        // submissions, answered by Dr. Mabuse
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 1, 'submission_id' => 'ssssssss01', 'closeup_image_id' => 'aaaaaaaaaaaaa1c', 'overview_image_id' => 'aaaaaaaaaaaaa1o']);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 1, 'submission_id' => 'ssssssss02', 'closeup_image_id' => 'aaaaaaaaaaaaa2c', 'overview_image_id' => 'aaaaaaaaaaaaa2o']);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 1, 'submission_id' => 'ssssssss03']);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 1, 'submission_id' => 'ssssssss04']);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 1, 'submission_id' => 'ssssssss05']);

        // submissions, answered by Dr. Eisenfaust
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 2, 'submission_id' => 'ssssssss06']);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 2, 'submission_id' => 'ssssssss07']);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => 2, 'submission_id' => 'ssssssss08']);

        // submissions, answered by Dr. No
        // none

        // submissions, answered by Dr. Oberschlau
        factory(App\Submission::class, 38)->states('answered')->create(['assigned_to_user_id' => 4]);

        // + 10 unanswered / assigned but not answered
        factory(App\Submission::class, 10)->create();

        // + 10 answered by
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);
        factory(App\Submission::class)->states('answered')->create(['assigned_to_user_id' => rand(5, 10)]);

        // attach between 1 and 3 symptoms to each submission
        $symptoms = \App\Symptom::all();
        App\Submission::all()->each(function ($submission) use ($symptoms) {
            $submission->symptoms()->attach(
                $symptoms->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

    }
}

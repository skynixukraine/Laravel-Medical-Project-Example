<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->truncate();

        factory(App\Models\Question::class)->states('answered')->times(20)->create();
        factory(App\Models\Question::class)->states('open')->times(5)->create();
    }
}

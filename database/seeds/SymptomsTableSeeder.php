<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SymptomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('symptoms')->truncate();

        factory(App\Models\Symptom::class)->create(['order' => 10, 'name' => 'Ausschlag']);
        factory(App\Models\Symptom::class)->create(['order' => 20, 'name' => 'Juckreiz']);
        factory(App\Models\Symptom::class)->create(['order' => 30, 'name' => 'Schwellung']);
        factory(App\Models\Symptom::class)->create(['order' => 40, 'name' => 'Rötung']);
        factory(App\Models\Symptom::class)->create(['order' => 50, 'name' => 'Schmerzen']);
        factory(App\Models\Symptom::class)->create(['order' => 60, 'name' => 'Schuppung']);
        factory(App\Models\Symptom::class)->create(['order' => 70, 'name' => 'Muttermal']);
        factory(App\Models\Symptom::class)->create(['order' => 80, 'name' => 'Flecken']);
        factory(App\Models\Symptom::class)->create(['order' => 90, 'name' => 'sonstiges']);
    }
}

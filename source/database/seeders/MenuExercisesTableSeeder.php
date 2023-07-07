<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuExercisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu_exercises')->insert([
            'menu_id' => 1, // サンプルのメニューID
            'exercise_id' => 1, // サンプルのエクササイズID
            'order' => 1,
            'planned_sets' => 3,
            'weight' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuExercise;

class Menu_exercisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // メニューと種目の関連データを作成
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 3,
            'order' => 1,
            'reps' => 3,
            'weight' => 50,
        ]);

        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 2,
            'order' => 1,
            'reps' => 4,
            'weight' => 60,
        ]);
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 5,
            'order' => 1,
            'reps' => 3,
            'weight' => 50,
        ]);

        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 6,
            'order' => 1,
            'reps' => 4,
            'weight' => 60,
        ]);
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 7,
            'order' => 1,
            'reps' => 3,
            'weight' => 50,
        ]);

        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 8,
            'order' => 1,
            'weight' => 60,
            'reps' => 10,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 1,
            'order' => 1,
            'weight' => 60,
            'reps' => 10,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 5,
            'order' => 1,
            'weight' => 50,
            'reps' => 10,
        ]);

        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 8,
            'order' => 1,
            'weight' => 60,
            'reps' => 10,
        ]);

        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 8,
            'order' => 1,
            'weight' => 50,
            'reps' => 10,
        ]);

        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 8,
            'order' => 1,
            'weight' => 60,
            'reps' => 10,
        ]);




        // 追加の関連データをここに追記

    }
}

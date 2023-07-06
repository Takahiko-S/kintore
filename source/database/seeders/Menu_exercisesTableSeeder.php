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
            'exercise_id' => 1,
            'order' => 1,
            'planned_sets' => 3,
            'weight' => 50,
        ]);
        
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 2,
            'order' => 2,
            'planned_sets' => 4,
            'weight' => 60,
        ]);
        
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 3,
            'order' => 1,
            'planned_sets' => 2,
            'weight' => 40,
        ]);
        
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 4,
            'order' => 2,
            'planned_sets' => 3,
            'weight' => 55,
        ]);
        
        // 追加の関連データをここに追記
    
    }
}

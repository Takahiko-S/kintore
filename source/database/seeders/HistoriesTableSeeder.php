<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('histories')->insert([
        //     'user_id' => 1,
        //     'menu_id' => 3,
        //     'menu_exercise_id' => 1,
        //     'menu_name' => 'メニュー３', // menu_nameフィールドに適切な値を設定してください
        //     'exercise_name' => 'some_exercise_name',
        //     'exercise_date' => '2023-08-01 16:36:23',
        //     'exercise_id' => 1,
        //     'sets' => 3,
        //     'weight' => 10,
        //     'reps' => 10,
        //     'memo' => 'some_memo',
        //     'is_completed' => 1,
        //     'created_at' => '2023-08-01 16:36:23',
        //     'updated_at' => '2023-08-01 16:36:23'
        // ]);
    }
}

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
        DB::table('histories')->insert([
            'user_id' => 1, // サンプルのユーザーID
            'menu_id' => 1, // サンプルのメニューID
            'exercise_date' => '2023-07-07',
            'exercise_id' => 1, // サンプルのエクササイズID
            'sets' => 3,
            'weight' => 10,
            'is_completed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Exercises; // Exerciseモデルのネームスペースを確認してください。

class ExercisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $init_exercises = [
            ['name' => 'ベンチプレス', 'body_part' => '胸'],
            ['name' => 'デッドリフト', 'body_part' => '背中'],
            ['name' => 'スクワット', 'body_part' => '脚'],
            ['name' => 'ショルダープレス', 'body_part' => '肩'],
            ['name' => 'バーベルカール', 'body_part' => '上腕二頭筋'],
            ['name' => 'トライセップスエクステンション', 'body_part' => '上腕三頭筋'],
            ['name' => 'レッグプレス', 'body_part' => '脚'],
            ['name' => 'プルアップ', 'body_part' => '背中'],
            ['name' => 'ダンベルフライ', 'body_part' => '胸'],
            ['name' => 'サイドレイズ', 'body_part' => '肩'],
            // その他、必要な種目を追加
        ];
        
        foreach ($init_exercises as $exercise) {
            $data = new Exercises();
            $data->name = $exercise['name'];
            $data->body_part = $exercise['body_part'];
            $data->save();
        }
    }
}

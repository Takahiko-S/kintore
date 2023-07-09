<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;


class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // メニューのデータを作成
        $menus = [
            [
                'user_id' => 1,
                'name' => 'メニュー1',
                'description' => 'メニュー1の説明',
            ],
            [
                'user_id' => 1,
                'name' => 'メニュー2',
                'description' => 'メニュー2の説明',
            ],
            // 追加のメニューデータをここに追記
        ];

        // メニューデータをデータベースに挿入
        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }
    }
}
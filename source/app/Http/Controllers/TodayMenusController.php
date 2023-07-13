<?php

namespace App\Http\Controllers;

use App\Models\Exercises;
use App\Models\Menu;
use App\Models\MenuExercise;
use Illuminate\Http\Request;

class TodayMenusController extends Controller
{
    public function todayMenu()
    {
        $menu = Menu::with('menuExercises.exercise')->orderBy('id', 'asc')->first();
        return view('contents.today_menu', compact('menu'));
    }

    public function todayEdit(string $id)
    {
        $menu = Menu::find($id);
        return view('contents.today_edit', compact('menu'));
    }


    public function todayUpdate(Request $request, string $id)
    {
        $menu = Menu::find($id);
        $menu->menu_name = $request->menu_name;
        foreach ($request->input('menu_exercises') as $index => $menuExerciseData) {
            // データが既存のメニューのものか新規のものかを判断する
            if ($index < count($menu->menuExercises)) {
                // 既存のメニューの更新
                $menuExercise = $menu->menuExercises[$index];
                $menuExercise->exercise->name = $menuExerciseData['name'];
                $menuExercise->reps = $menuExerciseData['reps'];
                $menuExercise->weight = $menuExerciseData['weight'];
                $menuExercise->exercise->save();
                $menuExercise->save();
            } else {
                // 新規メニューの追加
                $exercise = new Exercises();
                $exercise->name = $menuExerciseData['name'];
                $exercise->save();

                $menuExercise = new MenuExercise();
                $menuExercise->reps = $menuExerciseData['reps'];
                $menuExercise->weight = $menuExerciseData['weight'];
                $menuExercise->exercise_id = $exercise->id;
                $menuExercise->menu_id = $menu->id;
                $menuExercise->save();
            }
        }
        $menu->save();
        // メッセージをセッションに保存
        session()->flash('message', 'メニューが更新されました');
        return redirect()->route('today_menu');
    }

    public function todayDestroy(string $id)
    {
        $menuExercise = MenuExercise::find($id);
        $menu_id = $menuExercise->menu_id;
        $menuExercise->delete();
        return redirect()->route('today.edit', ['today' => $menu_id]);
    }
}

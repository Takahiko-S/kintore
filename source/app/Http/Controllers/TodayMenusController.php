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
        //withメソッドを使うことで、リレーション先のデータを一度のクエリで取得できるため、menuExecises::とかかなくてＯＫ
        $menu = Menu::with('menuExercises.exercise')->orderBy('id', 'asc')->first();
        return view('contents.today_menu', compact('menu'));
    }

    public function todayEdit(string $id)
    {
        $menu = Menu::find($id);;
        return view('contents.today_edit', compact('menu'));
    }


    public function todayUpdate(Request $request, string $id)
    {
        dd($request->all());
        $menu = Menu::find($id);
        $menu->name = $request->name;

        foreach ($request->menu_exercises as $index => $menuExerciseData) { //複数ある配列を更新するときの書き方
            // データが既存のメニューのものか新規のものかを判断する
            if ($index < count($menu->menuExercises)) {

                // 既存のメニューの更新
                $menuExercise = $menu->menuExercises[$index];
                $menuExercise->reps = $menuExerciseData['reps'];
                $menuExercise->weight = $menuExerciseData['weight'];
                $menuExercise->order = $menuExerciseData['order'];
                // dd($menuExercise);
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
                $menuExercise->order = $menuExerciseData['order'];
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

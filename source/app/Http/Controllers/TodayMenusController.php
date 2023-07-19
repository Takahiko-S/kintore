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
        //
        $menu = Menu::find($id);;
        $exercises = Exercises::all()->groupBy('body_part');

        return view('contents.today_edit', compact('menu', 'exercises'));
    }


    public function todayUpdate(Request $request, string $id)
    {
        dd($request->all());
        $menu = Menu::find($id);

        $menu->name = $request->name;

        foreach ($request->menu_exercises as $menuExerciseData) {
            $menuExercise = MenuExercise::find($menuExerciseData['id']);

            if ($menuExercise !== null) {
                // 既存のメニューエクササイズを更新
                $menuExercise->reps = $menuExerciseData['reps'];
                $menuExercise->weight = $menuExerciseData['weight'];
                $menuExercise->save();
            } else {
                // 新しいメニューエクササイズを追加
                $newMenuExercise = new MenuExercise();
                $newMenuExercise->menu_id = $menu->id;
                $newMenuExercise->exercise_id = $menuExerciseData['exercise_id'];
                $newMenuExercise->reps = $menuExerciseData['reps'];
                $newMenuExercise->weight = $menuExerciseData['weight'];
                $newMenuExercise->order = count($menu->menuExercises) + 1; // assuming order indicates the number of sets
                $newMenuExercise->save();
            }
        }

        $menu->save();
        session()->flash('message', 'メニューが更新されました');
        return redirect()->route('today_menu');
    }


    public function todayDestroy(string $id)
    {

        $menuExercise = MenuExercise::find($id);
        $menu_id = $menuExercise->menu_id;
        $menuExercise->delete();
        return response()->json(['menu_id' => $menu_id]);  // JSONレスポンスを返す
    }
    public function addExercises(Request $request)
    {
        // Get the request data
        $exerciseIds = $request->input('selectedExercises');
        $menuId = $request->input('menu_id');

        // Retrieve the specific Menu
        $menu = Menu::find($menuId);

        // Save the data
        foreach ($exerciseIds as $exerciseId) {
            $menu->exercises()->attach($exerciseId, ['order' => 1]);
        }

        // Redirect to the menu detail page
        return redirect()->route('today_edit', ['id' => $menu->id]);
    }
  
}

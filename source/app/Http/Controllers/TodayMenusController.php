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

        $menu = Menu::find($id);
        $exercises = Exercises::all()->groupBy('body_part');

        return view('contents.today_edit', compact('menu', 'exercises'));
    }


    public function todayUpdate(Request $request, string $id)
    {
        //dd($request->all());
        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;

        foreach ($request->menu_exercises as $menuExerciseData) {
            if (isset($menuExerciseData['id']) && $menuExerciseData['id'] != 'new') {
                // Existing menu exercise update
                $menuExercise = MenuExercise::find($menuExerciseData['id']);
                if ($menuExercise !== null) {
                    $menuExercise->reps = $menuExerciseData['reps'];
                    $menuExercise->weight = $menuExerciseData['weight'];
                    if (isset($menuExerciseData['memo'])) {
                        $menuExercise->memo = $menuExerciseData['memo'];
                    }
                    $menuExercise->save();
                }
            } else {
                // Add new menu exercise
                $newMenuExercise = new MenuExercise();
                $newMenuExercise->menu_id = $menu->id;
                $newMenuExercise->exercise_id = $menuExerciseData['exercise_id'];
                $newMenuExercise->set = $menuExerciseData['set'];
                $newMenuExercise->reps = $menuExerciseData['reps'];
                $newMenuExercise->weight = $menuExerciseData['weight'];
                if (isset($menuExerciseData['memo'])) {
                    $newMenuExercise->memo = $menuExerciseData['memo'];
                }
                $newMenuExercise->save();
            }
        }

        $menu->save();
        session()->flash('message', 'メニューが更新されました');
        return redirect()->route('today_menu');
    }


    public function todayDestroy(string $id)
    {
        // 送られてきた id から対応する MenuExercise を取得
        $menuExercise = MenuExercise::find($id);

        // 取得した MenuExercise の menu_id と exercise_id を取得
        $menu_id = $menuExercise->menu_id;
        $exercise_id = $menuExercise->exercise_id;

        // 同じ menu_id を持ち、同じ exercise_id を持つすべての MenuExercise を削除
        MenuExercise::where('menu_id', $menu_id)
            ->where('exercise_id', $exercise_id)
            ->delete();

        return response()->json(['menu_id' => $menu_id]);  // JSONレスポンスを返す
    }


    public function addExercises(Request $request)
    {
        // Get the request data
        $exerciseIds = $request->input('selectedExercises');
        $menuId = $request->input('menu_id');

        // Retrieve the specific Menu
        $menu = Menu::find($menuId);
        $currentExercisesCount = $menu->exercises()->count();
        // Save the data
        foreach ($exerciseIds as $exerciseId) {
            $menu->exercises()->attach($exerciseId, ['set' => 1, 'index' => $currentExercisesCount]);
            $currentExercisesCount++; // Increment the index for the next exercise
        }

        // Redirect to the menu detail page
        return redirect()->route('today_edit', ['id' => $menu->id]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\Exercises;
use App\Models\MenuExercise;
use Database\Seeders\MenusTableSeeder;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function schedule_index()
    {
        $menus = Menu::all();
        $exercises = Exercises::all()->groupBy('body_part');
        $body_parts = Exercises::select('body_part')->distinct()->get()->pluck('body_part');

        //dd($menus);
        return view('contents.schedule', compact('menus', 'exercises', 'body_parts'));
    }


    public function scheduleUpdate(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;

        // 送信された全てのメニューエクササイズのIDを配列に格納
        $menuExerciseIds = array_filter(
            array_column($request->menu_exercises, 'id'),
            function ($id) {
                return $id !== 'new';
            }
        );

        // データベースから現在のメニューに関連する全てのメニューエクササイズを取得し、それらが上記の配列に存在しない場合、それらを削除
        MenuExercise::where('menu_id', $id)->whereNotIn('id', $menuExerciseIds)->delete();

        // 以下は既存のコードを維持
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
        return redirect()->route('schedule_index');
    }


    public function schedule_Edit(string $id)
    {
        //
        $menu = Menu::find($id);;
        $exercises = Exercises::all()->groupBy('body_part');

        return view('contents.schedule_edit', compact('menu', 'exercises'));
    }


    public function menuDelete(Request $request)
    {

        Menu::destroy($request->menuId);
        // メニューが全て削除されたかを確認
        if (Menu::count() == 0) {
            // フラッシュメッセージの保存
            session()->flash('message', '全てのメニューが削除されました');
        }

        return response()->json(['status' => 'success']);
    }

    public function addMenu(Request $request)
    {
        //dd($request->all());
        //menuテーブルにデータを保存
        $menu = new Menu();
        $menu->user_id = Auth::id();
        $menu->name = $request->menu_name;
        $menu->save();

        //menu_exerciseテーブルにデータを保存
        if ($request->has('selectedExercises')) {
            foreach ($request->selectedExercises as $exerciseId) {
                $menu_exercise = new MenuExercise();
                $menu_exercise->menu_id = $menu->id;
                $menu_exercise->exercise_id = $exerciseId;
                $menu_exercise->set = 1;
                $menu_exercise->save();
            }
        }

        // Redirect to the menu detail page
        return redirect()->route('today_edit', ['id' => $menu->id]);
    }

    public function addNewExercise(Request $request)
    {
        //dd($request->all());
        //exerciseテーブルにデータを保存
        $exercise = new Exercises();
        $exercise->name = $request->exercise_name;
        $exercise->body_part = $request->body_part;
        $exercise->save();

        // Redirect to the menu detail page
        return redirect()->route('schedule_index');
    }

    public function addExercise(Request $request)
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
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
        $menus = Menu::where('user_id', Auth::id())->orderBy('order')->get();
        $menuExists = Menu::where('user_id', Auth::id())->count() > 0;
        $exercises = Exercises::all()->groupBy('body_part');
        $body_parts = Exercises::select('body_part')->distinct()->get()->pluck('body_part');

        //dd($menus);
        return view('contents.schedule', compact('menus', 'exercises', 'body_parts', 'menuExists'));
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
        // dd($request->all());
        $menu = new Menu();
        $menu->user_id = Auth::id();
        $menu->name = $request->menu_name;

        // Get the position where the new menu should be inserted
        $insertPosition = $request->insert_position ? intval($request->insert_position) + 1 : Menu::where('user_id', Auth::id())->count() + 1;
        // Get all the menus after the insert position
        $menusToUpdate = Menu::where('user_id', Auth::id())
            ->where('order', '>=', $insertPosition)
            ->get();

        // Update the order of the affected menus
        foreach ($menusToUpdate as $menuToUpdate) {
            $menuToUpdate->order++;
            $menuToUpdate->save();
        }

        // Now we can set the order of the new menu and save it
        $menu->order = $insertPosition;
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
        return redirect()->route('schedule_index', ['id' => $menu->id]);
    }

    public function addNewExercise(Request $request)
    {
        //exerciseテーブルにデータを保存
        $exercise = new Exercises();
        $exercise->name = $request->exercise_name;

        // ユーザーが新しい部位を追加した場合、それを使用
        if (!empty($request->new_body_part)) {
            $exercise->body_part = $request->new_body_part;

            // 新しい部位を部位テーブルに追加するロジックが必要かもしれません
        } else {
            // そうでなければ、選択された既存の部位を使用
            $exercise->body_part = $request->body_part;
        }

        $exercise->save();

        return response()->json([
            'status' => 'success',
            'message' => 'New exercise added successfully.'
        ]);
    }
    public function getNewExercises() //モーダルで更新された新しいエクササイズを取得
    {
        // Exercisesテーブルからすべてのエクササイズを取得
        $exercises = Exercises::all();

        // エクササイズのリストをJSON形式で返す
        return response()->json($exercises);
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

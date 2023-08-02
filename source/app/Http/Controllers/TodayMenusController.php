<?php

namespace App\Http\Controllers;

use App\Models\Exercises;
use App\Models\History;
use App\Models\Menu;
use App\Models\MenuExercise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodayMenusController extends Controller

{

    //-------------------------------------------------------------------------------------------------------------------------------------------

    public function todayMenu()
    {
        $user_id = Auth::id();
        $menus = Menu::with(['menuExercises.exercise', 'menuExercises.histories' => function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        }])->orderBy('id', 'asc')->get();
        $exercises = Exercises::all()->groupBy('body_part');
        $menu = $menus->first(function ($m) use ($user_id) {
            return !$m->isCompleted($user_id);
        });

        if (!$menu) {
            Session::flash('message', 'メニューがありません。');
        }

        return view('contents.today_menu', compact('menu'));
    }




    public function completeMenu(Request $request, $id)
    {
        //dd($request->all());
        $completed_exercises = $request->input('completed_exercises');
        if (!$completed_exercises) { // Make sure $completed_exercises is not null
            return redirect()->route('today_menu', ['id' => $id])->with('error', 'No exercises selected.');
        }

        // Here we need to retrieve the menu using the passed $id
        $menu = Menu::find($id);

        foreach ($completed_exercises as $completed_exercise) {

            // For each completed exercise, we get the MenuExercise instance
            $menuExercise = MenuExercise::find($completed_exercise);
            //dd($menuExercise);
            // Now, we can use $menu and $menuExercise
            History::create([
                'user_id' => Auth::id(),
                'menu_id' => $menu->id,
                'exercise_id' => $menuExercise->exercise_id,
                'menu_exercise_id' => $completed_exercise,
                'exercise_date' => Carbon::now(),
                'menu_name' => $menu->name,
                'exercise_name' => $menuExercise->exercise->name,
                'sets' => $menuExercise->set,
                'weight' => $menuExercise->weight,
                'reps' => $menuExercise->reps,
                'memo' => '', // Update this based on your requirements
                'is_completed' => false,
            ]);
        }

        // Redirect to the current menu page
        return redirect()->route('today_menu', ['id' => $id]);
    }

    public function todayComplete($id)
    {
        $menu = Menu::find($id);

        foreach ($menu->menuExercises as $menuExercise) {
            History::where('menu_exercise_id', $menuExercise->id)
                ->where('is_completed', false)
                ->update(['is_completed' => true]);
        }

        return redirect()->route('today_menu', ['id' => $id]);
    }



    //-------------------------------------------------------------------------------------------------------------------------------------------
    public function todayEdit(string $id)
    {

        $menu = Menu::find($id);
        $exercises = Exercises::all()->groupBy('body_part');

        return view('contents.today_edit', compact('menu', 'exercises'));
    }


    public function todayUpdate(Request $request, string $id)
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\Exercises;
use App\Models\MenuExercise;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function schedule_index()
    {
        $menus = Menu::all();
        //dd($menus);
        return view('contents.schedule', compact('menus'));
    }


    public function schedule_store(Request $request)
    {
        $exercise = new Exercises();
        $exercise->name = $menuExerciseData['name'];
        $exercise->save();

        $menuExercise = new MenuExercise();
        $menuExercise->reps = $menuExerciseData['reps'];
        $menuExercise->weight = $menuExerciseData['weight'];
        $menuExercise->exercise_id = $exercise->id;
        $menuExercise->menu_id = $menu->id;
        $menuExercise->save();

        // 成功した場合はリダイレクトやレスポンスを返すなどの処理を行う

        // 例: リダイレクト
        return redirect()->route('schedule.index')->with('success', 'スケジュールが保存されました。');
    }






    public function schedule_Edit(string $id)
    {
        //
        $menu = Menu::find($id);;
        $exercises = Exercises::all()->groupBy('body_part');
        
        return view('contents.schedule_edit', compact('menu', 'exercises'));
    }

    public function schedule_update(Request $request, $id)
    {

        $menu = Menu::find($id);

        // メニューが存在しない場合は404エラー
        if (!$menu) {
            abort(404);
        }

        // データを更新
        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->save();

        return redirect()->route('schedule.edit', $menu->id)
            ->with('message', 'スケジュールを更新しました。');
    }
}

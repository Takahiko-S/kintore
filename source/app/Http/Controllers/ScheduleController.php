<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exercises;
use App\Models\Menu;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function schedule_index()
    {
        $schedule = Menu::find($id);
        //dd($menus);
        return view('contents.schedule', compact('schedule'));
    }


    public function schedule_store(Request $request)
    {
        $exercise = new Exercise();
        $exercise->name = $menuExerciseData['name'];
        $exercise->save();

        $menuExercise = new MenuExercise;
        $menuExercise->planned_sets = $menuExerciseData['planned_sets'];
        $menuExercise->weight = $menuExerciseData['weight'];
        $menuExercise->exercise_id = $exercise->id;
        $menuExercise->menu_id = $menu->id;
        $menuExercise->save();

        // 成功した場合はリダイレクトやレスポンスを返すなどの処理を行う

        // 例: リダイレクト
        return redirect()->route('schedule.index')->with('success', 'スケジュールが保存されました。');
    }






    public function schedule_edit($id)
    {
        $menu = Menu::find($id);

        // メニューが存在しない場合は404エラー
        if (!$menu) {
            abort(404);
        }

        return view('contents.schedule.edit', [
            'menu' => $menu,
            'title' => 'スケジュール編集 - ' . $menu->name, // タイトルを設定
        ]);
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
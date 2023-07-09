<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exercises;
use App\Models\Menu;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::all();
        //dd($menus);
        return view('contents.schedule', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach ($validatedData['menus'] as $menuIndex => $menuData) {
            $menu = new Menu();
            $menu->user_id = Auth::user()->id; // もしユーザー認証が必要な場合は適切な方法でユーザーIDを取得する
            $menu->name = 'メニュー ' . ($menuIndex + 1);
            $menu->save();

            // メニューに関連する種目を保存する
            foreach ($menuData as $exerciseName) {
                $exercise = Exercises::firstOrCreate(['name' => $exerciseName]);
                $menu->exercises()->attach($exercise->id);
            }
        }

        // 成功した場合はリダイレクトやレスポンスを返すなどの処理を行う

        // 例: リダイレクト
        return redirect()->route('schedule.index')->with('success', 'スケジュールが保存されました。');
    }


    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
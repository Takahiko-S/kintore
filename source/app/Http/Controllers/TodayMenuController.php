<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class TodayMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 最新のメニューを取得します
        $menu = Menu::with('menuExercises.exercise')->orderBy('id', 'desc')->first();
        
        // ビューにデータを渡して表示
        return view('contents.today_menu', compact('menu'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $today = Menu::find($id);
        return view('contents.today_edit',compact('today'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // メニューをIDで検索
        $menu = Menu::find($id);
        
        // メニュー名を更新
        $menu->name = $request->input('name');
        
        // 種目を更新
        foreach ($request->input('menu_exercises') as $index => $menuExerciseData) {
            $menuExercise = $menu->menuExercises[$index];
            $menuExercise->exercise->name = $menuExerciseData['name'];
            $menuExercise->planned_sets = $menuExerciseData['planned_sets'];
            $menuExercise->weight = $menuExerciseData['weight'];
            $menuExercise->exercise->save();
            $menuExercise->save();
        }
        
        // メニューを保存
        $menu->save();
        
        // メッセージをセッションに保存
        session()->flash('message', 'メニューが更新されました');
        
        // メニュー一覧ページにリダイレクト
        return redirect()->route('today.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->input('m') ? intval($request->input('m')) : intval(date('m'));
        $year = $request->input('y') ? intval($request->input('y')) : intval(date('Y'));
        
        // 前の月と次の月を計算
        $prevMonth = $month - 1;
        $nextMonth = $month + 1;
        $prevYear = $year;
        $nextYear = $year;
        
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        }
        
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear = $year + 1;
        }
        
        $calendar_data = $this->makeCalendarData($year, $month);
        
        foreach ($calendar_data as &$data) {
            $d = explode('-', $data['date']);
            $data['date'] = intval($d[2]);
        }
        
        return view('contents.index', compact('calendar_data', 'year', 'month', 'prevMonth', 'nextMonth', 'prevYear', 'nextYear'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(History $history)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(History $history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, History $history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(History $history)
    {
        //
    }
    
    private function makeCalendarData($year,$month){
        $youbi = array('日','月','火','水','木','金','土',);
        $calendar_array = array();
        //print $month . "<br>";
        // $year = date('Y');
        //print $year . "<br>";
        $date = new \DateTime($year."-" . $month . "-1");
        
        $start = $date->format('w');
        $num= -$start . " day";
        if($start != 0){
            $date->modify($num);
        }
        
        for($i = $start; $i > 0; $i--){
            //print $date->format('Y-m-d') . $youbi[$date->format('w')] . "<br>";
            
            array_push($calendar_array,array('date' =>$date->format('Y-m-d'),'week' =>$youbi[$date->format('w')]));
            $date->modify('+1 day');
        }
        
        while($date->format('m') == $month){
            //print $date->format('Y-m-d') . $youbi[$date->format('w')] . "<br>";
            array_push($calendar_array,array('date' =>$date->format('Y-m-d'),'week' =>$youbi[$date->format('w')]));
            $date->modify('+1 day');
            
        }
        while($date->format('w') != 0){//0になるまで回す一週間区切り
            // print $date->format('Y-m-d') . $youbi[$date->format('w')] . "<br>";
            array_push($calendar_array,array('date' =>$date->format('Y-m-d'),'week' =>$youbi[$date->format('w')]));
            $date->modify('+1 day');
        }
        //print $date->format('Y-m-d') . "<br>";
        
        return $calendar_array;
    }
    
    public function top(){
        return view('layouts.index');
    }
}

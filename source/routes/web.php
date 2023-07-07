<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TodayMenuController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HistoryController::class, 'top'])->name('history.top');

Route::middleware('auth')->group(function () {
    Route::get('/index', [HistoryController::class, 'index'])->name('history.index');
    Route::resource('history',HistoryController::class);
    Route::resource('schedule',ScheduleController::class);
    Route::resource('today',TodayMenuController::class);
  
   
    
});

//Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

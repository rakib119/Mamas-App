<?php

use App\Http\Controllers\DailyRoutineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoutineStatusController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();



Route::middleware(['auth'])->group(function ()
{
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('kick/store', [HomeController::class, 'kick_store'])->name('kick.store');
    Route::get('kick/history', [HomeController::class, 'kick_history'])->name('kick.history');
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');
    Route::resource('routine',DailyRoutineController::class)->except('show');
    Route::get('routine/list', [DailyRoutineController::class, 'routine_list'])->name('routine.list');
    Route::put('/routine/{id}/toggle', [DailyRoutineController::class, 'toggle'])->name('routine.toggle');
    Route::post('/routine/mark-completed', [RoutineStatusController::class, 'markCompleted'])->name('routine.markCompleted');
    Route::get('/routine/check-status/{id}', [RoutineStatusController::class, 'checkStatus'])->name('routine.checkStatus');;

});

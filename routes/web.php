<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//スタート画面からマッチング画面へ
Route::get('/gameroom', function () {
    return view('games.gameroom');
})->name('GameRoom');

//マッチング画面から結果画面へ
Route::get('/matching', function(){
    return view('games.result');
})->name('Result');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/gameroom', [GameController::class, 'choose_Theme_CardNumber'])->name('games.choose_Theme_CardNumber');
});

// マッチング関係
Route::get('/dashboard', [RoomController::class, 'dashboard'])->name('dashboard');
Route::post('/matching', [RoomController::class, 'joinRoom'])->name('matching');
Route::get('/games/gameroom/{room}', [RoomController::class, 'gameRoom'])->name('games.gameroom');
Route::get('/check-room-status/{room}', [RoomController::class, 'checkRoomStatus']);

require __DIR__.'/auth.php';

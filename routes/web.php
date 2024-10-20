<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

//ユーザ関係
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//ララベル画面
Route::get('/', function () { return view('welcome'); }); //画面表示

//ダッシュボード画面
Route::get('/home', function () { return view('games.home'); })->middleware(['auth', 'verified'])->name('goHomeRoom'); //ユーザ認証からの画面表示
Route::post('/matching', [GameController::class, 'joinRoom'])->name('goMatchingRoom'); //マッチング画面へ遷移

//テーマ入力
Route::post('/makingTheme', [ThemeController::class, 'store'])->name('MakeTheme');

//マッチング画面
Route::get('/gameroom/{room}', [GameController::class, 'gameRoom'])->name('goGameRoom'); //人数が揃えばゲーム画面へ遷移
Route::get('/check-room-status/{room}', [GameController::class, 'checkRoomStatus']); //人数が揃わなければ待機

//ルーム作成画面by米田
Route::post('/makeroom', [GameController::class, 'makeRoom'])->name('goMakeRoom'); 
Route::get('/check-join-user/{room}',[GameController::class, 'checkJoinUser']); //部屋に参加しているユーザーを定期的に確認

//ルーム参加画面by米田
Route::post('/searchroom', [GameController::class, 'searchRoom'])->name('goSearchRoom');

//ゲーム画面
Route::post('/result/{room}', [GameController::class,'showResult'])->name('goResultRoom'); //結果画面へ遷移

//結果画面
Route::delete('/destory/{room}',[RoomController::class,'destroy'])->name('destroyRoom');   //指定されたルームを削除してダッシュボードへリダイレクト

require __DIR__ . '/auth.php';
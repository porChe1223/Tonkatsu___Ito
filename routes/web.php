<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

//ユーザ関係
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
});

//ララベル画面
Route::get('/', function () {
    return view('welcome');
}); //画面表示

//ホーム画面
Route::get('/home', function () { return view('games.home'); })->middleware(['auth', 'verified'])->name('goHomeRoom'); //ユーザ認証からの画面表示
Route::post('/matching', [RoomController::class, 'goMatchingRoom'])->name('goMatchingRoom'); //マッチング画面へ遷移
Route::post('/breakout_host', [RoomController::class, 'makeBreakoutRoom'])->name('makeBreakoutRoom'); //ブレイクアウトルームを作成
Route::post('/breakout_guest', [RoomController::class, 'joinBreakoutRoom'])->name('joinBreakoutRoom'); //ブレイクアウトルームへ参加

//テーマ入力
Route::post('/makingTheme', [ThemeController::class, 'store'])->name('MakeTheme');

//マッチング画面
Route::get('/check-room-status/{room}', [RoomController::class, 'checkRoomStatus']); //人数が揃えばゲーム画面へ遷移・人数が揃わなければ待機
Route::get('/gameroom/{room}', [GameController::class, 'gameRoom'])->name('GameRoom'); //ゲームルームに入った際にお題と番号をランダム選択
Route::delete('/matching', [RoomController::class, 'removeRoom'])->name('removeRoom'); //マッチングルームを抜けた際自身の情報を部屋から削除

//ブレイクアウト画面
Route::get('/check-join-user/{room}', [RoomController::class, 'checkJoinUser']); //部屋に参加しているユーザーを定期的に確認

//ゲーム画面
Route::post('/makingTheme/{room}', [ThemeController::class, 'store'])->name('MakeThemeInGame'); //新しいお題を作成
Route::get('/get-current-theme/{room}', [ThemeController::class, 'getCurrentTheme']); //現在のお題を更新
Route::post('/result/{room}', [GameController::class, 'showResult'])->name('goResultRoom'); //結果画面へ遷移

//結果画面
Route::delete('/home', [RoomController::class, 'destroyRoom'])->name('destroyRoom'); //指定されたルームを削除してダッシュボードへリダイレクト

require __DIR__ . '/auth.php';

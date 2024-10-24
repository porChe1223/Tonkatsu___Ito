<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\BreakoutController;
use App\Http\Controllers\ResultController;
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

//マッチングルーム関係
Route::post('/matching', [MatchingController::class, 'goMatchingRoom'])->name('goMatchingRoom'); //マッチング画面へ遷移
Route::get('/check-room-status/{room}', [MatchingController::class, 'checkMatchingStatus']); //人数が揃えばゲーム画面へ遷移・人数が揃わなければ待機
Route::delete('/matching', [MatchingController::class, 'removeMatchingRoom'])->name('removeMatchingRoom'); //マッチングルームを抜けた際自身の情報を部屋から削除

//ブレイクアウトルーム関係
Route::post('/breakout_host', [BreakoutController::class, 'makeBreakoutRoom'])->name('makeBreakoutRoom'); //ブレイクアウトルームを作成
Route::post('/breakout_guest', [BreakoutController::class, 'joinBreakoutRoom'])->name('joinBreakoutRoom'); //ブレイクアウトルームへ参加
Route::delete('/breakout_guest', [BreakoutController::class, 'removeBreakoutRoom'])->name('removeBreakoutRoom'); //ブレイクアウトルームを抜けた際自身の情報を部屋から削除
Route::delete('/breakout_host', [BreakoutController::class, 'destroyBreakoutRoom'])->name('destroyBreakoutRoom'); //ホストが抜けたら部屋削除by米田
Route::get('/check-join-user/{room}', [BreakoutController::class, 'checkJoinUser']); //ブレイクアウトルームに参加しているユーザーを定期的に確認

//ゲームルーム関係
Route::get('/gameroom/{room}', [GameController::class, 'gameRoom'])->name('GameRoom'); //ゲームルームに入った際にお題と番号をランダム選択
Route::get('/gameroom_host/{room}', [GameController::class, 'goGameRoomHost'])->name('goGameRoomHost'); //ホストがゲームルームに入った際にお題と番号をランダム選択
Route::get('/gameroom_guest/{room}', [GameController::class, 'goGameRoomGuest'])->name('goGameRoomGuest'); //ゲストがゲームルームに入った際にお題と番号をランダム選択

Route::post('/makingTheme', [ThemeController::class, 'store'])->name('MakeTheme'); //テーマ入力
Route::post('/makingTheme/{room}', [ThemeController::class, 'store'])->name('MakeThemeInGame'); //新しいお題を作成
Route::get('/get-current-theme/{room}', [ThemeController::class, 'getCurrentTheme']); //現在のお題を更新

//結果画面関係
Route::post('/result/host/{room}', [ResultController::class, 'showResult'])->name('goResultRoomHost'); //結果画面(host)へ遷移
Route::post('/result/guest/{room}', [ResultController::class, 'showResult'])->name('goResultRoomGuest'); //結果画面(guest)へ遷移
Route::delete('/result/host/{room}', [ResultController::class, 'removeResultRoom'])->name('removeRoomHost'); //指定されたルームを削除してダッシュボードへリダイレクト
Route::delete('/result/guest/{room}', [ResultController::class, 'removeResultRoom'])->name('removeRoomGuest'); //指定されたルームを削除してダッシュボードへリダイレクト

require __DIR__ . '/auth.php';

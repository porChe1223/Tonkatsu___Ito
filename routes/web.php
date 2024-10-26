<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\BreakoutController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ChatController;
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
Route::delete('/matching/remove', [MatchingController::class, 'removeMatchingRoom'])->name('removeMatchingRoom'); //マッチングルームを抜けた際自身の情報を部屋から削除

//ブレイクアウトルーム関係
Route::post('/breakout_host', [BreakoutController::class, 'makeBreakoutRoom'])->name('makeBreakoutRoom'); //ブレイクアウトルームを作成
Route::post('/breakout_guest', [BreakoutController::class, 'joinBreakoutRoom'])->name('joinBreakoutRoom'); //ブレイクアウトルームへ参加
Route::get('/check-join-user/{room}', [BreakoutController::class, 'checkJoinUser']); //ブレイクアウトルームに参加しているユーザーを定期的に確認
Route::post('/start-game/{roomId}', [BreakoutController::class, 'startGame'])->name('startGame'); //ホストがスタートボタンを押すとゲーム開始
Route::delete('/breakout_host/remove', [BreakoutController::class, 'removeBreakoutRoom'])->name('removeBreakoutRoomHost'); //ホストがブレイクアウトルームを抜けた際自身の情報を部屋から削除
Route::delete('/breakout_guest/remove', [BreakoutController::class, 'removeBreakoutRoom'])->name('removeBreakoutRoomGuest'); //ゲストがブレイクアウトルームを抜けた際自身の情報を部屋から削除

//ゲームルーム関係
Route::get('/gameroom/{room}', [GameController::class, 'gameRoom'])->name('GameRoom'); //ゲームルームに入った際にお題と番号をランダム選択
Route::get('/gameroom_host/{room}', [GameController::class, 'goGameRoomHost'])->name('goGameRoomHost'); //ホストがゲームルームに入った際にお題と番号をランダム選択
Route::get('/gameroom_guest/{room}', [GameController::class, 'goGameRoomGuest'])->name('goGameRoomGuest'); //ゲストがゲームルームに入った際にお題と番号をランダム選択
Route::delete('/gameroom_host/{room}/remove', [GameController::class, 'removeGameRoom'])->name('removeGameRoomHost'); //ホストがゲームルームを抜けた際自身の情報を部屋から削除
Route::delete('/gameroom_guest/{room}/remove', [GameController::class, 'removeGameRoom'])->name('removeGameRoomGuest'); //ゲストがゲームルームを抜けた際自身の情報を部屋から削除
//お題関係
Route::post('/makingTheme', [ThemeController::class, 'store'])->name('MakeTheme'); //お題入力
Route::post('/makingTheme/{room}', [ThemeController::class, 'store'])->name('MakeThemeInGame'); //新しいお題を作成
Route::get('/get-current-theme/{room}', [ThemeController::class, 'getCurrentTheme']); //現在のお題を更新
// チャット関係
Route::get('/chat/{roomId}', [ChatController::class, 'index'])->middleware('auth')->name('chat.index'); // チャットルームの表示
Route::get('/chat/{roomId}/messages', [ChatController::class, 'fetchMessages'])->middleware('auth')->name('chat.fetchMessages'); // メッセージの取得
Route::post('/chat/{roomId}/messages', [ChatController::class, 'sendMessage'])->middleware('auth')->name('chat.sendMessage'); // メッセージの送信


//結果画面関係
Route::get('/check-gameroom-status/{room}', [GameController::class, 'checkGameroomStatus']); //gameroomが終了したかどうかを判定
Route::post('/result/{room}', [ResultController::class, 'showResult'])->name('goResultRoom'); //結果画面(host)へ遷移
Route::post('/result_host/{room}', [ResultController::class, 'showResultHost'])->name('goResultRoomHost'); //結果画面(host)へ遷移
Route::get('/result_guest/{room}', [ResultController::class, 'showResultGuest'])->name('goResultRoomGuest'); //結果画面(guest)へ遷移
Route::post('/result_guest/{room}', [ResultController::class, 'showResult'])->name('goResultRoom'); 
Route::delete('/result_host/{room}/remove', [ResultController::class, 'removeResultRoom'])->name('removeRoomHost'); //指定されたルームを削除してダッシュボードへリダイレクト
Route::delete('/result_guest/{room}/remove', [ResultController::class, 'removeResultRoom'])->name('removeRoomGuest'); //指定されたルームを削除してダッシュボードへリダイレクト

require __DIR__ . '/auth.php';

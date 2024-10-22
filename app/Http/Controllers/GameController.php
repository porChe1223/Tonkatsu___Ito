<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Theme;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function gameRoom(Room $room, Theme $theme, User $user)
    {
        $user = Auth::user();
        //お題選択
        if (is_null($room->theme_id)) { //お題が決まっていなければ
            $choosed_Theme = Theme::inRandomOrder()->first();  //お題のランダム選択
            $room->theme_id = $choosed_Theme->id;
            $room->save(); //DB更新
        } else {
            $choosed_Theme = Theme::find($room->theme_id); // roomsに入っているお題を取得
        }

        $players = $room->participants;

        return view('games.gameroom', ['room' => $room, 'user' => $user, 'choosed_Theme' => $choosed_Theme, 'players' => $players]);
    }




    //結果画面
    public function showResult($room_id, Request $request)
    {
        // みんなのカード番号とそのユーザー情報を取得
        $room = Room::findOrFail($room_id);

        // Roomモデル内のparticipantsを使用して参加者の一覧を取得
        $participants = $room->participants->sortBy('card_number');

        // プレイヤーの順番（送信された順番）
        $player_order = $request->input('answer');

        // 正しい順番（カード番号順で並べたプレイヤー名）
        $correct_order = $participants->pluck('name')->toArray();

        // プレイヤーの順番が正しいかを判定
        $isCorrect = $player_order === $correct_order;

        return view(
            'games.result',
            [
                'isCorrect' => $isCorrect,
                'correct_order' => $correct_order,
                'player_order' => $player_order
            ],
            compact('room', 'participants')
        );
    }
}

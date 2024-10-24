<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //結果画面
    public function showResult($room_id, Request $request)
    {
        $room = Room::findOrFail($room_id); //みんなのカード番号とそのユーザー情報を取得

        $participants = $room->participants->sortBy('card_number'); //Roomモデル内のparticipantsを使用して参加者の一覧を取得

        $player_order = $request->input('answer'); //プレイヤーの順番（送信された順番）

        $correct_order = $participants->pluck('name')->toArray(); //正しい順番（カード番号順で並べたプレイヤー名）

        $isCorrect = $player_order === $correct_order; //プレイヤーの順番が正しいかを判定

        return view(
            'games.result_host',
            [
                'isCorrect' => $isCorrect,
                'correct_order' => $correct_order,
                'player_order' => $player_order,
            ],
            compact('room', 'participants')
        );
    }


    //ゲームが終了した部屋は削除
    public function destroyRoom(Room $room)
    {
        $participants = $room->participants; //参加者のリストを取得
        //ここに何もユーザの情報が入っていない

        $room->delete(); //部屋を削除

        foreach ($participants as $participant) { // 参加者全員に部屋が削除されたことを通知する処理を追加
            session()->put('room_deleted', true); //セッションにメッセージをセットして、ユーザーをリダイレクト
        }

        return redirect()->route('goHomeRoom')->with('message', 'ゲームが終了しました');
    }
}

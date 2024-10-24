<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Support\Facades\Auth;
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

    public function removeResultRoom(Room $room)
    {
        $yourRoomUser = RoomUser::where('user_id', Auth::id())->first(); //自身が登録されているroom_userを取得

        if ($yourRoomUser) {
            $room = Room::find($yourRoomUser->room_id); // 自身が今入っているroomを取得

            $yourRoomUser->delete(); // 自身が登録されているroom_userを削除

            $room->player_count -= 1; // 部屋のプレイヤーを減らす
            $room->save(); // DBに保存

            // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }

        return view('games.home', ['room' => $room])->with('message', 'ゲームが終了しました');
    }
}

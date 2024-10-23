<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Support\Facades\Auth;

class MatchingController extends Controller
{
    //マッチング画面
    public function goMatchingRoom(User $user)
    {
        $room = Room::where('status', 'waiting')->first(); // 既存の空き部屋を探す

        if (!$room) {
            $room = Room::create([ // 新しい部屋を作成
                'status' => 'waiting'
            ]);
        }

        $room->player_count += 1; //部屋のプレイヤーの増加
        $room->save(); //DBに保存

        RoomUser::firstOrCreate([ // 部屋に参加者を追加
            'room_id' => $room->id,
            'user_id' => Auth::id(),
        ]);

        $participants = $room->participants; //部屋の参加者を取得

        //カード番号選択
        $user = Auth::user();

        $usedCardNumbers = $participants->pluck('card_number')->toArray(); // 使用済みのカード番号を取得（NULLを除外）

        do { // 使用されていないカード番号を見つける
            $choosed_CardNumber = rand(0, 100);
        } while (in_array($choosed_CardNumber, $usedCardNumbers));

        $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
        $user->save();

        //GameRoomへの遷移
        if ($room->player_count == 3) { //揃ったら
            $room->update(['status' => 'full']); //部屋のステータスを変更

            return redirect()->route('GameRoom', ['room' => $room]); //gameroomに遷移・部屋番号を返す
        }

        return view('games.matching', ['room' => $room]); // 揃うまで待機
    }

    // マッチングルームの状態を確認するAPI
    public function checkMatchingStatus($roomId)
    {
        $room = Room::find($roomId);

        $isFull = $room->participants()->count() == 3; // 参加者が2人以上いるかどうかを確認

        return response()->json(['isFull' => $isFull, 'player_count' => $room->player_count]);
    }

    //マッチングルームを抜けたら自分の情報を消す
    public function removeMatchingRoom(Room $room)
    {
        $yourRoomUser = RoomUser::where('user_id', Auth::id())->first(); //自身が登録されているroom_userを取得
        $room = Room::find($yourRoomUser->room_id); //自身が今入っているroomを取得

        $yourRoomUser->delete(); //自身が登録されているroom_userを削除
        

        $room->player_count -= 1; //部屋のプレイヤーを減らす
        $room->save(); //DBに保存

        return response(null, 200);
    }
}

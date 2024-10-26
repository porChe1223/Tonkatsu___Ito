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
        //エラー対策として、まずは過去の自分の部屋データを削除
        while ($yourRoomUser = RoomUser::where('user_id', Auth::id())->first()) { //自身が登録されているroom_userがある限り
            $room = Room::find($yourRoomUser->room_id); // 自身が今入っているroomを取得

            $yourRoomUser->delete(); // 自身が登録されているroom_userを削除

            $room->player_count -= 1; // 部屋のプレイヤーを減らす
            $room->save(); // DBに保存

            // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }

        $room = Room::where('status', 'waiting')->first(); // 既存の空き部屋を探す

        if (!$room) {
            $room = Room::create([ // 新しい部屋を作成
                'status' => 'waiting',
                'player_count' => 0,
            ]);

            RoomUser::firstOrCreate([ // 部屋に参加者を追加
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);
            
            $room->player_count += 1; //部屋のプレイヤーの増加
            $room->save(); //DBに保存

            $participants = $room->participants; //部屋の参加者を取得

            //カード番号選択
            $user = Auth::user();

            $usedCardNumbers = $participants->pluck('card_number')->toArray(); // 使用済みのカード番号を取得（NULLを除外）

            do { // 使用されていないカード番号を見つける
                $choosed_CardNumber = rand(0, 100);
            } while (in_array($choosed_CardNumber, $usedCardNumbers));

            if ($user instanceof \App\Models\User) {
                $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
                $user->save();
            }

            //GameRoomへの遷移
            if ($room->participants()->count() == 2) { //揃ったら
                $room->update(['status' => 'start']); //部屋のステータスを変更

                return redirect()->route('goGameRoomHost', ['room' => $room]); //gameroomに遷移・部屋番号を返す
            }

            
        } else {
            RoomUser::firstOrCreate([ // 部屋に参加者を追加
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);

            $room->player_count += 1;
            $room->save(); //DBに保存

            $participants = $room->participants; //部屋の参加者を取得

            //カード番号選択
            $user = Auth::user();

            $usedCardNumbers = $participants->pluck('card_number')->toArray(); // 使用済みのカード番号を取得（NULLを除外）

            do { // 使用されていないカード番号を見つける
                $choosed_CardNumber = rand(0, 100);
            } while (in_array($choosed_CardNumber, $usedCardNumbers));

            if ($user instanceof \App\Models\User) {
                $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
                $user->save();
            }

            //GameRoomへの遷移
            if ($room->participants()->count() == 2) { //揃ったら
                $room->update(['status' => 'start']); //部屋のステータスを変更

                return redirect()->route('goGameRoomGuest', ['room' => $room]); //gameroomに遷移・部屋番号を返す
            }
        }
        return view('games.matching', ['room' => $room]); // 揃うまで待機
    }

    // マッチングルームの状態を確認するAPI
    public function checkMatchingStatus($roomId)
    {
        $room = Room::find($roomId);

        $isStarted = $room->participants()->count() == 2; // 揃ったかどうかを確認

        return response()->json(['isStarted' => $isStarted, 'player_count' => $room->player_count]);
    }

    //マッチングルームを抜けたら自分の情報を消す
    public function removeMatchingRoom()
    {
        while ($yourRoomUser = RoomUser::where('user_id', Auth::id())->first()) { //自身が登録されているroom_userがある限り
            $room = Room::find($yourRoomUser->room_id); // 自身が今入っているroomを取得

            $yourRoomUser->delete(); // 自身が登録されているroom_userを削除

            $room->player_count -= 1; // 部屋のプレイヤーを減らす
            $room->save(); // DBに保存

            // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }

        return response(null, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Theme;
use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BreakoutController extends Controller
{
    //ブレイクアウトルーム作成画面
    public function makeBreakoutRoom(User $user, Room $room)
    {
        //エラー対策として、まずは過去の自分の部屋データを削除
        while($yourRoomUser = RoomUser::where('user_id', Auth::id())->first()){ //自身が登録されているroom_userがある限り
            $room = Room::find($yourRoomUser->room_id); // 自身が今入っているroomを取得

            $yourRoomUser->delete(); // 自身が登録されているroom_userを削除

            $room->player_count -= 1; // 部屋のプレイヤーを減らす
            $room->save(); // DBに保存

            // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }

        //ルームを作成して待機画面に移動by米田
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

        $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
        $user->save();

        return view(
            'games.breakout_host',
            [
                'room' => $room,
                'participants' => $room->participants,
                'showStartButton' => $room->player_count >= 2 // 揃うまで待機
            ]
        );
    }

    // スタートボタンでルームのステータスを更新
    public function startGame($roomId)
    {
        $room = Room::find($roomId);
        $room->update(['status' => 'start']); // ステータスをstartに変更
        return response()->json(['success' => true]);
    }

    //部屋番号を入力してブレイクアウトルームに参加画面by米田
    public function joinBreakoutRoom(Request $request)
    {
        //エラー対策として、まずは過去の自分の部屋データを削除
        while($yourRoomUser = RoomUser::where('user_id', Auth::id())->first()){ //自身が登録されているroom_userがある限り
            $room = Room::find($yourRoomUser->room_id); // 自身が今入っているroomを取得

            $yourRoomUser->delete(); // 自身が登録されているroom_userを削除

            $room->player_count -= 1; // 部屋のプレイヤーを減らす
            $room->save(); // DBに保存

            // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }

        $roomId = $request->input('roomId'); //入力された部屋番号を保持
        $room = Room::find($roomId);

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

        $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
        $user->save();
        
        //GameRoomへの遷移
        if ($room->participants()->count() == 2) { //揃ったら
            $room->update(['status' => 'ready']); //部屋のステータスを変更
        }

        if ($room->status == 'start') {
            return redirect()->route('goGameRoomGuest', ['room' => $room]); //gameroom(guest)に遷移
        }

        return view('games.breakout_guest', ['room' => $room, 'participants' => $participants]); // 揃うまで待機
    }

    // ブレイクアウトルームに参加しているユーザーと人数を確認by米田
    public function checkJoinUser($roomId)
    {
        $room = Room::find($roomId);

        $isReady = $room->participants()->count() == 2;

        $participants = $room->participants; //部屋の参加者を取得

        return response()->json(['isReady' => $isReady, 'isStarted' => $room->status === 'start', 'player_count' => $room->player_count, 'participants' => $participants]);
    }

    //ブレイクアウトルームを抜けたら自分の情報を消す
    public function removeBreakoutRoom()
    {
        while($yourRoomUser = RoomUser::where('user_id', Auth::id())->first()){ //自身が登録されているroom_userがある限り
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

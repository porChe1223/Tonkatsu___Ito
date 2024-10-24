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

        //GameRoomへの遷移
        if ($room->player_count == 2) { //揃ったら
            $room->update(['status' => 'full']); //部屋のステータスを変更
            
            return redirect()->route('goGameRoomHost', ['room' => $room]); //gameroom(host)に遷移・部屋番号を返す
        }

        return view('games.breakout_host', ['room' => $room]); // 揃うまで待機
    }

    //部屋番号を入力してブレイクアウトルームに参加画面by米田
    public function joinBreakoutRoom(Request $request)
    {
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
            $room->update(['status' => 'full']); //部屋のステータスを変更
            
            return redirect()->route('goGameRoomGuest', ['room' => $room]); //gameroom(guest)に遷移
        }

        return view('games.breakout_guest', ['room' => $room]); // 揃うまで待機
    }

    // ブレイクアウトルームに参加しているユーザーと人数を確認by米田
    public function checkJoinUser($roomId)
    {
        $room = Room::find($roomId);

        $isFull = $room->participants()->count() == 2;

        return response()->json(['isFull' => $isFull, 'player_count' => $room->player_count, 'participants' => $room->participants]);
    }

    //ブレイクアウトルームを抜けたら自分の情報を消す
    public function removeBreakoutRoom(Request $request)
    {
        $roomId = $request->input('room_id');
        $room = Room::find($roomId); //自身が今入っているroomを取得

        if ($room) {
            // RoomUserテーブルからユーザーのデータを削除
            RoomUser::where('room_id', $room->id)
                ->where('user_id', Auth::id())
                ->delete();
    
            $room->decrement('player_count');
            $room->save();
        

        // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }

        return response(null, 200);
    }

    //ホストが抜けたらその部屋を削除by米田
    public function destroyBreakoutRoom(Request $request)
    {
        $roomId = $request->input('room_id');
        $room = Room::find($roomId); //自身が今入っているroomを取得
    
        if ($room) {
            RoomUser::where('room_id', $room->id)
                ->where('user_id', Auth::id())
                ->delete();
    
            $room->decrement('player_count');
            $room->save();
    
            // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }
    
        return response(null, 200);
        
    }
}

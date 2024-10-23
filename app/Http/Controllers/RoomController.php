<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Theme;
use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    //ブレイクアウトルーム作成画面
    public function makeBreakoutRoom(Theme $theme, User $user, Room $room)
    {
        //ルームを作成して待機画面に移動by米田
        $room = Room::create([ // 新しい部屋を作成
            'status' => 'waiting'
        ]);
        
        $room->player_count += 1; //部屋のプレイヤーの増加
        $room->save(); //DBに保存

        RoomUser::firstOrCreate([ // 部屋に参加者を追加
            'room_id' => $room->id,
            'user_id' => Auth::id(),
        ]);

        //カード番号選択
        $user = Auth::user();

        $participants = $room->participants; //部屋の参加者を取得

        $usedCardNumbers = $participants->pluck('card_number')->toArray(); // 使用済みのカード番号を取得（NULLを除外）
        
        do { // 使用されていないカード番号を見つける
            $choosed_CardNumber = rand(0, 100);
           } while (in_array($choosed_CardNumber, $usedCardNumbers));

        $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
        $user->save();

        // みんなのカード番号とそのユーザー情報を取得
        $room = Room::findOrFail($room->id);

        // Roomモデル内のparticipantsを使用して参加者の一覧を取得
        $participants = $room->participants;

        //Breakoutへの遷移
        if ($room->player_count == 2) { //揃ったら
            $room->update(['status' => 'full']); //部屋のステータスを変更
            
            return redirect()->route('GameRoom', ['room' => $room]); //gameroomに遷移・部屋番号を返す
        }

        return view('games.breakout_host', ['room' => $room]); // 揃うまで待機
    }

    //部屋番号を入力してブレイクアウトルームに参加画面by米田
    public function joinBreakoutRoom(Request $request)
    {
        $roomId = $request->input('roomId');
        $room = Room::find($roomId);

        $room->player_count += 1; //部屋のプレイヤーの増加
        $room->save(); //DBに保存

        RoomUser::firstOrCreate([ // 部屋に参加者を追加
            'room_id' => $room->id,
            'user_id' => Auth::id(),
        ]);

        //カード番号選択
        $user = Auth::user();

        $participants = $room->participants; //部屋の参加者を取得

        $usedCardNumbers = $participants->pluck('card_number')->toArray(); // 使用済みのカード番号を取得（NULLを除外）
        
        do { // 使用されていないカード番号を見つける
            $choosed_CardNumber = rand(0, 100);
           } while (in_array($choosed_CardNumber, $usedCardNumbers));

        $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
        $user->save();

        // みんなのカード番号とそのユーザー情報を取得
        $room = Room::findOrFail($room->id);

        // Roomモデル内のparticipantsを使用して参加者の一覧を取得
        $participants = $room->participants;
        
        //GameRoomへの遷移
        if ($room->player_count == 2) { //揃ったら
            $room->update(['status' => 'full']); //部屋のステータスを変更
            
            return redirect()->route('GameRoom', ['room' => $room]); //gameroomに遷移
        }

        return view('games.breakout_guest', ['room' => $room]); // 揃うまで待機
    }

    // ブレイクアウトルームに参加しているユーザーと人数を確認by米田
    public function checkJoinUser($roomId)
    {
        $room = Room::find($roomId);

        $isFull = $room->participants()->count() == 2;

        return response()->json(['isFull' => $isFull]);
    }

    //ブレイクアウトルームを抜けたら自分の情報を消す
    public function removeBreakoutRoom(Room $room)
    {
        $yourRoomUser = RoomUser::where('user_id', Auth::id())->first(); //自身が登録されているroom_userを取得
        $room = Room::find($yourRoomUser->room_id); //自身が今入っているroomを取得

        $yourRoomUser->delete(); //自身が登録されているroom_userを削除

        $room->player_count -= 1; //部屋のプレイヤーを減らす
        $room->save(); //DBに保存

        return response(null, 200);
    }




    //ゲームルームを抜けたら自分の情報を消す
    public function removeGameRoom(Room $room)
    {
        $yourRoomUser = RoomUser::where('user_id', Auth::id())->first(); //自身が登録されているroom_userを取得
        $room = Room::find($yourRoomUser->room_id); //自身が今入っているroomを取得

        $yourRoomUser->delete(); //自身が登録されているroom_userを削除
        

        $room->player_count -= 1; //部屋のプレイヤーを減らす
        $room->save(); //DBに保存

        return response(null, 200);
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

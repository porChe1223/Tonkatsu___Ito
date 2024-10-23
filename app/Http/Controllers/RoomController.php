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

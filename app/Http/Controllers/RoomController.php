<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    // 部屋の状態を確認するAPI
    public function checkRoomStatus($roomId)
    {
        $room = Room::find($roomId);

        $isFull = $room->participants()->count() == 3; // 参加者が3人以上いるかどうかを確認

        $joiningUserId = $room->participants(); //参加者を取得

        return response()->json(['isFull' => $isFull, 'joiningUserId' => $joiningUserId]);
    }

    // 部屋に参加しているユーザーと人数を確認by米田
    public function checkJoinUser($roomId)
    {
        $room = Room::find($roomId);

        $isFull = $room->participants()->count() == 3;

        $joiningUserId = $room->participants();

        return response()->json(['isFull' => $isFull, 'joiningUserId' => $joiningUserId]);
    }

    //ゲームが終了した部屋は削除
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('goHomeRoom');
    }
}

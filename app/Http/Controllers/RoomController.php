<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /*
        以下はすべてGameControllerに移動させたため隠す
        
    public function joinRoom(Request $request)
    {
        // 既存の空き部屋を探す
        $room = Room::where('status', 'waiting')->first();

            if (!$room) {
            // 新しい部屋を作成
            $room = Room::create([
                'status' => 'waiting'
            ]);
        }

            // 部屋に参加者を追加
        RoomUser::firstOrCreate([
            'room_id' => $room->id,
            'user_id' => Auth::id(),
        ]);

            // もし2人揃ったら、部屋のステータスを変更してgameroomにリダイレクト
        if ($room->participants()->count() == 2) {
            $room->update(['status' => 'full']);
            return redirect()->route('games.gameroom', ['room' => $room->id]);
        }

            // 2人になるまで待機画面に移行
        return view('games.matching', ['room' => $room]);
    }

        public function gameRoom(Room $room)
    {
        return view('games.gameroom', ['room' => $room]);
    }
    
    */

    // 部屋の状態を確認するAPI
    // public function checkRoomStatus($roomId)
    // {
    //     $room = Room::find($roomId);

    //     // 参加者が2人以上いるかどうかを確認
    //     $isFull = $room->participants()->count() == 2;

    //     return response()->json(['isFull' => $isFull]);
    // }
}

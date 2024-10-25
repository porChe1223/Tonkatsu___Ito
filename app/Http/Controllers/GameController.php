<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Theme;
use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function gameRoom(Room $room, Theme $theme, User $user)
    {
        $user = Auth::user();
        //お題選択
        if (is_null($room->theme_id)) { //お題が決まっていなければ
            $choosed_Theme = Theme::inRandomOrder()->first();  //お題のランダム選択
            $room->theme_id = $choosed_Theme->id;
            $room->save(); //DB更新
        } else {
            $choosed_Theme = Theme::find($room->theme_id); // roomsに入っているお題を取得
        }

        $players = $room->participants;

        return view('games.gameroom', ['room' => $room, 'user' => $user, 'choosed_Theme' => $choosed_Theme, 'players' => $players]);
    }

    public function goGameRoomHost(Room $room, Theme $theme, User $user)
    {
        $user = Auth::user();
        //お題選択
        if (is_null($room->theme_id)) { //お題が決まっていなければ
            $choosed_Theme = Theme::inRandomOrder()->first();  //お題のランダム選択
            $room->theme_id = $choosed_Theme->id;
            $room->save(); //DB更新
        } else {
            $choosed_Theme = Theme::find($room->theme_id); // roomsに入っているお題を取得
        }

        $players = $room->participants;

        return view('games.gameroom_host', ['room' => $room, 'user' => $user, 'choosed_Theme' => $choosed_Theme, 'players' => $players]);
    }

    public function goGameRoomGuest(Room $room, Theme $theme, User $user)
    {
        $user = Auth::user();
        //お題選択
        if (is_null($room->theme_id)) { //お題が決まっていなければ
            $choosed_Theme = Theme::inRandomOrder()->first();  //お題のランダム選択
            $room->theme_id = $choosed_Theme->id;
            $room->save(); //DB更新
        } else {
            $choosed_Theme = Theme::find($room->theme_id); // roomsに入っているお題を取得
        }

        $players = $room->participants;

        return view('games.gameroom_guest', ['room' => $room, 'user' => $user, 'choosed_Theme' => $choosed_Theme, 'players' => $players]);
    }

    public function removeGameRoom(Room $room)
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

        return view('games.home', ['room' => $room])->with('message', 'ゲームを退出しました');
    }
}

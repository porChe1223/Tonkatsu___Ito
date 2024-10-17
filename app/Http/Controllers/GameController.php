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
    //マッチング画面
    public function joinRoom()
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
    
    

    //ゲーム画面
    // public function gameRoom(Room $room)
    // {
    //     return view('games.gameroom', ['room' => $room]);
    // }

    public function choose_Theme_CardNumber(){
        // $room = Room::find(1);

        /* 
            テーマを一回のみ選択させる
        　　部屋ができないと検証できないためいったん隠す

        if(is_null($room->theme_id)){ //テーマが選ばれていない（rooomsに入っていない）なら
            //テーマをランダム選択
            $choosed_Theme = Theme::inRandomOrder()->first();
            $room->theme_id = $choosed_Theme->id;
            $room->save();
        } else { // 既にテーマが決まっている場合
            //roomsに入っているテーマを取得
            $choosed_Theme = Theme::find($room->theme_id);
        }

        */

        $choosed_Theme = Theme::inRandomOrder()->first();

        //カード番号取得

        $user = Auth::user();

        // 使用済みのカード番号を取得
        $usedCardNumbers = User::pluck('card_number')->toArray();

        // 使用されていないカード番号を見つける
        do {
            $choosed_CardNumber = rand(0, 100);
        } while (in_array($choosed_CardNumber, $usedCardNumbers));

        //選ばれたカード番号をデータベースに保存
        $user->card_number = $choosed_CardNumber;
        $user->save();

        return view('games.gameroom', compact('user', 'choosed_Theme'));
    }
}

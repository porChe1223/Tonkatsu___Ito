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
    public function joinRoom(Theme $theme, User $user)
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
        //カード番号選択
        $user = Auth::user();

        $usedCardNumbers = User::whereNotNull('card_number')->pluck('card_number')->toArray(); // 使用済みのカード番号を取得（NULLを除外）
        
        do { // 使用されていないカード番号を見つける
            $choosed_CardNumber = rand(0, 100);
           } while (in_array($choosed_CardNumber, $usedCardNumbers));

        $user->card_number = $choosed_CardNumber; // 選ばれたカード番号をデータベースに保存
        $user->save();

        //GameRoomへの遷移
        if ($room->player_count >= 2) { //もし2人揃ったら
            $room->update(['status' => 'full']); //部屋のステータスを変更
            
            return redirect()->route('goGameRoom', ['room' => $room]); //gameroomに遷移・部屋番号を返す
        }

        return view('games.matching', ['room' => $room]); // 2人になるまで待機画面に移行
    }

    //ルームを作成して待機画面に移動by米田
    public function makeRoom(Room $room)
    {
        $room = Room::create([ // 新しい部屋を作成
            'status' => 'waiting'
        ]);
        return view('games.makeroom',['room' => $room]);
    }

    //部屋番号を入力するjoinroomに移動by米田
    public function joinMakeroom()
    {
        return view('games.joinroom');
    }

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

        return view('games.gameroom', ['room' => $room, 'user' => $user, 'choosed_Theme' => $choosed_Theme]);
    }

    
    

    //結果画面
    public function showResult(){
        // みんなのカード番号を取得
        $usersCardNumbers = User::pluck('card_number')->toArray();
        sort($usersCardNumbers);

        return view('games.result', compact('usersCardNumbers'));
    }

    // 部屋の状態を確認するAPI
    public function checkRoomStatus($roomId)
    {
        $room = Room::find($roomId);

        // 参加者が2人以上いるかどうかを確認
        $isFull = $room->participants()->count() == 2;

        return response()->json(['isFull' => $isFull]);
    }

    // 部屋に参加しているユーザーと人数を確認by米田
    public function checkJoinUser($roomId)
    {
        $room = Room::find($roomId);

        $isFull = $room->participants()->count() == 2;

        $joiningUserId = $room->participants();

        return response()->json(['isFull' => $isFull, 'joiningUserId' => $joiningUserId]);
    }
}

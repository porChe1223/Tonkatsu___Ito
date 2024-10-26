<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //結果画面
    // public function showResult($room_id, Request $request)
    // {
    //     $room = Room::findOrFail($room_id); //みんなのカード番号とそのユーザー情報を取得

    //     $room->update(['status' => 'finish']); //部屋のステータスを変更

    //     $participants = $room->participants->sortBy('card_number'); //Roomモデル内のparticipantsを使用して参加者の一覧を取得

    //     // Host側ではフォームからの入力を取得し、Guest側では空配列をデフォルトとする
    //     $player_order = $request->input('answer',[]); //プレイヤーの順番（送信された順番）

    //     // Hostの場合、`player_order`をデータベースに保存
    //     if (!empty($player_order)) {
    //         $room->update(['player_order' => json_encode($player_order)]);
    //     }

    //     $correct_order = $participants->pluck('name')->toArray(); //正しい順番（カード番号順で並べたプレイヤー名）

    //     // Host側のみで順番を判定
    //     $isCorrect = count($player_order) > 0 ? $player_order === $correct_order : null;

    //     return view(
    //         'games.result_host',
    //         [
    //             'isCorrect' => $isCorrect,
    //             'correct_order' => $correct_order,
    //             'player_order' => $player_order,
    //         ],
    //         compact('room', 'participants')
    //     );
    // }

    // 結果画面（Host / Guest 両方に対応）
    public function showResult($room_id, Request $request, $isHost = false)
    {
        $room = Room::findOrFail($room_id); // ルームを取得
        $participants = $room->participants->sortBy('card_number'); // 参加者の一覧を取得

        // Hostの場合のみ、フォーム入力からの順番を取得して保存
        $player_order = $isHost ? $request->input('answer', []) : json_decode($room->player_order, true);
        if ($isHost && !empty($player_order)) {
            $room->player_order = json_encode($player_order); // 直接代入
            $room->status = 'finish';
            $room->save(); // 保存
        }

        $correct_order = $participants->pluck('name')->toArray(); // 正しい順番
        $isCorrect = $isHost && count($player_order) > 0 ? $player_order === $correct_order : null;

        // 適切なビューを選択
        $view = $isHost ? 'games.result_host' : 'games.result_guest';

        return view($view, [
            'isCorrect' => $isCorrect,
            'correct_order' => $correct_order,
            'player_order' => $player_order,
            'room' => $room,
            'participants' => $participants,
        ]);
    }

    // Host用の結果画面
    public function showResultHost($room_id, Request $request)
    {
        return $this->showResult($room_id, $request, true);
    }

    // Guest用の結果画面
    public function showResultGuest($room_id)
    {
        return $this->showResult($room_id, new Request(), false);
    }


    public function removeResultRoom(Room $room)
    {
        $yourRoomUser = RoomUser::where('user_id', Auth::id())->first(); //自身が登録されているroom_userを取得

        if ($yourRoomUser) {
            $room = Room::find($yourRoomUser->room_id); // 自身が今入っているroomを取得

            $yourRoomUser->delete(); // 自身が登録されているroom_userを削除

            $room->player_count -= 1; // 部屋のプレイヤーを減らす
            $room->save(); // DBに保存

            // プレイヤーが0人になったら部屋を削除
            if ($room->player_count <= 0) {
                $room->delete();
            }
        }

        return view('games.home', ['room' => $room])->with('message', 'ゲームが終了しました');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ResultController extends Controller
{
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


    public function removeResultRoomHost(Room $room)
    {
        // 自身が登録されているroom_userを取得
        $yourRoomUser = RoomUser::where('user_id', Auth::id())->first();

        // room_userが見つからない、またはroomが見つからない場合はすぐにgoHomeRoomにリダイレクト
        if (!$yourRoomUser || !$room = Room::find($yourRoomUser->room_id)) {
            return redirect()->route('goHomeRoom')->with('message', '部屋が見つかりませんでした');
        }

        // 自身が登録されているroom_userを削除
        $yourRoomUser->delete();

        // 部屋のプレイヤーを減らす
        $room->player_count -= 1;
        $room->save();

        // プレイヤーが0人になったら部屋を削除
        if ($room->player_count <= 0) {
            $room->delete();
        }

        return redirect()->route('goHomeRoom')->with('message', 'ゲームが終了しました');
    }

    public function removeResultRoomGuest()
    {
        return view('games.home')->with('message', 'ゲームが終了しました');
    }

    // マッチングルームの状態を確認するAPI
    public function checkResultStatus($roomId)
    {
        $room = Room::find($roomId);
        $isContinue = $room->status == "continue"; // コンテニューかチェック

        return response()->json(['isContinue' => $isContinue]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Room;
use Auth;

class ChatController extends Controller
{
    // チャットルームの表示
    public function index($roomId)
    {
        $room = Room::findOrFail($roomId);
        return view('chat.index', compact('room'));
    }

    // メッセージの取得
    public function fetchMessages(Request $request, $roomId)
    {
        $lastFetched = $request->input('lastFetched');

        $query = Message::where('room_id', $roomId)->with('user')->orderBy('created_at', 'asc');

        if ($lastFetched) {
            $query->where('created_at', '>', $lastFetched);
        }

        $messages = $query->get();

        return response()->json($messages);
    }

    // メッセージの送信
    public function sendMessage(Request $request, $roomId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'room_id' => $roomId,
            'message' => $request->message,
        ]);

        return response()->json($message->load('user'));
    }
}

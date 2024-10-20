<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Theme;
use App\Models\Room;

class ThemeController extends Controller
{
    public function store(Request $request, $roomId)
    {
        //$contact = new Theme;
        //$contact->theme = $request->input('ThemeIdea');
        //$contact->save();

        $newTheme = new Theme;
        $newTheme->theme = $request->input('ThemeIdea');
        $newTheme->save();

        // 新しいテーマを部屋に適用
        $room = Room::find($roomId);
        $room->theme_id = $newTheme->id;
        $room->save();

        return redirect()->route('goGameRoom', ['room' => $roomId]);

        //return redirect()->route('goHomeRoom');
    }
}

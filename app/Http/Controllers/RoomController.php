<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('dashboard');
    }
}

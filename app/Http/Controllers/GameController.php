<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function choose_Theme_CardNumber(){
        //テーマをランダム選択
        $choosed_Theme = Theme::inRandomOrder()->first();

        $user = Auth::user();

        //カード番号をランダム選択
        $choosed_CardNumber = rand(0, 100);

        //選ばれたカード番号をデータベースに保存
        $user->card_number = $choosed_CardNumber;
        $user->save();

        return view('games.gameroom', compact('user', 'choosed_Theme'));
    }
}

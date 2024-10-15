<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function chooseTheme()
    {
        //テーマをランダム選択
        $choosedTheme = Theme::inRandomOrder()->first();
        return view('games.gameroom', compact('choosedTheme'));
    }

    public function chooseCareNumber()
    {
        //テーマをランダム選択
        $choosedCardNumber = rand(0, 100);
        return view('games.gameroom', compact('choosedCardNumber'));
    }
}

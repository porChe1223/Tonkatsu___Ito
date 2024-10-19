<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Theme;

class ThemeController extends Controller
{
    public function store(Request $request)
    {
        $contact = new Theme;
        $contact->theme = $request->input('ThemeIdea');
        $contact->save();

        return redirect()->route('dashboard');
    }
}

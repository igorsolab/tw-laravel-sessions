<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePage extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $user = $request->user();
        // $user = Auth::user();
        // $user = auth()->user();
        return view('home',[
            // 'user' => $user
        ]);
    }
}

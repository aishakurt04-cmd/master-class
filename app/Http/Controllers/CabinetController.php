<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CabinetController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $masterClasses = $user->masterClasses()
            ->with('craft', 'participants')
            ->orderBy('date', 'desc')
            ->get();

        return view('cabinet', compact('user', 'masterClasses'));
    }
}

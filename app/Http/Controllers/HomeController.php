<?php

namespace App\Http\Controllers;

use App\Models\Craft;
use App\Models\MasterClass;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View
    {
        $crafts = Craft::all();
        $userRegistrations = [];
        $hasRegistrations = false;

        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->isLeader()) {
                $userRegistrations = $user->registeredMasterClasses()
                                ->with('craft', 'leader')
                                ->where('date', '>=', now()->startOfDay())
                                ->orderBy('date')
                                ->get();
                $hasRegistrations = $userRegistrations->isNotEmpty();
            }
        }
        return view('home', compact('crafts', 'userRegistrations','hasRegistrations'));
    }
}

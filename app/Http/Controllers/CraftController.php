<?php

namespace App\Http\Controllers;

use App\Models\Craft;
use Illuminate\Contracts\View\View;

class CraftController extends Controller
{
    public function show(int $id): View
    {
        $craft = Craft::with('masterClasses.leader')->findOrFail($id);
        $masterClasses = $craft->masterClasses()
            ->with('leader')
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        $crafts = Craft::all();

        return view('category', compact('craft', 'masterClasses', 'crafts'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MasterClass;
use App\Models\Registration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function create(int $masterClassId): View
    {
        $masterClass = MasterClass::with('craft', 'leader')->findOrFail($masterClassId);
        $user = Auth::user();

        $already = Registration::where('user_id', $user->id)
            ->where('master_class_id', $masterClassId)
            ->exists();

        if ($already) {
            abort(403, 'Вы уже записаны на этот мастер-класс');
        }

        if (! $masterClass->hasFreePlaces()) {
            abort(403, 'Нет свободных мест');
        }

        return view('registration-confirm', compact('masterClass'));
    }

    public function store(Request $request, int $masterClassId): RedirectResponse
    {
        $masterClass = MasterClass::findOrFail($masterClassId);
        $user = Auth::user();

        $already = Registration::where('user_id', $user->id)
            ->where('master_class_id', $masterClassId)
            ->exists();

        if ($already) {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс');
        }

        if (! $masterClass->hasFreePlaces()) {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('error', 'Свободных мест нет');
        }

        if ($request->input('action') !== 'confirm') {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('info', 'Запись отменена');
        }

        Registration::create([
            'user_id' => $user->id,
            'master_class_id' => $masterClass->id,
            'status' => 'confirmed',
        ]);

        $masterClass->increment('current_participants');

        return redirect()->route('craft.show', $masterClass->craft_id)
            ->with('success', 'Вы успешно записаны на мастер-класс');
    }
}

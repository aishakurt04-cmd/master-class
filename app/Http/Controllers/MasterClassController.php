<?php

namespace App\Http\Controllers;

use App\Http\Requests\MasterClassStoreRequest;
use App\Models\Craft;
use App\Models\MasterClass;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterClassController extends Controller
{
    public function create(Request $request): View
    {
        $crafts = Craft::all();
        $timeSlots = [
            '09:00' => '9:00 - 11:00',
            '11:00' => '11:00 - 13:00',
            '13:00' => '13:00 - 15:00',
            '15:00' => '15:00 - 17:00',
        ];

        $user = Auth::user();
        $selectedDate = $request->get('date', date('Y-m-d'));

        // Проверяем занятость слотов на выбранную дату
        $occupiedSlots = MasterClass::where('leader_id', $user->id)
            ->where('date', $selectedDate)
            ->pluck('start_time')
            ->map(fn ($time) => $time->format('H:i'))
            ->toArray();

        // Проверяем, все ли слоты заняты на эту дату
        $allSlotsOccupied = ! empty($occupiedSlots) && count($occupiedSlots) === count($timeSlots);

        return view('master-class-form', compact('crafts', 'timeSlots', 'occupiedSlots', 'selectedDate', 'allSlotsOccupied'));
    }

    public function store(MasterClassStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $endTime = date('H:i:s', strtotime($validated['start_time'].' +2 hours'));

        MasterClass::create([
            'craft_id' => $validated['craft_id'],
            'leader_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $endTime,
            'max_participants' => $validated['max_participants'],
            'current_participants' => 0,
            'price' => $validated['price'],
        ]);

        return redirect()->route('cabinet')->with('success', 'Мастер-класс успешно создан');
    }

    // AJAX: получить доступные слоты для выбранной даты
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $date = $request->get('date');
        $user = Auth::user();

        $occupied = MasterClass::where('leader_id', $user->id)
            ->where('date', $date)
            ->pluck('start_time')
            ->map(fn ($time) => $time->format('H:i'))
            ->toArray();

        $allSlots = ['09:00', '11:00', '13:00', '15:00'];
        $available = array_diff($allSlots, $occupied);

        return response()->json([
            'available' => array_values($available),
            'allSlotsOccupied' => empty($available),
        ]);
    }

    public function edit(int $id): View
    {
        $user = Auth::user();
        $masterClass = MasterClass::where('leader_id', $user->id)->where('id', $id)->first();
        if (! $masterClass) {
            return redirect()->route('cabinet')->with('error', 'Мастер-класс не найден или принадлежит другому пользователю');
        }

        return view('master-class-edit', compact('masterClass'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = Auth::user();
        $masterClass = MasterClass::where('leader_id', $user->id)->where('id', $id)->first();
        if (! $masterClass) {
            return redirect()->route('cabinet')->with('error', 'Мастер-класс не найден или принадлежит другому пользователю');
        }
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
        $masterClass->update($validated);

        return redirect()->route('cabinet')->with('success', 'Мастер-класс обновлён');
    }
}

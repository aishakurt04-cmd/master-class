<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MasterClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class MasterClassStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->isLeader();
    }

    public function rules(): array
    {
        return [
            'craft_id'          => ['required', 'exists:crafts,id'],
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string', 'max:500'],
            'date'              => ['required', 'date', 'after_or_equal:today'],
            'start_time'        => ['required', 'in:09:00,11:00,13:00,15:00'],
            'max_participants'  => ['required', 'integer', 'min:1', 'max:20'],
            'price'             => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'craft_id.required'        => 'Выберите вид творчества.',
            'craft_id.exists'          => 'Выбранный вид творчества не существует.',
            
            'name.required'            => 'Введите название мастер-класса.',
            'name.max'                 => 'Название не должно превышать 255 символов.',
            
            'description.required'     => 'Введите описание мастер-класса.',
            'description.max'          => 'Описание не должно превышать 500 символов.',
            
            'date.required'            => 'Выберите дату мастер-класса.',
            'date.after_or_equal'      => 'Дата не может быть в прошлом.',
            
            'start_time.required'      => 'Выберите время мастер-класса.',
            'start_time.in'            => 'Выберите корректное время (9:00, 11:00, 13:00 или 15:00).',
            
            'max_participants.required' => 'Укажите количество мест.',
            'max_participants.min'      => 'Минимальное количество мест - 1.',
            'max_participants.max'      => 'Максимальное количество мест - 20.',
            
            'price.required'           => 'Укажите стоимость мастер-класса.',
            'price.numeric'            => 'Стоимость должна быть числом.',
            'price.min'                => 'Стоимость не может быть отрицательной.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            $date = $this->input('date');
            $startTime = $this->input('start_time');

            // Проверка, что время не занято
            $exists = MasterClass::where('leader_id', $user->id)
                ->where('date', $date)
                ->where('start_time', $startTime)
                ->exists();

            if ($exists) {
                $validator->errors()->add('start_time', 'Это время уже занято вами.');
            }
        });
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{11}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле ФИО обязательно для заполнения.',
            'name.max' => 'ФИО не должно превышать 255 символов.',

            'email.required' => 'Поле Email обязательно для заполнения.',
            'email.email' => 'Email должен быть действительным электронным адресом.',
            'email.max' => 'Email не должен превышать 255 символов.',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.',

            'password.required' => 'Поле Пароль обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать не менее 6 символов.',

            'phone.required' => 'Поле Телефон обязательно для заполнения.',
            'phone.regex' => 'Телефон должен состоять из 11 цифр (например: 88005553555).',

        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\LoginDTO;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|regex:/[A-Z][a-zA-Z]{6,}$/',
            'password' => 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+]).{8,}$/',
        ];
    }

    public function toDTO()
    {
        return new LoginDTO([
            'username' => $this->input('username'),
            'password' => $this->input('password'),
        ]);
    }

    public function messages()
    {
        return [
            'username.required' => 'Имя пользователя обязательно для заполнения',
            'username.string' => 'Имя пользователя должно быть строкой',
            'username.regex' => 'Имя пользователя должно начинаться с заглавной буквы и содержать только буквы латинского алфавита',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.string' => 'Пароль должен быть строкой',
            'password.min' => 'Пароль должен содержать не менее 8 символов',
            'password.regex' => 'Пароль должен содержать не менее 8 символов, как минимум одну цифру, одну заглавную букву, одну строчную букву и один специальный символ',
        ];
    }
}


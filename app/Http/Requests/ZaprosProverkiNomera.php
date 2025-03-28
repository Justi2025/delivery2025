<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZaprosProverkiNomera extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'phone' => ['required', 'regex:/^7\d{10}$/', 'exists:users'],
        ];
    }


    public function messages()
    {
        return [
            'phone.exists' => 'У нас нет клиента с таким номером телефона',
            'password.confirmed' => 'Пароли должны совпадать',
            'phone.regex' => 'Номер телефона должен быть в формате 7(000)000-00-00'
        ];
    }
}

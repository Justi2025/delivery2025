<?php
 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpravitKodProverkiNomeraZapros extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'regex:/^7\d{10}$/', 'unique:users'],
        ];
    }


    public function messages(): array
    {
        return [
            'phone.unique' => 'Данный телефон уже зарегистрирован',
            'password.confirmed' => 'Пароли должны быть одинаковыми'
        ];
    }
}

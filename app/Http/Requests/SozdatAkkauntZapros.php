<?php
 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SozdatAkkauntZapros extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|min:6',
            // 'email' => 'required|email|unique:users',
            'phone' => ['required', 'regex:/^(9|7)[0-9]{6}$/', 'unique:users'],
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Необходимо заполнить поле Почта'
        ];
    }
}

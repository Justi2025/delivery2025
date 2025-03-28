<?php
 

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZaprosIzmeneniaNomera extends FormRequest
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
            'phone' => ['required', 'regex:/^7[0-9]{10}$/', 'exists:users'],
            'code' => ['required', 'digits:6'],
            'password' => 'required|confirmed|min:6',
        ];
    }


    public function messages()
    {
        return [
            'phone.exists' => 'У нас нет клиента с таким номером телефона',
            'password.confirmed' => 'Пароли должны совпадать',
            'code.exists' => 'Вы ввели неправильный код',
        ];
    }
}

<?php
 

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ZaprosRegistratciiPolzovatelia extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|min:6',
            'city_id' => 'exists:cities,id',
            'year_of_birth' => 'digits:4',
            'street' => '',
            'house' => '',
            'flat' => '',
            'phone' => ['required', 'regex:/^7[0-9]{10}$/', 'unique:users'],
            'password' => 'required|confirmed|min:6',
            'code' => ['required', 'digits:6']
        ];
    }
}

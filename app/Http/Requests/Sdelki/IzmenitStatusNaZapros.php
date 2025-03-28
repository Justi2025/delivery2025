<?php
 

namespace App\Http\Requests\Sdelki;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IzmenitStatusNaZapros extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|int|exists:orders,id',
            'order_status' => 'required|int'
        ];
    }
}

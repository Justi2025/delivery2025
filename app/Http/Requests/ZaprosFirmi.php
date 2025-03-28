<?php
 

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ZaprosFirmi extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'string|min:3',
            'country' => 'string|in:ru,ab',
            'company_color' => '',
            'label_color' => '',
            'usage_frequency' => '',
            'pickup_only_paid' => '',
        ];
    }
}

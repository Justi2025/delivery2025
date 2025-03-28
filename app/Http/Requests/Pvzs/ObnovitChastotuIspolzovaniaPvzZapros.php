<?php
 

namespace App\Http\Requests\Pvzs;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ObnovitChastotuIspolzovaniaPvzZapros extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //'delivery_point_id' => 'exists:delivery_points,id',
            'usage_frequency' => 'required|int'
        ];
    }
}

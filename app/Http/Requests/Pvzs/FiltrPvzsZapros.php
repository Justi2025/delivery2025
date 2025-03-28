<?php
 

namespace App\Http\Requests\Pvzs;

use Illuminate\Foundation\Http\FormRequest;

class FiltrPvzsZapros extends FormRequest
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
            'country' => 'string|nullable'
        ];
    }
}

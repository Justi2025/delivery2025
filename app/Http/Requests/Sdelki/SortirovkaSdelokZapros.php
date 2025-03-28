<?php


namespace App\Http\Requests\Sdelki;

use Illuminate\Foundation\Http\FormRequest;

class SortirovkaSdelokZapros extends FormRequest
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
            'sort.customer' => 'sometimes|string|in:asc,desc',
            'sort.dp' => 'sometimes|string|in:asc,desc',
            'sort.created_at' => 'sometimes|string|in:asc,desc',
        ];
    }
}

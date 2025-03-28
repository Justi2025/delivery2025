<?php


namespace App\Http\Requests\Sotrudniki;

use App\Khranilischa\RoliPolzovatelei;
use Illuminate\Foundation\Http\FormRequest;

class ObnovitSotrudnikaZapros extends FormRequest
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
            //'user_id' => 'required|int|exists:users,id',
            'role' => 'sometimes|int|in:' . RoliPolzovatelei::casesAsString(),
            'office_id' => 'sometimes|int'
        ];
    }
}

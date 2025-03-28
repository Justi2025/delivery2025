<?php
 

namespace App\Http\Requests\Sdelki;

use App\Khranilischa\Sdelki\Perechislenia\TipMestonaznachenia;
use Illuminate\Foundation\Http\FormRequest;

class SozdatSdelkuSotrudnikomZapros extends FormRequest
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
            'client_id' => 'sometimes|int|exists:users,id',
            'from_id' => 'int|required|gt:0',
            'to_id' => 'nullable',
            'barcode_images.*' => 'nullable|file|mimes:jpg,jpeg,png',
            'barcode_text' => 'string|nullable',
            'destination_type' => 'required|int|in:' . TipMestonaznachenia::casesAsString(),
            'comment' => 'string|nullable|max:500',
            'pickup_only_paid' => '',
            'amount_to_pay_for_customer' => 'int'
        ];
    }


    public function messages(): array
    {
        return [
            'from_id.gt' => 'Неверный номер ПВЗ',
            'comment.max' => 'Только 500 символов'
        ];
    }
}

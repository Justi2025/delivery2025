<?php
 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SokranitFailZapros extends FormRequest
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
            'file' => 'required|file|mimes:jpg,jpeg,png,doc,docx,csv,xlsx,xls,txt,pdf,pptx',
            'dest' => '',
            'owner_id' => 'nullable|int'
        ];
    }
}

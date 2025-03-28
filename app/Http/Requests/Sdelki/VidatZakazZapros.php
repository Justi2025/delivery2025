<?php


namespace App\Http\Requests\Sdelki;

use Illuminate\Foundation\Http\FormRequest;

class VidatZakazZapros extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'int|required|exists:orders,id',
            'payment_type' => 'string|required|in:cash,cashless',
        ];
    }
}

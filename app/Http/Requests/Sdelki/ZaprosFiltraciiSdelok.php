<?php


namespace App\Http\Requests\Sdelki;

use Illuminate\Foundation\Http\FormRequest;

class ZaprosFiltraciiSdelok extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'filters.customer_id' => 'int',
            'filters.couriers.*' => 'int',
            'filters.order_statuses.*' => 'int',
            'filters.addresses_from.*' => 'int',
            'filters.addresses_to.*' => 'int',
            'filters.start_datetime' => 'nullable|numeric',
            'filters.end_datetime' => 'nullable|numeric',
            'filters.customer_cities.*' => 'int',
            'filters.customer_name' => 'string',
            'filters.order_id' => 'int',


            'sort.customer' => 'sometimes|string|in:asc,desc',
            'sort.dp' => 'sometimes|string|in:asc,desc',
            'sort.created_at' => 'sometimes|string|in:asc,desc',
            'sort.issued_at' => 'sometimes|string|in:asc,desc',
            'sort.order_status' => 'sometimes|string|in:asc,desc',
        ];
    }
}

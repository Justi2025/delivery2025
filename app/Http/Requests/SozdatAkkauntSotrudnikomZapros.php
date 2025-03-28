<?php
 

namespace App\Http\Requests;

use App\Common\Primesi\ToJson;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Psr\Http\Message\RequestInterface;

class SozdatAkkauntSotrudnikomZapros extends FormRequest
{
    use ToJson;

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
            'phone' => ['required', 'regex:/^7\d{10}$/', 'unique:users'],
            'password' => 'min:6',
            'full_name' => 'required|min:6',
            // 'firstname' => 'required|min:2',
            // 'lastname' => 'required|min:2',
            // 'middlename' => 'min:2',
            'passport_image' => 'nullable|image|mimes:jpg,jpeg,png',
            //'country' => '',
            'city_id' => 'required|exists:cities,id',
            'street' => '',
            'house' => '',
            'flat' => '',
            'year_of_birth' => 'nullable|int', // digits:4
            //'role' => '',
            //'status' => '',
            //'avatar' => '',
            //'bonuses_balance',
            //'creator_id',
            'is_vip' => 'bool',
            //'telegram_chat_id',
            //'phone_verified_at',

        ];
    }

}

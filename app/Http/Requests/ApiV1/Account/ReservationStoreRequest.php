<?php

namespace App\Http\Requests\ApiV1\Account;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'route_id' => 'required|uuid|exists:routes,id',
            'number_of_seats' => 'required|min:1|max:10'
        ];
    }
}

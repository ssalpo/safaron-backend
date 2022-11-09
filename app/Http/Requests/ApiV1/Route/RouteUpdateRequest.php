<?php

namespace App\Http\Requests\ApiV1\Route;

use App\Rules\IsUserCar;
use Illuminate\Foundation\Http\FormRequest;

class RouteUpdateRequest extends FormRequest
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
            'go_time' => 'required|date|date_format:Y-m-d H:i',
            'car_id' => ['required', 'uuid', new IsUserCar],
            'free_places' => 'required|numeric|min:1|max:10',
            'fast_reservation' => 'required|boolean',
            'baggage_transportation' => 'required|boolean',
            'description' => 'nullable|string',
        ];
    }
}

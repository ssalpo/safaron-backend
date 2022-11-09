<?php

namespace App\Http\Requests\ApiV1\Route;

use App\Rules\IsUserCar;
use Illuminate\Foundation\Http\FormRequest;

class RouteStoreRequest extends FormRequest
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
            'locations' => 'required|array',
            'locations.*.from_place_id' => 'required|uuid|exists:places,id',
            'locations.*.to_place_id' => 'required|uuid|exists:places,id',
            'go_time' => 'required|date|date_format:Y-m-d H:i',
            'car_id' => ['required', 'uuid', new IsUserCar],
            'fast_reservation' => 'required|boolean',
            'baggage_transportation' => 'required|boolean',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ];
    }
}

<?php

namespace App\Http\Requests\ApiV1\Account\Route;

use App\Models\Route;
use Illuminate\Foundation\Http\FormRequest;

class RouteCancelRequest extends FormRequest
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
            'cancel_reason' => 'required|numeric|in:' . implode(',', array_keys(Route::CANCEL_REASONS)),
            'cancel_description' => 'required_if:cancel_reason,5',
        ];
    }
}

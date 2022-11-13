<?php

namespace App\Http\Requests\ApiV1\Account;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'nullable',
            'photo' => 'nullable|mimes:jpeg,jpg|max:1024',
            'birthday' => 'nullable|date_format:Y-m-d',
            'gender' => 'nullable|numeric|in:1,2',
        ];
    }
}

<?php

namespace App\Http\Requests\ApiV1\Account;

use Illuminate\Foundation\Http\FormRequest;

class ConversationStoreRequest extends FormRequest
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
            'message' => 'required|min:1|max:300',
            'receiver_id' => 'required|uuid|exists:users,id'
        ];
    }
}

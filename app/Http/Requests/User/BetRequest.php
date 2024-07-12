<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class BetRequest extends FormRequest
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
            'amount' => 'required|numeric|gt:0|lt:1000000000',
            'is_demo' => 'required|integer|in:0,1',
            'bet_type' => 'required|integer|in:0,1',
        ];
    }
}

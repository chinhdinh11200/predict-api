<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingAdminRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'trade_min' => ['required', 'numeric'],
            'withdraw_fee' => ['required', 'numeric'],
            'start_golden_hour' => ['nullable', ''],
            'end_golden_hour' => ['nullable', ''],
        ];
    }
}

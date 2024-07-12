<?php

namespace App\Http\Requests\Admin\Auth;

use App\Rules\UserUnique;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name' => ['required', 'string', 'max:' . config('validate.max_length.name')],
            'email' => ['required', 'string', 'email', 'max:' . config('validate.max_length.email'), new UserUnique($userId)],
        ];
    }
}

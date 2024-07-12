<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use App\Rules\UserUnique;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $status = [User::STATUS_INACTIVE, User::STATUS_ACTIVE];
        $userId = $this->route('user');
        $nameMaxLength = config('validate.max_length.name');

        return [
            'name' => ['required', 'string', 'max:' . $nameMaxLength],
            'email' => ['required', 'string', 'email', 'max:' . config('validate.max_length.email'), new UserUnique($userId)],
            'status' => ['required', 'in:' . implode(',', $status)],
        ];
    }
}

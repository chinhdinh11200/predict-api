<?php

namespace App\Services\Admin;

use App\Exceptions\InputException;
use App\Models\Admin;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class AuthService extends Service
{
    /**
     * Login
     *
     * @param array $data
     * @return array|null
     */
    public function login(array $data): ?array
    {
        $admin = Admin::query()->where('email', $data['email'])->first();
        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return null;
        }//end if

        $token = $admin->createToken('authAdminToken')->plainTextToken;

        return [
            'access_token' => $token,
            'type_token' => 'Bearer',
        ];
    }

    /**
     * Update profile
     *
     * @param $data
     * @return int
     * @throws InputException
     */
    public function update($data): int
    {
        $admin = $this->user;
        if (!$admin) {
            throw new InputException(trans('response.not_found'));
        }//end if

        if ($admin->status == Admin::STATUS_INACTIVE) {
            throw new InputException(trans('response.invalid'));
        }//end if

        return Admin::query()
            ->where('id', '=', $admin->id)
            ->update($data);
    }

    /**
     * Change Password
     *
     * @param array $data
     * @return bool
     * @throws InputException
     */
    public function changePassword(array $data): bool
    {
        $admin = $this->user;
        if (!Hash::check($data['current_password'], $admin->password)) {
            throw new InputException(trans('auth.password'));
        }//end if

        $admin->update([
            'password' => Hash::make($data['password'])
        ]);

        return true;
    }
}

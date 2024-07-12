<?php

namespace App\Services\User;

use App\Exceptions\InputException;
use App\Helpers\StringHelper;
use App\Jobs\ForgotPasswordJob;
use App\Jobs\RegisterUserJob;
use App\Models\User;
use App\Models\UserForgotPassword;
use App\Models\UserRelationship;
use App\Models\UserSetting;
use App\Models\UserVerify;
use App\Services\Common\FileService;
use App\Services\Service;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthService extends Service
{
    /**
     * Login
     *
     * @param array $data
     * @return array
     * @throws InputException
     */
    public function login(array $data)
    {
        $user = User::query()
            ->where('email', '=', $data['email'])
            ->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new InputException(trans('auth.failed'));
        }//end if

        switch ($user->status) {
            case User::STATUS_INACTIVE:
                throw new InputException(trans('auth.login_status.inactive'));
            case User::STATUS_LOCK:
                throw new InputException(trans('auth.login_status.lock'));
        }//end switch

        $token = $user->createToken('authUserToken', ['*'], now()->addMinutes(config('user.expired_session_time')))->plainTextToken;

        return [
            'access_token' => $token,
            'type_token' => 'Bearer',
        ];
    }

    /**
     * Register
     *
     * @param array $data
     * @return bool
     */
    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            $refCode = StringHelper::uniqueCode();
            $newUser = User::query()->create([
                'username' => $data['username'],
                'email' => Str::lower($data['email']),
                'password' => Hash::make($data['password']),
                'status' => User::STATUS_INACTIVE,
                'refcode' => Str::upper($refCode),
            ]);

            if (!$newUser) {
                throw new InputException(trans('auth.register_fail'));
            }//end if

            $parentUser = User::query()->where('refcode', $data['refcode'])->first();
            if ($data['refcode'] && !$parentUser) {
                throw new InputException(trans('auth.register_fail'));
            }//end if

            $parent = $data['refcode'] ? UserRelationship::query()->where('user_id', $parentUser->id)->first() : null;
            UserRelationship::create(['user_id' => $newUser->id], $parent);

            // setting
            UserSetting::query()->create([
                'user_id' => $newUser->id,
                'is_revert' => UserSetting::NONE_REVERT,
                'is_marketing' => UserSetting::NONE_MARKETING,
                'is_lock_transaction' => UserSetting::NONE_LOCK_TRANSACTION,
            ]);

            $verifyToken = StringHelper::makeToken();
            UserVerify::query()->create([
                'user_id' => $newUser->id,
                'token' => $verifyToken,
            ]);

            dispatch(new RegisterUserJob($data['email'], $data['username'], $verifyToken, App::getLocale()))->onQueue(config('queue.job_name.send_register_mail'));
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * Register verify
     *
     * @param $data
     * @return bool
     */
    public function registerVerify($data): bool
    {
        $email = $data['email'];
        $check = UserVerify::query()
            ->where('token', $data['token'])
            ->whereHas('user', function ($query) use ($email) {
                return $query->where('email', $email);
            })
            ->first();
        if (!$check) {
            throw new \Exception(trans('response.verify.register_verified'));
            return false;
        }//end if

        DB::beginTransaction();
        try {
            User::query()->where('id', $check->user_id)->update(['status' => User::STATUS_ACTIVE]);
            $check->delete();
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }
    }

    /**
     * Update profile
     *
     * @param $data
     * @return int
     * @throws InputException
     */
    public function update($data)
    {
        $user = $this->user;
        if (!$user) {
            throw new InputException(trans('response.not_found'));
        }//end if

        $status = [User::STATUS_INACTIVE, User::STATUS_LOCK];
        if (in_array($user->status, $status)) {
            throw new InputException(trans('response.invalid'));
        }//end if

        return User::query()
            ->where('id', '=', $user->id)
            ->update($data);
    }

    /**
     * Change Password
     *
     * @param array $data
     * @return bool
     * @throws InputException
     */
    public function changePassword(array $data)
    {
        $user = $this->user;

        if (!Hash::check($data['current_password'], $user->password)) {
            throw new InputException(trans('auth.password'));
        }//end if

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return true;
    }

    /**
     * Send forgot password request
     *
     * @param $data
     * @return bool
     * @throws InputException
     */
    public function sendForgotPasswordRequest($data): bool
    {
        $user = User::query()
            ->where('email', $data['email'])
            ->where('status', User::STATUS_ACTIVE)
            ->first();
        if (!$user) {
            throw new InputException(trans('auth.forgot_password.fail'));
        }//end if

        DB::beginTransaction();
        try {
            $verifyToken = StringHelper::makeToken();
            UserForgotPassword::query()->updateOrCreate([
                'email' => $data['email'],
            ],[
                'token' => $verifyToken,
            ]);

            dispatch(new ForgotPasswordJob($data['email'], $user->username, $verifyToken, App::getLocale()))->onQueue(config('queue.job_name.send_register_mail'));
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * Verify forgot password request
     *
     * @param $data
     * @return bool
     */
    public function verifyForgotPasswordRequest($data): bool
    {
        $requestForgotPassword = UserForgotPassword::query()
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();
        if (!$requestForgotPassword) {
            throw new \Exception(trans('response.verify.forgot_password_verified'));
            return false;
        }//end if

        DB::beginTransaction();
        try {
            User::query()
                ->where('email', $data['email'])
                ->update(['password' => Hash::make($data['password'])]);
            $requestForgotPassword->delete();
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * Reset virtual balance to 1000.
     *
     * @return true
     * @throws InputException
     */
    public function resetVirtualBalance(): bool
    {
        $update = User::query()
            ->where('id', $this->user->id)
            ->update([
                'virtual_balance' => config('user.virtual_balance.reset_value'),
            ]);

        if (!$update) {
            throw new InputException(trans('auth.virtual_balance.fail'));
        }//end if

        return true;
    }

    /**
     * Get my balance
     *
     * @return array
     */
    public function getMyBalance(): array
    {
        return [
            'real_balance' => $this->user->real_balance,
            'virtual_balance' => $this->user->virtual_balance,
            'usdt_balance' => $this->user->usdt_balance,
        ];
    }

    /**
     * Upload avatar
     *
     * @param $image
     * @param $type
     * @return bool
     */
    public function uploadAvatar($image, $type): bool
    {
        $user = $this->user;
        DB::beginTransaction();
        try {
            $imageUpload = FileService::getInstance()->uploadImage($image, $type);
            User::query()
                ->where('id', $user->id)
                ->update([
                    'avatar' => $imageUpload['fullPath'],
                ]);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }
}

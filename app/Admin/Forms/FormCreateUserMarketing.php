<?php

namespace App\Admin\Forms;

use App\Exceptions\InputException;
use App\Helpers\StringHelper;
use App\Models\User;
use App\Models\UserRelationship;
use App\Models\UserSetting;
use Dcat\Admin\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class FormCreateUserMarketing extends Form
{

    public function store(?array $data = null, $redirectTo = null)
    {
        DB::beginTransaction();
        try {
            $data = $data ?: $this->request->all();

            if ($response = $this->beforeStore($data)) {
                return $this->sendResponse($response);
            }

            $refCode = StringHelper::uniqueCode();
            $newUser = User::query()->create([
                'username' => $data['username'],
                'email' => Str::lower($data['email']),
                'password' => Hash::make($data['password']),
                'real_balance' => $data['real_balance'] ?? 0,
                'status' => User::STATUS_ACTIVE,
                'refcode' => Str::upper($refCode),
            ]);

            if (!$newUser) {
                throw new InputException(trans('admin.register_fail'));
            } //end if

            $parentUser = User::query()->where('refcode', $data['refcode'])->first();
            if ($data['refcode'] && !$parentUser) {
                throw new InputException(trans('admin.register_fail'));
            } //end if

            $parent = $data['refcode'] ? UserRelationship::query()->where('user_id', $parentUser->id)->first() : null;
            UserRelationship::create(['user_id' => $newUser->id], $parent);

            // setting
            UserSetting::query()->create([
                'user_id' => $newUser->id,
                'is_revert' => UserSetting::NONE_REVERT,
                'is_marketing' => UserSetting::MARKETING,
                'is_lock_transaction' => UserSetting::NONE_LOCK_TRANSACTION,
            ]);

            DB::commit();

            $url = $this->getRedirectUrl($newUser->id, $redirectTo);

            return $this->sendResponse(
                $this->response()
                    ->redirectIf($url !== false, $url)
                    ->success(trans('admin.save_succeeded'))
            );
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage(), [$e]);
            $response = $this->handleException($e);

            if ($response instanceof Response) {
                return $response;
            }

            return $this->sendResponse(
                $this->response()
                    ->error(trans('admin.save_failed'))
                    ->withExceptionIf($e->getMessage(), $e)
            );
        } //end try
    }
}

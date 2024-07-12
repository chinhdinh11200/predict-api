<?php

namespace App\Admin\Forms;

use App\Models\User;
use App\Models\UserSetting;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class UserSettingForm extends Form implements LazyRenderable
{
    use LazyWidget;

    public function handle(array $input)
    {
        return $this->success('保存成功');
    }

    public function default()
    {
        return [
            'username' => $this->payload['name'] ?? '',
            'is_marketing' => $this->payload['is_marketing'] ?? UserSetting::NONE_MARKETING,
            'is_revert' => $this->payload['is_revert'] ?? UserSetting::NONE_REVERT,
            'is_lock_transaction' => $this->payload['is_lock_transaction'] ?? UserSetting::NONE_LOCK_TRANSACTION,
        ];
    }

    public function form()
    {
        $this->resetButton(false);
        $this->action("/user/{$this->payload['key']}/setting");

        $this->text('username', trans('admin.users.setting.title_edit'))->disable(true);
        $this->select('is_marketing', trans('admin.users.setting.marketing_title'))->options([
            UserSetting::MARKETING => trans("admin.users.setting.status_marketing." . UserSetting::MARKETING),
            UserSetting::NONE_MARKETING => trans("admin.users.setting.status_marketing." . UserSetting::NONE_MARKETING),
        ]);
        $this->select('is_revert', trans('admin.users.setting.revert_title'))->options([
            UserSetting::REVERT => trans("admin.users.setting.status_revert." . UserSetting::REVERT),
            UserSetting::NONE_REVERT => trans("admin.users.setting.status_revert." . UserSetting::NONE_REVERT),
        ]);
        $this->select('is_lock_transaction', trans('admin.users.setting.transaction_title'))->options([
            UserSetting::LOCK_TRANSACTION => trans("admin.users.setting.status_transaction." . UserSetting::LOCK_TRANSACTION),
            UserSetting::NONE_LOCK_TRANSACTION => trans("admin.users.setting.status_transaction." . UserSetting::NONE_LOCK_TRANSACTION),
        ]);

    }
}

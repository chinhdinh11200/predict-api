<?php

namespace App\Admin\Forms;

use App\Models\User;
use App\Models\UserSetting;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class UserLevelForm extends Form implements LazyRenderable
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
            'level' => $this->payload['level'] ?? 0,
        ];
    }

    public function form()
    {
        $this->resetButton(false);
        $this->action("/user/{$this->payload['key']}/level");

        $this->text('username', trans('admin.users.setting.title_edit'))->disable(true);
        $this->select('level', trans('admin.users.level'))->required()->options([
            0,1,2,3,4,5,6,7
        ]);
    }
}

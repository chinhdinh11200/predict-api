<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\UserMarketing;
use App\Admin\Forms\FormCreateUserMarketing;
use App\Helpers\NumberHelper;
use App\Models\User;
use App\Rules\Password;
use App\Rules\UserUnique;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;

class UserMarketingController extends UserController
{
    public function title()
    {
        return trans('admin.user_marketing.title.list');
    }

    public function grid()
    {
        return Grid::make(new UserMarketing(['setting']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('username', trans('admin.users.username'));
            $grid->column('email', trans('admin.users.email'));
            $grid->column('fullname', trans('admin.users.fullname'));
            $grid->column('real_balance', trans('admin.users.real_balance'))->sortable()
                ->display(fn ($realBalance) => NumberHelper::admin_number_format_no_zero(floatval($realBalance)))
                ->editable([]);

            $grid->quickSearch('username', 'fullname', 'email');                
            $grid->disableDeleteButton();
        });
    }

    public function create(Content $content) 
    {
        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['create'] ?? trans('admin.create'))
            ->body($this->formCreate());
    }

    public function store()
    {
        return $this->formCreate()->store();
    }

    public function update($id)
    {
        return $this->formRealBalance()->update($id);
    }

    public function edit($id, Content $content)
    {
        return $content
            ->body($this->formRealBalance()->edit($id));
    }

    public function formRealBalance()
    {
        return Form::make(new User(), function (Form $form) {
            $form->number('real_balance')->rules(['bail', 'required', 'numeric', 'gt:0']);

            $form->disableDeleteButton();
        });
    }    

    public function formCreate()
    {
        return FormCreateUserMarketing::make(new User(), function (Form $form) {
            $form->text('username', trans('admin.users.username'))->rules(['required', 'string', 'max:' . config('validate.max_length.name'), new UserUnique()]);
            $form->text('email', trans('admin.users.email'))->rules(['required', 'string', 'email', 'max:' . config('validate.max_length.email'), new UserUnique()]);
            $form->password('password', trans('admin.users.password'))->rules(['required', new Password(), 'confirmed']);
            $form->password('password_confirmation', trans('admin.users.password_confirmation'));
            $form->text('real_balance', trans('admin.users.real_balance'))->rules(['nullable', 'numeric', 'gt:0']);
            $form->text('refcode', trans('admin.users.refcode'))->rules(['nullable', 'string', 'max:' . config('validate.max_length.refcode'), 'exists:users,refcode']);
        });
    }
}

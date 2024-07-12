<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\PoolForm;
use App\Admin\Repositories\Pool;
use Dcat\Admin\Form;
use Dcat\Admin\Form\Footer;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;

class PoolController extends AdminController
{
    protected $type;

    public function edit($id, Content $content)
    {
        $this->type = request()->get('is_add');

        return $content
            ->title($this->type == PoolForm::DEPOSIT ? trans('admin.pool.deposit') : trans('admin.pool.withdraw'))
            ->body($this->form()->edit($id));
    }

    public function update($id)
    {
        return $this->form()->update($id, request()->all(), route('dcat.admin.dashboard'));
    }

    public function form()
    {
        return PoolForm::make(new Pool(), function (Form $form) {
            $form->text('value', trans('admin.pool.value'))->disable();
            $form->number('value_change', $this->type == PoolForm::DEPOSIT ? trans('admin.pool.value_deposit') : trans('admin.pool.value_withdraw'));
            $form->hidden('type')->value($this->type);

            $form->disableHeader();
            $form->footer(function (Footer $a) {
                $a->view('widgets.form-footer-with-back', ['backUrl' => route('dcat.admin.dashboard')]);
                $a->disableEditingCheck();
                $a->disableCreatingCheck();
                $a->disableViewCheck();
                $a->disableReset();
            });
        });
    }
}

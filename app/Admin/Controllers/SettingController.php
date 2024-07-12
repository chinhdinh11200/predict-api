<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Setting;
use Dcat\Admin\Form;
use Dcat\Admin\Show;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Models\Setting as ModelsSetting;

class SettingController extends AdminController
{

    public function title()
    {
        return trans('admin.settings.title.list');
    }

    public function grid()
    {
        return Grid::make(new Setting(), function (Grid $grid) {
            $grid->quickSearch('key');

            $grid->column('id')->sortable();
            $grid->column('key', trans('admin.settings.key'))->display(function ($key) {
                return trans("admin.settings.{$key}");
            });
            $grid->column('value', trans('admin.settings.value'))->sortable();
            $grid->column('type', trans('admin.settings.type'));

            $grid->disableCreateButton();
            $grid->disableDeleteButton();
        });
    }

    public function detail($id)
    {
        return Show::make($id, new Setting(), function (Show $show) {
            $show->field('key', trans('admin.settings.key'));
            $show->field('value', trans('admin.settings.value'));
            $show->field('type', trans('admin.settings.type'));
        });
    }

    public function form()
    {
        return Form::make(new Setting(), function (Form $form) {
            $id = $form->getKey();
            $form->text('key', trans('admin.settings.key'))->disable();
            if ($id) {
                $setting = ModelsSetting::query()->find($id);
                if ($setting && $setting->type == 'datetime') {
                    $form->datetime('value', trans('admin.settings.value'))->format('YYYY-MM-DD HH:mm:ss')->rules('date|date_format:Y-m-d H:i:s');
                } else {
                    $form->number('value', trans('admin.settings.value'))->rules('numeric');
                }
            }
            $form->text('type', trans('admin.settings.type'))->disable();

            $form->disableDeleteButton();
        });
    }
}

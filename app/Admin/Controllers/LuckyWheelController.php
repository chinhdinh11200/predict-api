<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\LuckyWheel;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class LuckyWheelController extends AdminController
{
    public function title()
    {
        return trans('admin.lucky_wheel.title.list');
    }

    public function grid()
    {
        return Grid::make(new LuckyWheel(), function (Grid $grid) {
            $grid->quickSearch('name_vi', 'name_en');

            $grid->column('id')->sortable();
            $grid->column('name_vi', trans('admin.lucky_wheel.name_vi'));
            $grid->column('name_en', trans('admin.lucky_wheel.name_en'));
            $grid->column('image_url', trans('admin.lucky_wheel.image_url'))->image('', 60, 60);
            $grid->column('slice_quantity', trans('admin.lucky_wheel.slice_quantity'))->sortable();
            $grid->column('prize_quantity', trans('admin.lucky_wheel.prize_quantity'))->sortable();
            $grid->column('winning_probability', trans('admin.lucky_wheel.winning_probability'))->sortable();
            $grid->column('reward', trans('admin.lucky_wheel.reward'))->sortable();
            $grid->column('spin_again', trans('admin.lucky_wheel.spin_again'));

            $grid->disableCreateButton();
            $grid->disableDeleteButton();
        });
    }

    public function detail($id)
    {
        return Show::make($id, new LuckyWheel(), function (Show $show) {
            $show->field('id');
            $show->field('name_vi', trans('admin.lucky_wheel.name_vi'));
            $show->field('name_en', trans('admin.lucky_wheel.name_en'));
            $show->field('image_url', trans('admin.lucky_wheel.image_url'))->image();
            $show->field('slice_quantity', trans('admin.lucky_wheel.slice_quantity'));
            $show->field('prize_quantity', trans('admin.lucky_wheel.prize_quantity'));
            $show->field('reward', trans('admin.lucky_wheel.reward'));

            $show->disableDeleteButton();
        });
    }

    public function form()
    {
        return Form::make(new LuckyWheel(), function (Form $form) {
            $id = $form->getKey();
            $form->text('name_vi', trans('admin.lucky_wheel.name_vi'));
            $form->text('name_en', trans('admin.lucky_wheel.name_en'));
            $form->image('image_url', trans('admin.lucky_wheel.image_url'))->url("upload")->autoUpload();

            $form->number('slice_quantity', trans('admin.lucky_wheel.slice_quantity'));
            $form->number('prize_quantity', trans('admin.lucky_wheel.prize_quantity'));
            $form->number('reward', trans('admin.lucky_wheel.reward'))->rules(['numeric', 'integer']);

            $form->disableDeleteButton();
        });
    }
}
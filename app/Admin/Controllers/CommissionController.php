<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Admin\Repositories\HistoryCommission;
use App\Helpers\NumberHelper;
use App\Models\HistoryCommission as ModelsHistoryCommission;

class CommissionController extends AdminController
{
    public function title()
    {
        return trans('admin.commission.title.list');
    }

    public function grid()
    {
        return Grid::make(new HistoryCommission(['user', 'fromUser']), function (Grid $grid) {
            $grid->quickSearch(['user.username', 'fromUser.username', 'note']);

            $grid->column('id')->sortable();
            $grid->column('user.username', trans('admin.commission.user_id'));
            $grid->column('from_user.username', trans('admin.commission.from_user_id'));
            $grid->column('value', trans('admin.commission.value'))->sortable()->display(function ($value) {
                return NumberHelper::admin_number_format_no_zero(floatval($value));
            });
            $grid->column('type', trans('admin.commission.type'))->display(function ($type) {
                return trans("admin.commission.type_lang.{$type}");
            });
            $grid->column('note', trans('admin.commission.note'));

            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();

                $filter->equal('type', trans('admin.commission.type'))->width(2)->select(trans('admin.commission.type_lang'));
            });
        });
    }

    public function detail($id)
    {
        return Show::make($id, new HistoryCommission(['user', 'fromUser']), function (Show $show) {
            $show->field('user.username', trans('admin.commission.user_id'));
            $show->field('from_user.username', trans('admin.commission.from_user_id'));
            $show->field('value', trans('admin.commission.value'))->as(function ($value) {
                return NumberHelper::admin_number_format_no_zero(floatval($value));
            });
            $show->field('type', trans('admin.commission.type'))->as(function ($type) {
                return trans("admin.commission.type_lang.{$type}");
            });
            $show->field('note', trans('admin.commission.note'));

            $show->disableEditButton();
            $show->disableDeleteButton();
        });
    }
}

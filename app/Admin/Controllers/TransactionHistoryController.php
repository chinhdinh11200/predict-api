<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Grid;
use App\Admin\Repositories\TransactionHistory;
use App\Helpers\NumberHelper;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class TransactionHistoryController extends AdminController
{
    public function title()
    {
        return trans('admin.transaction_history.title.list');
    }
    
    public function grid()
    {
        return Grid::make(new TransactionHistory(), function (Grid $grid) {
            $grid->column('created_at', trans('admin.transaction_history.created_at'))->display(fn ($time) => $time)->sortable();
            $grid->column('amount', trans('admin.transaction_history.amount'))->display(fn ($amount) => NumberHelper::admin_number_format_no_zero(floatval($amount)))->sortable();
            $grid->column('type', trans('admin.transaction_history.type'))->display(fn ($type) => trans("admin.transaction_history.type_lang.{$type}"));
            $grid->column('tx', trans('admin.transaction_history.tx'))->display(fn ($tx) => $tx ?? trans("admin.transaction_history.tx_lang.{$this->type}"));
            $grid->column('status', trans('admin.transaction_history.status'))->display(fn ($status) => trans("admin.transaction_history.status_lang.{$status}"));

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $types = trans('admin.transaction_history.type_lang');
                $filter->equal('type', trans('admin.transaction_history.type'))->width(3)->select($types);
            });
            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
        });
    }

    public function detail($id)
    {
        return Show::make($id, new TransactionHistory(), function (Show $show) use (&$id) {
            $transaction = TransactionDetail::query()->where('id', $id)->first();
            $show->field('created_at', trans('admin.transaction_history.created_at'))->as(fn ($time) => Carbon::parse($time)->format(config('date.fe_date_time_full_format')));
            $show->field('amount', trans('admin.transaction_history.amount'))->as(fn ($amount) => NumberHelper::admin_number_format_no_zero(floatval($amount)));
            $show->field('type', trans('admin.transaction_history.type'))->as(fn ($type) => trans("admin.transaction_history.type_lang.{$type}"));
            $show->field('tx', trans('admin.transaction_history.tx'))->as(fn ($tx) => $tx ?? trans("admin.transaction_history.tx_lang.{$transaction->type}"));
            $show->field('status', trans('admin.transaction_history.status'))->as(fn ($status) => trans("admin.transaction_history.status_lang.{$status}"));

            $show->disableDeleteButton();
            $show->disableEditButton();
        });
    }
}

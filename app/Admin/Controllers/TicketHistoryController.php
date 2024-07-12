<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\TicketHistory;
use Dcat\Admin\Form;
use Dcat\Admin\Show;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Models\User;

class TicketHistoryController extends AdminController
{

    public function title()
    {
        return trans('admin.ticket_history.title.list');
    }

    public function grid()
    {
        return Grid::make(new TicketHistory(['user']), function (Grid $grid) {
            $grid->quickSearch('user.username');

            $grid->column('id')->sortable();
            $grid->column('user.username', trans('admin.ticket_history.user_id'));
            $grid->column('quantity', trans('admin.ticket_history.quantity'))->sortable();
            $grid->column('prize', trans('admin.ticket_history.prize'));
            $grid->column('value', trans('admin.ticket_history.value'))->sortable();
            $grid->column('type', trans('admin.ticket_history.type'))->display(function ($type) {
                return trans("admin.ticket_history.type_lang.{$type}");
            });

            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();
        });
    }

    public function detail($id)
    {
        return Show::make($id, new TicketHistory(['user']), function (Show $show) {
            $show->field('user.username', trans('admin.ticket_history.user_id'));
            $show->field('quantity', trans('admin.ticket_history.quantity'));
            $show->field('prize', trans('admin.ticket_history.prize'));
            $show->field('value', trans('admin.ticket_history.value'));
            $show->field('type', trans('admin.ticket_history.type'))->as(function ($type) {
                return trans("admin.ticket_history.type_lang.{$type}");
            });

            $show->disableDeleteButton();
            $show->disableEditButton();
        });
    }

    public function form()
    {
        return Form::make(new TicketHistory(['user']), function (Form $form) {
            $users = User::query()->pluck('username', 'id')->toArray();
            $form->select('user_id', trans('admin.ticket_history.user_id'))->options($users);
            $form->number('quantity', trans('admin.ticket_history.quantity'))->rules('numeric|integer|min:0');
            $form->text('prize', trans('admin.ticket_history.prize'));
            $form->text('value', trans('admin.ticket_history.value'));
            $form->select('type', trans('admin.ticket_history.type'))->options(trans('admin.ticket_history.type_lang'));
        });
    }
}
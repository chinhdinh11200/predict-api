<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\HistoryCommandBet;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class HistoryCommandBetController extends AdminController
{
    public function title()
    {
        return trans('admin.command.title.list');
    }

    public function grid()
    {
        return Grid::make(new HistoryCommandBet(), function(Grid $grid) {
            $grid->column('id')->sortable();
            // $grid->column('session_id', trans('admin.command.session_id'));
            $grid->column('type', trans('admin.command.type'))->display(fn($type) => trans("admin.command.type_lang.{$type}"));
            $grid->column('type_target', trans('admin.command.type_target'))->display(fn($typeTarget) => $typeTarget !== null ? trans("admin.command.type_target_lang.{$typeTarget}") : "");
            
            $grid->disableActions(true);
        });
    }
}
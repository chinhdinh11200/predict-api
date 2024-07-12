<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Bet;
use App\Helpers\NumberHelper;
use App\Models\User;
use App\Models\UserSetting;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class BetController extends AdminController
{
    public function title()
    {
        return trans('admin.bet.title.list');
    }

    public function grid()
    {
        return Grid::make(new Bet(['user', 'user.setting']), function (Grid $grid) {
            $grid->quickSearch('user.username');
            $grid->column('user.username', trans('admin.bet.user_id'))
            ->if(function () {
                return $this->user && $this->user->setting && $this->user->setting->is_marketing == UserSetting::MARKETING ? true : false;
            })
            ->label('success');
            $grid->column('amount', trans('admin.bet.amount'))->display(fn ($amount) => NumberHelper::admin_number_format_no_zero($amount));
            $grid->column('reward', trans('admin.bet.reward'))->display(fn ($reward) => NumberHelper::admin_number_format_no_zero($reward));
            $grid->column('bet_type', trans('admin.bet.bet_type'))->display(fn ($betType) => trans("admin.bet.result_lang.{$betType}"));
            $grid->column('result', trans('admin.bet.result'))->display(fn ($result) => $result ? trans("admin.bet.result_lang.{$result}") : trans('admin.bet.is_result_lang.0'));
            $grid->column('is_demo', trans('admin.bet.is_demo'))->display(fn ($isDemo) => trans("admin.bet.is_demo_lang.{$isDemo}"));

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $users = User::query()->pluck('username', 'id')->toArray();
                $filter->equal('user.id', trans('admin.bet.filter.user'))->width(3)->select($users);
                $typeResult = trans('admin.bet.result_lang');
                $filter->equal('result', trans('admin.bet.filter.type_result'))->width(2)->select($typeResult);
                
                $typeUserBetSelect = trans('admin.bet.bet_type_lang');
                $filter->equal('bet_type', trans('admin.bet.filter.type_user_bet'))->width(2)->select($typeUserBetSelect);
                $typeBet = trans('admin.bet.is_demo_lang');
                $filter->equal('is_demo', trans('admin.bet.filter.type_bet'))->width(2)->select($typeBet);
                $filter->between('created_at', trans('admin.bet.filter.date_between'))->width(6)->date();
            });

            $grid->disableActions();
            $grid->disableCreateButton();
        });
    }
}

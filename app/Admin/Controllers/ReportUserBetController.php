<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ReportUserBet;
use App\Helpers\NumberHelper;
use App\Models\Bet;
use Carbon\Carbon;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;

class ReportUserBetController extends AdminController
{

    public function title()
    {
        return trans('admin.dashboard.title.list');
    }

    public function grid()
    {
        $reportUserBet = new ReportUserBet(['bets']);
        $dataRequest = request()->get('updated_at');
        $dateFrom = $dataRequest['start'] ?? date("2000-m-d");
        $dateTo = $dataRequest['end'] ?? null;
        $dateFrom = Carbon::parse($dateFrom)->startOfDay()->format('Y-m-d H:i:s');
        $dateTo = Carbon::parse($dateTo)->endOfDay()->format('Y-m-d H:i:s');
        $reportUserBet->setDateFrom($dateFrom);
        $reportUserBet->setDateTo($dateTo);
        return Grid::make($reportUserBet, function (Grid $grid) use ($reportUserBet) {
            $grid->wrap(function (Renderable $view) {
                $card = Card::make($view);
                $card->id('grid-report-user-bet');
                $card->noPadding();
                $card->class('shadow-none');

                return $card;
            });
            $grid->id;
            $grid->column('username', trans('admin.users.username'));
            $grid->column('email', trans('admin.users.email'));
            $grid->column('amount_win_div_lose', trans('admin.dashboard.amount_win_div_lose'))->display(fn () => $this->count_win . ' : ' . $this->count_lose);
            $grid->column('amount_win_sub_lose', trans('admin.dashboard.amount_win_sub_lose'))->display(fn ($amountWin) => NumberHelper::admin_number_format_no_zero(floatval($amountWin)));
            $grid->column('amount_win', trans('admin.dashboard.win'))->display(fn ($amountWin) => NumberHelper::admin_number_format_no_zero(floatval($amountWin)));
            $grid->column('amount_lose', trans('admin.dashboard.lose'))->display(fn ($amountLose) => NumberHelper::admin_number_format_no_zero(floatval($amountLose)));

            $grid->disableCreateButton();
            $grid->disableActions();

            $grid->filter(function (Grid\Filter $filter) use ($reportUserBet) {
                $filter->panel();
                $filter->whereBetween('updated_at', function ($query) use ($reportUserBet) {}, trans('admin.dashboard.title.filter_date'))->date();
            });
        });
    }
}

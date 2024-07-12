<?php

namespace App\Admin\Metrics\Dashboard;

use App\Models\Bet;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;
use App\Admin\Metrics\Dashboard;
use App\Helpers\NumberHelper;
use App\Models\HistoryCommission;
use App\Models\TicketHistory;
use Illuminate\Http\Request;

class TradeStats extends Donut
{
    public const ONE_DAY = 0;
    public const ONE_WEEK = 1;
    public const ONE_MONTH = 2;
    public const ONE_YEAR = 3;
    public const ALL = 4;

    protected $labels = ['Win', 'Lose'];
    protected $balanceContent;

    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $colors = [$color->primary(), $color->alpha('blue2', 0.5)];

        $this->title('Trade stats');
        $this->setLabels();
        $this->chartLabels($this->labels);
        $this->chartColors($colors);
        $this->dropdown(trans('admin.dashboard.trade'));
        $this->contentWidth(6, 6);
        $this->chartSmooth();
    }

    public function setLabels()
    {
        $this->labels = [trans('admin.dashboard.win_lose.' . Bet::WIN), trans('admin.dashboard.win_lose.' . Bet::LOSE)];
    }

    public function render()
    {
        $this->fill();

        return parent::render();
    }

    public function handle(Request $request)
    {
        $startTime = Carbon::now()->startOfDay();
        $endTime = Carbon::now()->endOfDay();
        switch ($request->get('option')) {
            case self::ONE_WEEK:
                $startTime = Carbon::now()->subWeek(1)->startOfDay();
                $endTime = Carbon::now()->endOfDay();
                break;
            case self::ONE_MONTH:
                $startTime = Carbon::now()->subMonth(1)->startOfDay();
                $endTime = Carbon::now()->endOfDay();
                break;
            case self::ONE_YEAR:
                $startTime = Carbon::now()->subYear(1)->startOfDay();
                $endTime = Carbon::now()->endOfDay();
                break;
            case self::ALL:
                $startTime = Carbon::now()->subYear(1)->startOfDay();
                $endTime = Carbon::now()->endOfDay();
                $totalTrade = Bet::query()
                    ->where('updated_at', '<=', $endTime)
                    ->where('is_demo', Bet::REAL_TYPE)
                    ->where('is_result', Bet::EXECUTED_RESULT)
                    ->count();
                $totalWinRound = Bet::query()
                    ->where('result', BET::WIN)
                    ->where('updated_at', '<=', $endTime)
                    ->where('is_demo', Bet::REAL_TYPE)
                    ->count();
                $totalLoseRound = Bet::query()
                    ->where('result', BET::LOSE)
                    ->where('updated_at', '<=', $endTime)
                    ->where('is_demo', Bet::REAL_TYPE)
                    ->count();
                $totalSell = Bet::query()
                    ->where('updated_at', '<=', $endTime)
                    ->where('bet_type', Bet::DOWN)
                    ->where('is_demo', Bet::REAL_TYPE)
                    ->count();
                $totalBuy = Bet::query()
                    ->where('updated_at', '<=', $endTime)
                    ->where('bet_type', Bet::UP)
                    ->where('is_demo', Bet::REAL_TYPE)
                    ->count();
                $netProfit = Bet::query()
                    ->where('is_demo', Bet::REAL_TYPE)
                    ->where('is_result', Bet::EXECUTED_RESULT)
                    ->sum('amount') - Bet::query()
                    ->where('is_demo', Bet::REAL_TYPE)
                    ->where('is_result', Bet::EXECUTED_RESULT)
                    ->where('result', Bet::ADD)
                    ->sum('reward') - Bet::query()->where('result', BET::LOSE)
                      ->where('is_demo', Bet::REAL_TYPE)
                      ->where('is_result', Bet::EXECUTED_RESULT)
                      ->where('result', Bet::ADD)
                      ->sum('amount');
                $ticketPrizeAmount = TicketHistory::query()
                    ->where('updated_at', '<=', $endTime)
                    ->where('type', TicketHistory::TYPE_USE)
                    ->sum('value');
                $commission = HistoryCommission::query()
                    ->where('updated_at', '<=', $endTime)
                    ->sum('value');
                $revenue = $ticketPrizeAmount + $commission;

                $this->withContent($totalTrade, $totalWinRound, $totalLoseRound);
                $this->withChart([$totalWinRound, $totalLoseRound]);
                $this->withBalanceContent($totalTrade, $totalSell, $totalBuy, $revenue, $netProfit);
                return;
                break;
            default:
                // self::ONE_DAY;
                $startTime = Carbon::now()->startOfDay();
                $endTime = Carbon::now()->endOfDay();
        }
        $totalTrade = Bet::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->count();
        $totalWinRound = Bet::query()
            ->where('result', BET::WIN)
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->count();
        $totalLoseRound = Bet::query()
            ->where('result', BET::LOSE)
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->count();
        $totalSell = Bet::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('bet_type', Bet::DOWN)
            ->count();
        $totalBuy = Bet::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('bet_type', Bet::UP)
            ->count();
        $netProfit = Bet::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->sum('amount') - Bet::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->where('result', Bet::ADD)
            ->sum('reward') - Bet::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->where('result', Bet::ADD)
            ->sum('amount');
        $ticketPrizeAmount = TicketHistory::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->where('type', TicketHistory::TYPE_USE)
            ->sum('value');
        $commission = HistoryCommission::query()
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->sum('value');
        $revenue = $ticketPrizeAmount + $commission;

        $this->withContent($totalTrade, $totalWinRound, $totalLoseRound);
        $this->withChart([$totalWinRound, $totalLoseRound]);
        $this->withBalanceContent($totalTrade, $totalSell, $totalBuy, $revenue, $netProfit);
    }

    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data
        ]);
    }

    public function renderContent()
    {
        $content = parent::renderContent();
        $content = str_replace('float:right;', 'transform:scale(1.8);', $content);
        return <<<HTML
            <div class="">
                <div>{$content}</div>
                <div class="mt-3">
                    {$this->balanceContent}
                </div>
            </div>
        HTML;
    }

    protected function withContent($totalTrade, $totalWinRound, $totalLoseRound)
    {
        $blue = Admin::color()->alpha('blue2', 0.5);
        $style = 'margin-bottom: 8px';
        $labelWidth = 140;
        $titleTrade = trans('admin.dashboard.title.trade');
        $titleWin = trans('admin.dashboard.title.win');
        $titleLose = trans('admin.dashboard.title.lose');
        $titleWinRate = trans('admin.dashboard.title.win_rate');
        $winRate = $this->calculateRate($totalWinRound, $totalTrade);

        return $this->content(
            <<<HTML
                <div class="d-flex pl-1 pr-1 pt-1" style="{$style}">
                    <div style="width: {$labelWidth}px; font-weight: 600">
                        {$titleTrade}
                    </div>
                    <div style="font-weight: 600; font-size:16px">{$totalTrade}</div>
                </div>
                <div class="d-flex pl-1 pr-1" style="{$style}">
                    <div style="width: {$labelWidth}px">
                        <i class="fa fa-circle text-primary"></i> {$titleWin}
                    </div>
                    <div>{$totalWinRound}</div>
                </div>
                <div class="d-flex pl-1 pr-1" style="{$style}">
                    <div style="width: {$labelWidth}px">
                        <i class="fa fa-circle" style="color: $blue"></i> {$titleLose}
                    </div>
                    <div>{$totalLoseRound}</div>
                </div>
                <div class="d-flex pl-1 pr-1" style="{$style}">
                    <div style="width: {$labelWidth}px; font-weight: 600">
                        {$titleWinRate}
                    </div>
                    <div style="font-weight: 600; font-size:16px">{$winRate} %</div>
                </div>
            HTML
        );
    }

    protected function withBalanceContent($totalTrade, $totalSell, $totalBuy, $revenue, $netProfit)
    {
        $titleProfit = trans('admin.dashboard.title.profit');
        $titleSell = trans('admin.dashboard.title.sell');
        $titleBuy = trans('admin.dashboard.title.buy');
        $titleRevenue = trans('admin.dashboard.title.revenue');
        $titleTradeSummary = trans('admin.dashboard.title.trade_summary');
        $sellRate = $this->calculateRate($totalSell, $totalTrade);
        $buyRate = $this->calculateRate($totalBuy, $totalTrade);
        $labelWidth = 140;
        $revenue = NumberHelper::admin_number_format_no_zero(floatval($revenue));
        $netProfit = NumberHelper::admin_number_format_no_zero(floatval($netProfit));

        $this->balanceContent = <<<HTML
            <div class="d-flex">
                <div class="col-sm-6">
                    <div>
                        <p class="mb-0" style="width: {$labelWidth}px; font-weight: 600">{$titleProfit}</p>
                        <p style="font-weight: 600; font-size: 24px">$ {$netProfit}</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div>
                        <p class="mb-0" style="width: {$labelWidth}px; font-weight: 600">{$titleRevenue}</p>
                        <p style="font-weight: 600; font-size: 24px">$ {$revenue}</p>
                    </div>
                </div>
            </div>
            <div class="d-flex pl-1 pr-1 mb-1">
                <div style="width: {$labelWidth}px; margin-bottom: 8px; font-weight: 600">
                    {$titleTradeSummary}
                </div>
                <!-- <div>{$sellRate} %</div> -->
                <div style="width: calc(100% - 160px); height: 18px; line-height: 18px" class="d-flex rounded overflow-hidden">
                    <div class="m-0 text-center" style="width: {$sellRate}%; font-weight: 700; background-color: #F14462">{$sellRate} % {$titleSell}</div>
                    <div class="m-0 text-center" style="width: {$buyRate}%; font-weight: 700; background-color: #01CE91">{$buyRate} % {$titleBuy}</div>
                </div>
            </div>
        HTML;
    }

    protected function calculateRate($divide, $total)
    {
        if ($total <= 0) return 0;

        return round($divide / $total * 100);
    }
}

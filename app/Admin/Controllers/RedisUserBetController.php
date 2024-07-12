<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\RedisUserBetBuy;
use App\Admin\Repositories\RedisUserBetSell;
use App\Helpers\NumberHelper;
use App\Models\HistoryCommandBet;
use App\Models\LastResult;
use App\Models\Setting;
use App\Models\UserSetting;
use App\Services\User\BetService;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class RedisUserBetController extends AdminController
{

    public function __construct()
    {
        RedisUserBetBuy::getAll();
        RedisUserBetSell::getAll();
    }

    public function title()
    {
        return trans('admin.users.title.user_bet');
    }

    public function index(Content $content)
    {
        $route = route('dcat.admin.user-bet.render-table');
        $typeBetText = trans('admin.users.bet_summary.type_session.bet');
        $typeResultText = trans('admin.users.bet_summary.type_session.result');
        $socketUrl = config('app.scan_url');
        Admin::js('https://cdn.socket.io/4.7.5/socket.io.min.js');
        Admin::script(
            <<<JS
                const socket = io('{$socketUrl}', {
                    auth: {
                        serverOffset: 0,
                    },
                    ackTimeout: 10000,
                    retries: 3,
                    transports: ["websocket"],
                    query: {
                        token: 1234,
                    }
                });

                socket.on('BIFIX_USER_BET', () => {
                    $.ajax({
                        url: '{$route}',
                        type: 'GET',
                        success: function(data) {
                            $('#userBetSell .card-body').html(data.grid_sell);
                            $('#userBetBuy .card-body').html(data.grid_buy);
                            $('#card-summary').parent().html(data.card);
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    })
                });
                socket.on('BIFIX_BET_END', () => {
                    $.ajax({
                        url: '{$route}?end=true',
                        type: 'GET',
                        success: function(data) {
                            $('#userBetSell .card-body').html(data.grid_sell);
                            $('#userBetBuy .card-body').html(data.grid_buy);
                            $('#card-summary').parent().html(data.card);
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    })
                });

                socket.on('BIFIX_MARKET_PRICE', (data) => {
                    if (data?.isBetSession) {
                        $('#time-session').text((data.end_time - data.event_time) / 1000)
                        $('#type-session').text('{$typeBetText}')
                    } else {
                        $('#time-session').text((data.end_time - data.event_time) / 1000)
                        $('#type-session').text('{$typeResultText}')
                    }
                });

                $(document).on('submit', '#store-regulation', function(e) {
                    e.preventDefault();
                    submitRegulation(e, socket);
                })
            JS
        );

        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->row($this->renderWidgetUserBet());
                });
                $row->column(6, function (Column $column) {
                    $column->row($this->gridBuy());
                });
                $row->column(6, function (Column $column) {
                    $column->row($this->gridSell());
                });
            });
    }

    public function gridBuy()
    {
        return Grid::make(new RedisUserBetBuy(['bets', 'setting']), function (Grid $grid) {
            $grid->wrap(function (Renderable $view) {
                $card = Card::make($view);
                $card->id('userBetBuy');

                return $card;
            });
            $grid->column('username', trans('admin.users.username'))
                ->if(function () {
                    return $this->setting && $this->setting->is_marketing == UserSetting::MARKETING;
                })
                ->label('success');
            $grid->column('email', trans('admin.users.email'));
            $grid->column('real_balance', trans('admin.users.real_balance'))->display(function ($realBalance) {
                return NumberHelper::admin_number_format_no_zero(floatval($realBalance));
            });
            $grid->column(trans('admin.users.bet_amount'))->display(function () {
                return NumberHelper::admin_number_format_no_zero(floatval(RedisUserBetBuy::getBetsWithType($this->id)));
            });
            $grid->disableActions(true);
            $grid->disableCreateButton();
        });
    }

    public function gridSell()
    {
        return Grid::make(new RedisUserBetSell(['bets', 'setting']), function (Grid $grid) {
            $grid->wrap(function (Renderable $view) {
                $card = Card::make($view);
                $card->id('userBetSell');

                return $card;
            });
            $grid->column('username', trans('admin.users.username'))
                ->if(function () {
                    return $this->setting && $this->setting->is_marketing == UserSetting::MARKETING;
                })
                ->label('success');
            $grid->column('email', trans('admin.users.email'));
            $grid->column('real_balance', trans('admin.users.real_balance'))->display(function ($realBalance) {
                return NumberHelper::admin_number_format_no_zero(floatval($realBalance));
            });
            $grid->column(trans('admin.users.bet_amount'))->display(function () {
                return NumberHelper::admin_number_format_no_zero(floatval(RedisUserBetSell::getBetsWithType($this->id)));
            });
            $grid->disableActions(true);
            $grid->disableCreateButton();
        });
    }

    public function renderTable()
    {
        RedisUserBetBuy::getAll();
        RedisUserBetSell::getAll();
        $gridSell = $this->gridSell()->render();
        $gridBuy = $this->gridBuy()->render();
        $card = $this->renderWidgetUserBetCard()->render();
        return [
            'card' => $card,
            'grid_sell' => $gridSell,
            'grid_buy' => $gridBuy,
        ];
    }

    public function renderWidgetUserBet()
    {
        $title = trans('admin.users.bet_summary.summary');
        $row = new Row();
        $cardBet = $this->renderWidgetUserBetCard();
        $cardTime = $this->renderWidgetTimeCard();
        $row->column(12, function (Column $column) use ($cardBet) {
            $column->row($cardBet);
        });
        $row->column(12, function (Column $column) use ($cardTime) {
            $column->row($cardTime);
        });
        $cardWrapper = Card::make(
            "<h3>{$title}</h3>",
            $row
        );
        $cardWrapper->noPadding();

        return $cardWrapper;
    }

    public function renderWidgetTimeCard()
    {
        $card = Card::make(view('widget-user-time'));
        $card->padding('0 0 15px 0');
        $card->class('shadow-none');

        return $card;
    }

    public function renderWidgetUserBetCard()
    {
        $dataBuy = RedisUserBetBuy::getTotalAmountInSession();
        $dataSell = RedisUserBetSell::getTotalAmountInSession();
        $buy = $dataBuy['buy'] ?? 0;
        $sell = $dataSell['sell'] ?? 0;
        $sessionId = $dataBuy['sessionId'] ?? null;
        $historyCommandBet = $dataBuy['historyCommandBet'] ?? null;
        $profitBetBuy = 0;
        $profitBetSell = 0;
        $profitRate = Setting::query()
            ->where('key', 'profit_bet')
            ->first();
        if ($profitRate) {
            $profitBetBuy = $buy * (1 + $profitRate->value / 100);
            $profitBetSell = $sell * (1 + $profitRate->value / 100);
        }

        $data = [
            'buy' => $buy,
            'sell' => $sell,
            'poolSell' => $buy + $sell - $profitBetSell,
            'poolBuy' => $buy + $sell - $profitBetBuy,
            'route' => route('dcat.admin.user-bet.regulation'),
        ];

        $card = Card::make(
            view('widget-user-bet', [
                'data' => $data,
                'historyCommandBet' => $historyCommandBet,
                'sessionId' => $sessionId,
            ])
        );
        $card->padding('0 0 15px 0');
        $card->id('card-summary');
        $card->class('shadow-none');

        return $card;
    }

    public function regulation(Request $request)
    {
        $validate = $request->validate([
            'session_id' => 'required',
            'type_target' => 'required|numeric|integer',
        ]);

        try {
            HistoryCommandBet::query()
                ->updateOrCreate([
                    'session_id' => $validate['session_id'] ?? null,
                ], [
                    'type' => HistoryCommandBet::COMMAND_REGULATION_TYPE,
                    'type_target' => $validate['type_target'],
                ]);

            return true;
        } catch (\Exception $e) {
            logger($e->getMessage(), [$e]);
            return false;
        }
    }
}

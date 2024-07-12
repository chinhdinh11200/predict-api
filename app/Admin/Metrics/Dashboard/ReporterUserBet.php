<?php

namespace App\Admin\Metrics\Dashboard;

use App\Admin\Repositories\ReportUserBet;
use App\Helpers\NumberHelper;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card as WidgetsCard;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ReporterUserBet extends Card
{
    public const ONE_DAY = 0;
    public const ONE_WEEK = 1;
    public const ONE_MONTH = 2;
    public const ALL = 3;
    public $filterId;

    protected function init()
    {
        parent::init();
        $this->filterId = 'filter-report-user-bet';
        $reportUserBet = new ReportUserBet(['bets']);
        $dateFrom = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $dateTo = Carbon::now()->format('Y-m-d H:i:s');
        $reportUserBet->setDateFrom($dateFrom);
        $reportUserBet->setDateTo($dateTo);
        $this->header($this->headerContent());
        $this->content($this->grid($reportUserBet));
    }

    public function handle(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateFilter($request->get('dateFrom'), $request->get('dateTo'));
        
        $reportUserBet = new ReportUserBet(['bets']);
        $reportUserBet->setDateFrom($dateFrom);
        $reportUserBet->setDateTo($dateTo);
        $this->content($this->grid($reportUserBet));
    }

    public function headerContent()
    {
        Admin::css(asset('vendor/dcat-admin/dcat/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css?v2.2.2-beta'));
        Admin::js(asset('vendor/dcat-admin/dcat/plugins/moment/moment-with-locales.min.js?v2.2.2-beta'));
        Admin::js(asset('vendor/dcat-admin/dcat/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js?v2.2.2-beta'));
        $dateFrom = Carbon::now()->startOfDay()->format('d-m-Y');
        $dateTo = Carbon::now()->format('d-m-Y');
        $initDropdown = self::ONE_DAY;
        $cardFilter = WidgetsCard::make(view('dashboard.report_user_bet_filter', [
            'id' => $this->filterId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'initDropdown' => $initDropdown]
        ));
        $cardFilter->class('shadow-none');

        return $cardFilter;
    }

    public function grid($reportUserBet)
    {
        $grid = Grid::make($reportUserBet, function (Grid $grid) {
            $grid->wrap(function (Renderable $view) {
                $card = WidgetsCard::make($view);
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
            $grid->disableFilter();
            $grid->disableFilterButton();
            $grid->disableRefreshButton();
        });

        $row = new Row();
        $row->column(12, function (Column $column) use ($grid) {
            $column->row($grid);
        });

        $cardWrapper = WidgetsCard::make($row);
        $cardWrapper->class('shadow-none');

        return $cardWrapper;
    }

    public function getDateFilter($dateFrom = null, $dateTo = null)
    {
        if (!$dateFrom || !$dateTo) {
            return [
                null, null
            ];
        }

        return [
            Carbon::parse($dateFrom)->startOfDay()->format('Y-m-d H:i:s'),
            Carbon::parse($dateTo)->endOfDay()->format('Y-m-d H:i:s'),
        ];
    }
    /**
     * @return mixed
     */
    public function addScript()
    {
        if (!$this->allowBuildRequest()) {
            return;
        }

        $id = $this->filterId;

        $this->fetching(
            <<<JS
            var \$card = $('#{$id}');
            \$card.loading();
            JS
        );

        $this->fetched(
            <<<'JS'
            $card.loading(false);   
            $card.find('.metric-header').html(response.header);
            $('#grid-report-user-bet').html(response.content);
            JS
        );

        $clickable = "#{$id} .btn-submit";

        if ($this->chart) {
            $this->chart->merge($this)->click($clickable);
        } else {
            // $cardRequestScript = $this->click($clickable)->buildRequestScript();
            $cardRequest = $this->click($clickable)->buildRequest();
        }
        return $this->script = <<<JS
                {$cardRequest}
                {$this->buildDropdownScript()}
            $('{$clickable}').on('click', function () {
                request({dateFrom: $('.time-start').val(), dateTo: $('.time-end').val()});
            });
            {$this->click($clickable)->buildRequestScript()}
        JS;
    }

    private function formatRequestData()
    {
        $data = [
            '_key'   => $this->getUriKey(),
            '_token' => csrf_token(),
        ];

        return json_encode(
            array_merge($this->parameters(), $data)
        );
    }

    public function buildRequestScript()
    {
        if (!$this->allowBuildRequest()) {
            return;
        }
        return <<<JS
            (function () {
                request({dateFrom: $('.time-start').val(), dateTo: $('.time-end').val()});
                {$this->buildBindingScript()}
            })();
        JS;
    }

    public function buildRequest()
    {
        if (!$this->allowBuildRequest()) {
            return;
        }

        $fetching = implode(';', $this->requestScripts['fetching']);
        $fetched = implode(';', $this->requestScripts['fetched']);
        return <<<JS
            var loading;
            function request(data) {
                if (loading) {
                    return;
                }
                loading = 1;
                data = $.extend({$this->formatRequestData()}, data || {});

                {$fetching};

                $.ajax({
                url: '{$this->getRequestUrl()}',
                dataType: 'json',
                method: '{$this->method}',
                data: data,
                success: function (response) {
                    loading = 0;
                    {$fetched};
                },
                error: function (a, b, c) {
                    loading = 0;
                    Dcat.handleAjaxError(a, b, c)
                },
                });
            }

            request({dateFrom: $('.time-start').val(), dateTo: $('.time-end').val()});

            {$this->buildBindingScript()}
        JS;
    }

    function buildDropdownScript()
    {
        $id = $this->filterId;
        $dropdownClickable = "#{$id} .dropdown .dropdown-item";

        return <<<JS
            $('{$dropdownClickable}').on('click', function () {
                var selected = $(this).find('.select-option').data('option');
                const oneDay = 0;
                const oneWeek = 1;
                const oneMonth = 2;
                var dateFrom;
                var dateTo;
                switch (selected) {
                    case oneDay:
                        dateFrom = moment(new Date()).subtract(1, 'days').format('DD-MM-YYYY');
                        dateTo = moment(new Date()).format('DD-MM-YYYY');
                        break;
                    case oneWeek:
                        dateFrom = moment(new Date()).subtract(7, 'days').format('DD-MM-YYYY');
                        dateTo = moment(new Date()).format('DD-MM-YYYY');
                        break;
                    case oneMonth:
                        dateFrom = moment(new Date()).subtract(1, 'months').format('DD-MM-YYYY');
                        dateTo = moment(new Date()).format('DD-MM-YYYY');
                        break;
                    default:
                        break;
                }
                $('.time-start').val(dateFrom).trigger('change');
                $('.time-end').val(dateTo).trigger('change');
                $(this).parents('.dropdown').find('.btn').html($(this).text());
                $('#{$id} .btn-submit').click();
            });
        JS;
    }
}

<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Examples;
use App\Http\Controllers\Controller;
use App\Admin\Metrics\Dashboard;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('Dashboard')
            ->body(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->row(new Dashboard\TradeStats());
                });
                $row->column(6, function (Column $column) {
                    $column->row(new Dashboard\PoolMoney());
                });
                $row->column(12, function (Column $column) {
                    // $column->row(new Dashboard\ReporterUserBet());
                });
            });
    }
}

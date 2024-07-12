<?php

namespace App\Admin\Metrics\Dashboard;

use App\Models\Pool;
use Dcat\Admin\Widgets\Card as WidgetsCard;
use Dcat\Admin\Widgets\Metrics\Card;

class PoolMoney extends Card
{
    protected $view = 'widgets.card-no-header'; 
    protected function init()
    {
        $this->content($this->card());
    }

    protected function card()
    {
        $pool = Pool::query()->first();
        $card = WidgetsCard::make('', view('dashboard.pool-money', ['pool' => $pool]));
        $card->padding('24px 15px 24px 15px');

        return $card;
    }
}

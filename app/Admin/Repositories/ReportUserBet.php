<?php

namespace App\Admin\Repositories;

use App\Models\Bet;
use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\User as UserModel;
use Dcat\Admin\Grid;
use Illuminate\Support\Facades\DB;

class ReportUserBet extends EloquentRepository
{

    protected $eloquentClass = UserModel::class;
    protected $dateFrom;
    protected $dateTo;

    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }

    public function get(Grid\Model $model)
    {
        $this->setSort($model);
        $this->setPaginate($model);

        $query = $this->newQuery();

        if ($this->relations) {
            $query->with($this->relations);
        }
        $query->withSum([
            'bets AS amount_win' => function ($queryBet) {
                $queryBet->where('result', Bet::WIN)
                    ->where('is_demo', Bet::REAL_TYPE);
                if ($this->dateFrom && $this->dateTo) {
                    $queryBet->whereBetween('updated_at', [$this->dateFrom, $this->dateTo]);
                }
            }
        ], 'amount');
        $query->withSum([
            'bets AS amount_lose' => function ($queryBet) {
                $queryBet->where('result', Bet::LOSE)
                    ->where('is_demo', Bet::REAL_TYPE);
                if ($this->dateFrom && $this->dateTo) {
                    $queryBet->whereBetween('updated_at', [$this->dateFrom, $this->dateTo]);
                }
            }
        ], 'amount');
        $query->withSum([
            'bets AS amount_win_sub_lose' => function ($queryBet) {
                $queryBet->where('is_result', Bet::EXECUTED_RESULT)
                    ->where('is_demo', Bet::REAL_TYPE);
                if ($this->dateFrom && $this->dateTo) {
                    $queryBet->whereBetween('updated_at', [$this->dateFrom, $this->dateTo]);
                }
            }
        ], DB::raw("CASE WHEN bets.result = " . Bet::WIN . " THEN bets.amount ELSE -bets.amount END"));
        $query->withCount([
            'bets AS count_win' => function ($queryBet) {
                $queryBet->where('result', Bet::WIN)
                    ->where('is_demo', Bet::REAL_TYPE);
                if ($this->dateFrom && $this->dateTo) {
                    $queryBet->whereBetween('updated_at', [$this->dateFrom, $this->dateTo]);
                }
            }
        ], '*');
        $query->withCount([
            'bets AS count_lose' => function ($queryBet) {
                $queryBet->where('result', Bet::LOSE)
                    ->where('is_demo', Bet::REAL_TYPE);
                if ($this->dateFrom && $this->dateTo) {
                    $queryBet->whereBetween('updated_at', [$this->dateFrom, $this->dateTo]);
                }
            }
        ], '*');
        $query->whereHas('bets', function ($queryBet) {
            $queryBet->where('is_demo', Bet::REAL_TYPE);
            if ($this->dateFrom && $this->dateTo) {
                $queryBet->whereBetween('updated_at', [$this->dateFrom, $this->dateTo]);
            }
        });
        $query->orderBy('amount_win_sub_lose', 'DESC');

        return $model->apply($query, true, $this->getGridColumns());
    }
}

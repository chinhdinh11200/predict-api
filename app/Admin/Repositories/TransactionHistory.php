<?php

namespace App\Admin\Repositories;

use App\Models\TransactionDetail;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\EloquentRepository;

class TransactionHistory extends EloquentRepository
{
    protected $eloquentClass = TransactionDetail::class;

    public function get(Grid\Model $model)
    {
        $this->setSort($model);
        $this->setPaginate($model);
        $query = $this->newQuery();
        [$column] = $model->getSort();
        if (!$column) {
            $query->orderBy('created_at', 'DESC');
        }
        if ($this->relations) {
            $query->with($this->relations);
        }

        return $model->apply($query, true, $this->getGridColumns());
    }
}
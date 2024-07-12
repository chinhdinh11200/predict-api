<?php

namespace App\Admin\Repositories;

use App\Models\Bet as ModelsBet;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\EloquentRepository;

class Bet extends EloquentRepository
{
    protected $eloquentClass = ModelsBet::class;

    public function get(Grid\Model $model)
    {
        [$column, $type, $cast] = $model->getSort();
        $this->setSort($model);
        $this->setPaginate($model);
        $query = $this->newQuery();
        if (!$column) {
            $query->orderBy('id', 'DESC');
        }
        if ($this->relations) {
            $query->with($this->relations);
        }

        return $model->apply($query, true, $this->getGridColumns());
    }
}

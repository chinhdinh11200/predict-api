<?php

namespace App\Admin\Repositories;

use App\Models\TicketHistory as ModelsTicketHistory;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\EloquentRepository;

class TicketHistory extends EloquentRepository
{

    protected $eloquentClass = ModelsTicketHistory::class;

    public function get(Grid\Model $model)
    {
        $this->setSort($model);
        $this->setPaginate($model);
        $query = $this->newQuery();
        [$column] = $model->getSort();
        if (!$column) {
            $query->orderBy('id', 'DESC');
        }
        if ($this->relations) {
            $query->with($this->relations);
        }

        return $model->apply($query, true, $this->getGridColumns());
    }
}
<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\User as UserModel;
use Dcat\Admin\Grid;

class User extends EloquentRepository {

    protected $eloquentClass = UserModel::class;


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
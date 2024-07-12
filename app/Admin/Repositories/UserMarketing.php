<?php

namespace App\Admin\Repositories;

use App\Models\User;
use App\Models\UserSetting;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\EloquentRepository;

class UserMarketing extends EloquentRepository
{
    protected $eloquentClass = User::class;


    public function get(Grid\Model $model)
    {
        $this->setSort($model);
        $this->setPaginate($model);
        $query = $this->newQuery();
        [$column, $type, $cast] = $model->getSort();
        if (!$column) {
            $query->orderBy('id', 'DESC');
        }
        if ($this->relations) {
            $query->with($this->relations);
        }

        $query->whereHas('setting', function($querySetting) {
            $querySetting->where('is_marketing', UserSetting::MARKETING);
        });

        return $model->apply($query, true, $this->getGridColumns());
    }
}
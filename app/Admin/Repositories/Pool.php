<?php

namespace App\Admin\Repositories;

use App\Models\Pool as ModelsPool;
use Dcat\Admin\Repositories\EloquentRepository;

class Pool extends EloquentRepository
{
    protected $eloquentClass = ModelsPool::class;
}

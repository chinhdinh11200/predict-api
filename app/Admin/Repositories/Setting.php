<?php

namespace App\Admin\Repositories;

use App\Models\Setting as ModelsSetting;
use Dcat\Admin\Repositories\EloquentRepository;

class Setting extends EloquentRepository
{

    protected $eloquentClass = ModelsSetting::class;
}

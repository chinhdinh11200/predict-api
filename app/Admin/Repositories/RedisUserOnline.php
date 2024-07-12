<?php

namespace App\Admin\Repositories;

use App\Models\User;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Redis;

class RedisUserOnline extends EloquentRepository {

    /**
     * Data in redis store by format key: value
     * Key is socket_user:user_id
     * Value is json_encode([...socket.id])
     */
    
    protected $eloquentClass = User::class;
    
    public static function getUserRedis() {
        try {
            $userIdRedis = Redis::keys(config('admin.socket.user_online'));
            $userIds = [];
            foreach ($userIdRedis as $key => $userId) {
                $abc = explode(':', $userId);
                $userIds[] = array_pop($abc);
            }

            return $userIds;
        } catch (\Exception $e) {
            logger($e->getMessage(), [$e]);
            return [];
        }
    }

    public function get(Grid\Model $model)
    {
        $this->setSort($model);
        $this->setPaginate($model);

        $query = $this->newQuery();

        if ($this->relations) {
            $query->with($this->relations);
        }

        $query->whereIn('id', self::getUserRedis());

        return $model->apply($query, true, $this->getGridColumns());
    }
}

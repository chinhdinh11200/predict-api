<?php

namespace App\Services\User;

use App\Models\LuckyWheel;
use App\Services\MasterDataService as BaseMasterDataService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class MasterDataService extends BaseMasterDataService
{
    /**
     * @var array
     */
    protected $availableResources = [
        'transaction_type' => [
            'driver' => self::DRIVER_CUSTOM,
            'target' => 'getTransactionType',
        ],

        'lucky_wheel' => [
            'driver' => self::DRIVER_CUSTOM,
            'target' => 'getLuckyWheel',
        ],

        'lucky_box' => [
            'driver' => self::DRIVER_CUSTOM,
            'target' => 'getLuckyBox',
        ],
    ];

    protected const NAME_IMAGE = [10000, 5000, 500, 100, 50, 10, 5, 1];

    /**
     * Get transaction type
     *
     * @return array
     */
    protected function getTransactionType(): array
    {
        $types = config('transaction.type');
        $results = [];
        foreach ($types as $key => $value) {
            $results[] = [
                'id' => $key,
                'name' => trans('transaction.type.' . $value),
            ];
        }

        return $results;
    }

    /**
     * Get lucky wheel
     *
     * @return Collection
     */
    protected function getLuckyWheel()
    {
        $data = collect();
        $spinWheel = LuckyWheel::query()->get();
        foreach ($spinWheel as $item) {
            for ($i = 0; $i < $item->slice_quantity; $i++) {
                $data->push([
                    'id' => $item->id,
                    'name_vi' => $item->name_vi,
                    'name_en' => $item->name_en,
                    'image_url' => URL::asset($item->image_url),
                    'reward' => $item->reward,
                    'spin_again' => $item->spin_again,
                ]);
            } //end for
        } //end foreach

        return $data->shuffle();
    }

    /**
     * Get lucky box
     *
     * @return Collection
     */
    protected function getLuckyBox()
    {
        $data = array();
        foreach (self::NAME_IMAGE as $item) {
            $data[] = ['image_url' => URL::asset("images/lucky_box/{$item}.png")];
        } //end foreach

        return $data;
    }
}

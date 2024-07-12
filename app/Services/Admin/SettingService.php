<?php

namespace App\Services\Admin;

use App\Models\Setting;
use App\Services\Service;

class SettingService extends Service
{
    /**
     * Get setting key value
     *
     * @return array
     */
    public function index(): array
    {
        $list = Setting::query()->get();
        $data = [];
        foreach ($list as $setting) {
            $data[$setting['key']] = $setting['value'];
        }//end foreach

        return $data;
    }

    /**
     * Update setting key value
     *
     * @param $data
     * @return bool
     */
    public function update($data): bool
    {
        foreach ($data as $key => $value) {
            Setting::query()->where('key', $key)->update(['value' => $value]);
        }//end foreach

        return true;
    }
}

<?php

namespace App\Services\Common;

use App\Services\Service;

class SessionServiceCommon extends Service
{
    /**
     * Get session
     *
     * @param int $limit
     * @return mixed
     */
    public function getSession(int $limit = 5): mixed
    {
        $data = [];
        $endpoint = 'https://api.mexc.com/api/v3/klines';
        $params = [
            'symbol' => 'BTCUSDT',
            'interval' => '1m',
            'startTime' => '',
            'endTime' => '',
            'limit' => $limit,
        ];
        $url = $endpoint . '?' . http_build_query($params);

        $headers = [
            'Content-Type: application/json',
            'X-MEXC-APIKEY: ' . config('chart.x_mexc_apikey'),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);

        if (!curl_errno($ch)) {
            $data = json_decode($response, true);
        }//end if

        curl_close($ch);

        return $data;
    }
}

<?php

namespace App\Services\User;

use App\Models\WalletDepositUser;
use App\Services\Service;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WalletDepositUserService extends Service
{
    /**
     * @param $userId
     * @return mixed
     */
    public function getWalletByUserId($userId): mixed
    {
        return WalletDepositUser::query()->where('user_id', $userId)->first();
    }

    /**
     * @param $userId
     * @return mixed
     * @throws GuzzleException
     */
    public function generateWalletDeposit($userId): mixed
    {
        try {
            $client = new Client();

            $url = config('app.scan_url').'/generate-wallet';
            $apiKey = config('app.scan_api_key');
            $response = $client->post($url, [
                'headers' => [
                    'x-api-token' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'user_id' => $this->user->id,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            if (!@$responseData['data']['success']) {
                return false;
            }

            return WalletDepositUser::query()->where('user_id', $userId)->first();
        } catch (RequestException $exception) {
            Log::error($exception->getMessage());
            return false;
        }//end try
    }
}

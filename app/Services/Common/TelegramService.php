<?php

namespace App\Services\Common;

use App\Jobs\SendTelegramMessageJob;
use App\Services\Service;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramService extends Service
{
    /**
     * Get Telegram Info
     *
     * @return mixed|null
     * @throws TelegramSDKException
     */
    public function getTelegramInfo(): mixed
    {
        $response = Telegram::getUpdates();
        if (empty($response)) {
            return null;
        }//end if

        $update = $response[count($response) - 1];
        $chat = $update->getChat();
        if ($chat->isEmpty()) {
            return null;
        }//end if

        return $chat->id;
    }

    /**
     * Send Message
     *
     * @param string $message
     * @param $chatId
     */
    public function sendMessage(string $message, $chatId)
    {
        dispatch(new SendTelegramMessageJob($chatId, $message));
    }
}

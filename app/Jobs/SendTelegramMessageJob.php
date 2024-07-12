<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Chat group id
     *
     * @var $chatId
     */
    protected $chatId;

    /**
     * Message content
     *
     * @var string $message
     */
    protected string $message;


    /**
     * Create a new job instance.
     *
     * @param $chatId
     * @param string $message
     */
    public function __construct($chatId, string $message)
    {
        $this->chatId = $chatId;
        $this->message = $message;
        $this->onQueue('');
        $this->delay(5);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws TelegramSDKException
     */
    public function handle()
    {
        Telegram::sendMessage([
            'chat_id' => $this->chatId,
            'parse_mode' => 'HTML',
            'text' => $this->message
        ]);
    }

    /**
     * Get Queue Name
     *
     * @return string|null
     */
    public function getQueue(): ?string
    {
        return config('queue.job_name.send_telegram_message');
    }

    /**
     * Get queue delay time
     *
     * @return array|\DateInterval|\DateTimeInterface|int|null
     */
    public function getDelay()
    {
        return config('queue.delay_time.telegram');
    }
}

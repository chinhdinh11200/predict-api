<?php

namespace App\Console\Commands\System;

use App\Console\Kernel;
use App\Services\Common\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramSDKException;

class GetTelegramInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Kernel::CMD_SYS_GET_TELEGRAM_INFO;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CMD Sys] Command get telegram info.';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws TelegramSDKException
     */
    public function handle(): int
    {
        $this->components->info($this->description);

        $chatId = TelegramService::getInstance()->getTelegramInfo();

        Log::info($chatId);

        $this->line('');
        $this->components->info($chatId);

        return Command::SUCCESS;
    }
}

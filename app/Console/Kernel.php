<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    public const CMD_SYS_GET_TELEGRAM_INFO = 'admin:sys:get-telegram-info';
    public const CMD_UPDATE_USER_BALANCE = 'update:balance {--user_id=}';
    public const SCHEDULE_BACKUP_DATABASE_NO_NOTIFY = 'backup:run --only-db --disable-notifications';
    public const SCHEDULE_UPDATE_SESSION = 'update:session {--limit=}';
    public const SCHEDULE_COLLECT_WALLET = 'collect:wallet';

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command(self::SCHEDULE_BACKUP_DATABASE_NO_NOTIFY)
            ->twiceMonthly(1, 15, '00:00')
            ->runInBackground();

        $schedule->command(self::SCHEDULE_UPDATE_SESSION)
            ->everyFiveMinutes()
            ->runInBackground();

        $schedule->command(self::SCHEDULE_COLLECT_WALLET)
            ->hourly()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

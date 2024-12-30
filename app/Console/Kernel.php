<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DbBackup::class,
        Commands\SafetyInspectionAlertMail::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // DBバックアップ 毎日午前3時0分に実行される
        $schedule->command('command:dbBackup')->dailyAt('3:00');
        // 安全点検アラートメール送信 毎日午前9時0分に実行される
        $schedule->command('command:safetyInspectionAlertMail')->dailyAt('9:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

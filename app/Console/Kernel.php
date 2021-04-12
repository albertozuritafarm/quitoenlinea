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
        Commands\sftpUploadFilesSS::class,
        Commands\sftpUploadPaymentsSS::class,
        Commands\productChannelCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sftp:uploadFilesSS')->dailyAt('22:00')->runInBackground()->onOneServer();
        $schedule->command('sftp:uploadPaymentsSS')->dailyAt('22:00')->runInBackground()->onOneServer();
        $schedule->command('sftp:uploadFilesSS')->dailyAt('05:00')->runInBackground()->onOneServer();
        $schedule->command('sftp:uploadPaymentsSS')->dailyAt('05:00')->runInBackground()->onOneServer();
        $schedule->command('ss:productChannel')->dailyAt('23:00')->runInBackground()->onOneServer();
        $schedule->command('sftp:receivePayments')->dailyAt('05:00')->runInBackground()->onOneServer();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

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
        Commands\sendNotifKegiatanPosko::class,
        Commands\sendPolling::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notif:kegiatanposko')
            ->name('sendNotifKegiatanPosko')
            ->timezone('Asia/Jakarta')
            ->daily('15:00');

        $schedule->command('notif:polling')
            ->name('sendPolling')
            ->timezone('Asia/Jakarta')
            ->daily('14:00');
            // ->everyMinute();
        
        $schedule->command('notif:syncdctktp')
            ->name('SyncDctKependudukan')
            ->timezone('Asia/Jakarta')
            // ->daily('15:00');
            ->everyThreeHours();
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

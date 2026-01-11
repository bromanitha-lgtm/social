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
        \App\Console\Commands\PostCleanup::class,
        \App\Console\Commands\AIPostCleanup::class,
        // ... other commands
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Optional: Auto schedule the cleanup
        $schedule->command('posts:cleanup --days=90 --min-views=10')
                 ->monthly()
                 ->timezone('Asia/Colombo')
                 ->emailOutputOnFailure('admin@example.com');
        
        // You might also want to schedule AIPostCleanup if needed
        // $schedule->command('ai:post-cleanup')->daily();
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
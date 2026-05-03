<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run invoice generator daily at midnight
        $schedule->command('invoices:generate')->daily();
        $schedule->command('invoices:mark-overdue')->dailyAt('01:00');

        // Send payment due reminders daily at 9 AM
        $schedule->command('billing:send-due-reminders')->dailyAt('09:00');

        // Send overdue payment notifications to staff/admin daily at 8 AM
        $schedule->command('notifications:send-overdue-payments')->dailyAt('08:00');

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/Console.php');
    }
}

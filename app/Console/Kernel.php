<?php

namespace App\Console;

use App\Console\Commands\Processorders;
use App\Models\Store;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Cron\CronExpression;
use Symfony\Component\Console\Input\ArgvInput;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\Processorders::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if ($this->checkIsScheduleCommand()) {
            return;
        }
        \Log::info('START RUN 1');

        // $schedule->command('inspire')->hourly();
        $stores = Store::where(['check_orders_by' => 'cron'])->get();
        if (!empty($stores)) {
            foreach ($stores as $store) {
                if (CronExpression::isValidExpression($store->orders_cron)) {
                    $schedule->command('Processorders:get ' . $store->id)
                    ->cron($store->orders_cron)->when(
                        function () use ($store) {
                            return true;
                        }
                    )->withoutOverlapping(30)->runInBackground();
                }
            }
        }
    }

    private function checkIsScheduleCommand()
    {
        if ($this->app->runningInConsole()) {
            $input = new ArgvInput();
            $artisanArg = $input->getFirstArgument();
            \Log::info('Argument');
            \Log::info($artisanArg);
            if ($artisanArg && strpos($artisanArg, 'schedule:') !== 0) {
                \Log::info('Schedule Not Running');
                return true;
            }
            \Log::info('Schedule Running');
        }
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

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\ProductScrapingService;
use App\Jobs\CategoryPageBatchJob;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
 
        $startPage = 1;
        $endPage = 5;
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            app(ProductScrapingService::class)->scrapeAndStoreProducts();
        })->dailyAt('11:56'); // horario de execução
        $schedule->job(new CategoryPageBatchJob(1, $startPage, $endPage))->dailyAt('13:12');
        $schedule->job(new CategoryPageBatchJob(7, $startPage, $endPage))->dailyAt('13:13');
        $schedule->job(new CategoryPageBatchJob(13, $startPage, $endPage))->dailyAt('13:13');
        $schedule->job(new CategoryPageBatchJob(11, $startPage, $endPage))->dailyAt('13:14');
        $schedule->job(new CategoryPageBatchJob(15, $startPage, $endPage))->dailyAt('13:14');
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

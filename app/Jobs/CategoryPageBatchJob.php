<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ProductScrapingService;
class CategoryPageBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $category;
    protected $startPage;
    protected $endPage;
    public function __construct($category, $startPage, $endPage)
    {
        $this->category = $category;
        $this->startPage = $startPage;
        $this->endPage = $endPage;
    }

    /**
     * Execute the job.
     */
    public function handle(ProductScrapingService $scrapingService): void
    {
        for ($page = $this->startPage; $page <= $this->endPage; $page++) {
            $scrapingService->scrapePage($this->category, $page);
        }
        if ($this->endPage + 5 <= 30) { 
            dispatch(new CategoryPageBatchJob($this->category, $this->endPage + 1, $this->endPage + 5));
        }
    }
}

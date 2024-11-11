<?php

namespace App\Http\Controllers;

use App\Services\ProductScrapingService;

class ProductScraperController extends Controller
{
    protected $scrapingService;

    public function __construct(ProductScrapingService $scrapingService)
    {
        $this->scrapingService = $scrapingService;
    }

    public function scrape()
    {
        
        $logId =$this->scrapingService->scrapeAndStoreProducts();
        return redirect()->route('products.index', ['log' => $logId])->with('success', 'Produtos extraídos com sucesso. Veja o log da execução.');
    }
}

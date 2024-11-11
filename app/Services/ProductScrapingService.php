<?php
namespace App\Services;

use App\Models\Product;
use App\Models\ScrapingLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler; // Adicionado import da classe Crawler
class ProductScrapingService
{
    public function scrapeAndStoreProducts()
    {
        set_time_limit(0);
        $maxCategories = 20; // supondo até 20 categorias; ajuste conforme necessário
        $maxPages = 1; // limite de 10 páginas por categoria
        // Contadores para novos e produtos atualizados
        $new = 0;
        $updated = 0;
        $errors = 0; 
        for ($category = 1; $category <= $maxCategories; $category++) {
            for ($page = 1; $page <= $maxPages; $page++) {
                    try {
                        // Realiza a requisição para o site
                        $url = "https://www.casadamusica.com.br/loja/catalogo.php?loja=1175919&categoria={$category}&pg={$page}";
                        $response = Http::get($url);
                        
                        // Verifica se a requisição foi bem-sucedida
                        if ($response->successful()) {
                           
                            $html = $response->body();

                           
                            $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
                            $categoryName = $crawler->filter('h1.catalog-name')->text();
                           
                            // Encontrar todos os produtos
                            $products = $crawler->filter('.list-product  .product ');
                            if ($products->count() > 0) {
                                    // Iterar sobre os produtos
                                    $products->each(function ($product) use (&$new, &$updated, $categoryName) {
                                        $price = $product->filter('.preco-avista')->text();
                                    
                                        // Remove o símbolo "R$" e os espaços
                                        $price = str_replace('R$', '', $price);
                                        
                                        // Remove o ponto de milhar
                                        $price = str_replace('.', '', $price);
                                        
                                        // Substitui a vírgula por ponto
                                        $price = str_replace(',', '.', $price);
                                        
                                        // Remove qualquer outro caractere não numérico (se houver)
                                        $price = preg_replace('/[^0-9.]/', '', $price);
                                        
                                        // Converte para float
                                        $price = floatval($price);
                                        
                                        $link=$product->filter('.space-image')->attr('href');
                                        // Converte o preço para um número decimal
                                        //$price = floatval($price);
                                        
                                        $responseProduct = Http::get($link);
                                    
                                        if ($responseProduct->successful()) {
                                                $htmlproduct = $responseProduct->body();
                                                
                                                // Caso utilize o PHP Simple HTML DOM, ou outro parser de sua escolha
                                                $crawlerproduct = new \Symfony\Component\DomCrawler\Crawler($htmlproduct);
                                        
                                                // Encontrar todos os produtos
                                                $productsDetails = $crawlerproduct->filter('.tabs-content');
                                                
                                                $descriptionHtml = $productsDetails->filter('#descricao')->html();
                                                // Remover o texto indesejado, como as informações sobre a loja e as quebras de linha

                                            
                                        }
                                    
                                        $productData = [
                                            'name' => $product->filter('.product-name')->text(),
                                            'price' => $price,
                                            'description' => $descriptionHtml,
                                            'image_url' => $product->filter('.image img.lazyload')->attr('data-src')
                                            //'category'=>$categoryName
                                        ];
                                    
                                        // Chama a função storeOrUpdateProduct para salvar os dados
                                        $this->storeOrUpdateProduct($productData, $new, $updated);

                                        // Temporização para evitar carga excessiva
                                        sleep(2); // Pausa de 2 segundos entre as requisições
                                    
                                    });
                            } else {
                                $errors++;
                                Log::warning("Erro ao acessar a URL: {$url}");  // Registra um erro no log
                                // Se não houver produtos, passe para a próxima página ou categoria
                                return;
                            }
                            // Registra log de sucesso

                        } else {
                            // Caso a requisição falhe
                            $errors++;
                            throw new \Exception('Falha ao acessar o site.');
                        }
                    } catch (\Exception $e) {
                        $errors++;
                                             
                        // Registra o erro nos logs do Laravel
                        Log::error('Falha no Scraping', ['exception' => $e]);
                    }
              
            }       
        }
        $log = ScrapingLog::create([
            'status' => $errors > 0 ? 'Sucesso Parcial' : 'Sucesso',
            'new_products' => $new,
            'updated_products' => $updated,
            'error_message' => $errors > 0 ? "Ocorreram $errors erros durante o scraping." : null,
        ]);
        return $log->id;
    }

    public function storeOrUpdateProduct($productData, &$new, &$updated)
    {
        $product = Product::where('name', $productData['name'])->first();

        if ($product) {
            // Atualiza apenas se houver mudanças
            if (($product->price != $productData['price'])||($product->description != $productData['description'])||($product->image_url != $productData['image_url'])){
                $product->update($productData);
                $updated++; // Incrementa o contador de produtos atualizados
            }
        } else {
            Product::create($productData); // Cria novo produto
            $new++; // Incrementa o contador de novos produtos
        }
    }

    public function scrapePage($category, $page)
    {
        $new = 0;
        $updated = 0;
        $errors = 0; 
        try {
            $url = "https://www.casadamusica.com.br/loja/catalogo.php?loja=1175919&categoria={$category}&pg={$page}";
            $response = Http::get($url);
    
            if ($response->successful()) {
                $html = $response->body();
                $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
                $products = $crawler->filter('.list-product .product');
                if ($products->count() > 0) {
                    $products->each(function ($product) use (&$new, &$updated) {
                        $productData = $this->extractProductData($product);
                        $this->storeOrUpdateProduct($productData, $new, $updated);
                    });
                } else {
                    $errors++;
                    Log::warning("Nenhum produto encontrado na URL: {$url}");
                }
            } else {
                $errors++;
                throw new \Exception("Falha ao acessar a URL: {$url}");
            }
        } catch (\Exception $e) {
            $errors++;
            Log::error('Erro no scraping', ['exception' => $e]);
        } 
        $log = ScrapingLog::create([
            'status' => $errors > 0 ? 'Sucesso Parcial' : 'Sucesso',
            'new_products' => $new,
            'updated_products' => $updated,
            'error_message' => $errors > 0 ? "Ocorreram $errors erros durante o scraping." : null,
        ]);       
    }
    private function extractProductData($product)
    {
        $price = $product->filter('.preco-avista')->text();
        $price = floatval(preg_replace('/[^0-9.]/', '', str_replace(['R$', '.', ','], ['', '', '.'], $price)));
        return [
            'name' => $product->filter('.product-name')->text(),
            'price' => $price,
            'description' => $this->scrapeProductDescription($product->filter('.space-image')->attr('href')),
            'image_url' => $product->filter('.image img.lazyload')->attr('data-src')
        ];
    }
    
    private function scrapeProductDescription($url)
    {
        $response = Http::get($url);
        if ($response->successful()) {
            $crawler = new \Symfony\Component\DomCrawler\Crawler($response->body());
            $descriptionHtml=$crawler->filter('#descricao')->html();

            // Usar preg_replace para remover tudo antes da sequência "============================<br>"
            $descriptionHtml = preg_replace('/.*?=============================/s', '', $descriptionHtml);
            return $descriptionHtml;
        }
        Log::warning("Falha ao acessar detalhes do produto em: {$url}");
        return null;
    }
}

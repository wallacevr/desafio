<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    public function index($log = null)
    {
        $products = Product::paginate(10); 
        return view('products.index', compact('products','log'));
    }
    // Função para exibir os detalhes do produto
    public function show($id)
    {
        // Busca o produto pelo ID
        $product = Product::find($id);

        // Verifica se o produto existe
        if (!$product) {
            abort(404, 'Produto não encontrado');
        }

        // Retorna a view com os dados do produto
        return view('products.show', compact('product'));
    }
}

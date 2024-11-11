@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <!-- Header with product name and price -->
    <div class="product-header text-center">
        <h1 class="display-4">{{ $product->name }}</h1>
        <p class="product-price">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
    </div>

    <div class="container my-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image img-fluid shadow">
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h3 class="mb-3">Descrição</h3>
                <div class="product-description">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center my-5">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Voltar para a lista de produtos</a>
        </div>
    </div>
@endsection


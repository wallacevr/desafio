@extends('layouts.app')

@section('title', 'Produtos')
@section('header', 'Lista de Produtos')

@section('content')
    <h1 class="text-center mb-4">@yield('header', 'Lista de Produtos')</h1>
    @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                @if($log)
                    <a href="{{route('logs.show',$log)}}">Exibir log</a>
                @endif
            </div>

        @endif
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none text-dark">
                    <div class="product-card">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image w-100">
                        <div class="product-info">
                            <h5>{{ $product->name }}</h5>
                            <p class="product-price">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                            <p class="product-description">{!! \Illuminate\Support\Str::limit($product->description, 100) !!}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="pagination-wrapper d-flex justify-content-center my-4">
        {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>

    @if($products->isEmpty())
        <p class="text-center">Nenhum produto encontrado.</p>
    @endif
@endsection

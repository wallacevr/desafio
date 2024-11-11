<!-- resources/views/logs/show.blade.php -->
@extends('layouts.app')

@section('content')
    
    <h1 class="text-center mb-4">@yield('header', 'Log de Execução')</h1>
    <p>Status: {{ $log->status }}</p>
    <p>Novos Produtos: {{ $log->new_products }}</p>
    <p>Produtos Atualizados: {{ $log->updated_products }}</p>
    <p>Mensagem de Erro: {{ $log->error_message ?? 'Nenhum' }}</p>

    <a href="{{ route('products.index') }}">Voltar para Produtos</a>
@endsection

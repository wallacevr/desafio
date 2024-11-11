@extends('layouts.app')

@section('content')

    <h1 class="text-center">LOGS DE SCRAPING</h1>
    <div class="container mt-4">


        <!-- Exibição dos logs -->
        @if($logs->isEmpty())
            <div class="alert alert-warning" role="alert">
                Nenhum log encontrado.
            </div>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Mensagem</th>
                        <th scope="col">Data</th>
                        <th scope="col">Status</th>
                        <th scope="col">Mensagem de Erro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->new_products }} Novos Produtos  <br>{{ $log->updated_products }} Produtos Atualizados</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if(($log->status == 'Sucesso')||($log->status == 'Sucesso Parcial'))
                                    <span class="badge bg-success">{{$log->status}}</span>
                                @else
                                    <span class="badge bg-danger">{{$log->status}}</span>
                                @endif
                            </td>
                            <td>{{ $log->error_message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
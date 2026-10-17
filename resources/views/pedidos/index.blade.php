@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Pedidos</h1>
        <a href="{{ route('mesas.index') }}" class="btn btn-primary">
            <i class="fas fa-th"></i> Mesas
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Mesa</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedidos as $pedido)
                        <tr>
                            <td>#{{ $pedido->id }}</td>
                            <td>{{ str_pad($pedido->mesa->numero, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $pedido->cliente->nome }}</td>
                            <td>{{ $pedido->valor_total_formatado }}</td>
                            <td>
                                @if ($pedido->status === \App\Models\Pedido::STATUS_FINALIZADO)
                                    <span class="badge badge-success">Finalizado</span>
                                @else
                                    <span class="badge badge-danger">Cancelado</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a
                                    href="{{ route('pedidos.edit', $pedido) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="{{ $pedido->status === \App\Models\Pedido::STATUS_CANCELADO ? 'Visualizar pedido' : 'Editar pedido' }}"
                                    aria-label="{{ $pedido->status === \App\Models\Pedido::STATUS_CANCELADO ? 'Visualizar pedido' : 'Editar pedido' }}"
                                >
                                    <i class="fas fa-{{ $pedido->status === \App\Models\Pedido::STATUS_CANCELADO ? 'eye' : 'pen' }}"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Nenhum pedido finalizado ou cancelado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@extends('adminlte::page')

@section('title', $pedido->status === \App\Models\Pedido::STATUS_ABERTO ? 'Atendimento da mesa' : 'Editar pedido')

@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            @if ($pedido->status === \App\Models\Pedido::STATUS_ABERTO)
                Mesa {{ $pedido->mesa->numero }}
            @else
                Editar pedido #{{ $pedido->id }}
            @endif
        </h1>
        <a href="{{ $pedido->status === \App\Models\Pedido::STATUS_ABERTO ? route('mesas.index') : route('pedidos.index') }}" class="btn btn-secondary">
            Voltar
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Itens do pedido</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('pedidos.itens.store', $pedido) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="form-row align-items-end">
                            <div class="col-md-8 form-group mb-md-0">
                                <label for="produto_id">Produto</label>
                                <select name="produto_id" id="produto_id" class="form-control @error('produto_id') is-invalid @enderror" required>
                                    <option value="">Selecione um produto</option>
                                    @foreach ($produtos as $produto)
                                        <option value="{{ $produto->id }}" {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                                            {{ $produto->nome }} — {{ $produto->preco_formatado }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('produto_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2 form-group mb-md-0">
                                <label for="quantidade">Qtd.</label>
                                <input
                                    type="number"
                                    name="quantidade"
                                    id="quantidade"
                                    value="{{ old('quantidade', 1) }}"
                                    min="1"
                                    step="1"
                                    class="form-control @error('quantidade') is-invalid @enderror"
                                    required
                                >
                                @error('quantidade')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2 form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-block">Adicionar</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Preço</th>
                                    <th>Quantidade</th>
                                    <th>Subtotal</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pedido->itens as $item)
                                    <tr>
                                        <td>{{ $item->produto_nome }}</td>
                                        <td>{{ $item->preco_unitario_formatado }}</td>
                                        <td>{{ $item->quantidade }}</td>
                                        <td>{{ $item->subtotal_formatado }}</td>
                                        <td class="text-right">
                                            <form action="{{ route('pedidos.itens.destroy', [$pedido, $item]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-remover-item
                                                    data-produto="{{ $item->produto_nome }}"
                                                >
                                                    Remover
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Nenhum produto adicionado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th>{{ $pedido->valor_total_formatado }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dados do pedido</h3>
                </div>
                <div class="card-body">
                    <p class="mb-3"><strong>Mesa:</strong> {{ $pedido->mesa->numero }}</p>

                    <form action="{{ route('pedidos.cliente.update', $pedido) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="cliente_id">Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="form-control" required>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ $pedido->cliente_id === $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-block">Salvar cliente</button>
                    </form>
                </div>
            </div>

            @if ($pedido->status === \App\Models\Pedido::STATUS_ABERTO)
                <form action="{{ route('pedidos.finalizar', $pedido) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Deseja finalizar este pedido?')">
                        Finalizar pedido
                    </button>
                </form>
            @else
                <div class="alert alert-success mb-0">Pedido finalizado. Alterações são permitidas.</div>
            @endif
        </div>
    </div>
@stop

@section('js')
    <script>
    $(function () {
        $('[data-remover-item]').on('click', function (event) {
            event.preventDefault();

            var botao = $(this);
            var formulario = botao.closest('form');

            Swal.fire({
                title: 'Remover item?',
                text: 'Deseja remover ' + botao.data('produto') + ' deste pedido?',
                icon: 'warning',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Remover',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                padding: '2rem 2.5rem'
            }).then(function (resultado) {
                if (resultado.value) {
                    formulario.get(0).submit();
                }
            });
        });
    });
    </script>
@stop

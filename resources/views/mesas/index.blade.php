@extends('adminlte::page')

@section('title', 'Mesas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Mesas</h1>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-clipboard-list"></i> Pedidos
        </a>
    </div>
@stop

@section('js')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var filtroMesa = document.getElementById('filtro_mesa');
        var filtroStatus = document.getElementById('filtro_status');
        var cards = document.querySelectorAll('[data-mesa]');
        var semResultado = document.getElementById('mesas_sem_resultado');

        function filtrarMesas() {
            var numero = filtroMesa.value.trim();
            var status = filtroStatus.value;
            var quantidadeVisivel = 0;

            cards.forEach(function (card) {
                var mesmaMesa = !numero || card.dataset.mesa === numero;
                var mesmoStatus = !status || card.dataset.status === status;
                var visivel = mesmaMesa && mesmoStatus;

                card.classList.toggle('d-none', !visivel);

                if (visivel) {
                    quantidadeVisivel++;
                }
            });

            semResultado.classList.toggle('d-none', quantidadeVisivel > 0);
        }

        filtroMesa.addEventListener('input', filtrarMesas);
        filtroStatus.addEventListener('change', filtrarMesas);
    });
    </script>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body p-2">
            <div class="form-row">
                <div class="col-md">
                    <label for="filtro_mesa" class="sr-only">Buscar mesa</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input
                            type="search"
                            id="filtro_mesa"
                            class="form-control"
                            placeholder="Buscar mesa por número"
                            inputmode="numeric"
                            autocomplete="off"
                            aria-label="Buscar mesa por número"
                        >
                    </div>
                </div>
                <div class="col-md-3 mt-2 mt-md-0">
                    <label for="filtro_status" class="sr-only">Filtrar por situação</label>
                    <select id="filtro_status" class="form-control" aria-label="Filtrar por situação">
                        <option value="">Todas as situações</option>
                        <option value="livre">Livre</option>
                        <option value="ocupada">Ocupada</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($mesas as $mesa)
            @php
                $pedido = $mesa->pedidoAberto;
            @endphp

            <div
                class="col-xl-2 col-lg-3 col-md-4 col-sm-6"
                data-mesa="{{ $mesa->numero }}"
                data-status="{{ $pedido ? 'ocupada' : 'livre' }}"
            >
                <div class="card mesa-card {{ $pedido ? 'mesa-card--ocupada' : 'mesa-card--livre' }}">
                    <div class="card-body text-center">
                        <div class="mesa-card__numero">Mesa {{ $mesa->numero }}</div>

                        @if ($pedido)
                            <span class="badge badge-primary">Ocupada</span>
                            <div class="mesa-card__cliente" title="{{ $pedido->cliente->nome }}">
                                {{ $pedido->cliente->nome }}
                            </div>
                            <div class="mesa-card__total">{{ $pedido->valor_total_formatado }}</div>
                            <a href="{{ route('mesas.show', $mesa) }}" class="btn btn-primary btn-sm btn-block">
                                Continuar
                            </a>
                        @else
                            <span class="badge badge-success">Livre</span>
                            <div class="mesa-card__vazia">Sem atendimento</div>
                            <form action="{{ route('mesas.abrir', $mesa) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm btn-block">
                                    Abrir
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="mesas_sem_resultado" class="alert alert-light border text-center d-none">
        Nenhuma mesa encontrada para os filtros selecionados.
    </div>
@stop

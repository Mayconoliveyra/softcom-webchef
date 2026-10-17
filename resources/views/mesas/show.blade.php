@extends('adminlte::page')

@section('title', isset($modoPedido) ? (!empty($somenteLeitura) ? 'Visualizar pedido #' . $pedido->id : 'Editar pedido #' . $pedido->id) : 'Mesa ' . $mesa->numero)

@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)

@section('content')
    <div class="pt-3" data-tela-pedido data-somente-leitura="{{ !empty($somenteLeitura) ? '1' : '0' }}">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
        <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Dados</h3>
            @if (isset($modoPedido))
                <div class="ml-auto d-flex align-items-center">
                    <span class="text-muted small pr-3 mr-3 border-right">
                        Mesa <strong>{{ str_pad($mesa->numero, 2, '0', STR_PAD_LEFT) }}</strong>
                    </span>
                    @if ($pedido->status === \App\Models\Pedido::STATUS_FINALIZADO)
                        <span class="badge badge-success">Finalizado</span>
                    @else
                        <span class="badge badge-danger">Cancelado</span>
                    @endif
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="form-row align-items-end">
                @if (!isset($modoPedido))
                <div class="col-md-2 form-group mb-md-0">
                    <label for="mesa_numero">Mesa</label>
                    <input
                        type="text"
                        id="mesa_numero"
                        class="form-control"
                        value="{{ str_pad($mesa->numero, 2, '0', STR_PAD_LEFT) }}"
                        disabled
                    >
                </div>
                @endif
                <div class="{{ isset($modoPedido) ? 'col-md-12' : 'col-md-10' }} form-group mb-0">
                    <form action="{{ route('pedidos.cliente.update', $pedido) }}" method="POST" id="form_cliente">
                        @csrf
                        @method('PUT')
                        <label for="cliente_id">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="form-control mesa-select-pesquisavel" tabindex="1" required {{ !empty($somenteLeitura) ? 'disabled' : '' }}>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $pedido->cliente_id === $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Itens</h3>
        </div>
        <div class="card-body">
            <form
                action="{{ route('pedidos.itens.store', $pedido) }}"
                method="POST"
                class="mb-4"
                id="form_produto"
                data-focar-produto="{{ session('focar_produto') ? '1' : '0' }}"
            >
                @csrf
                <div class="form-row align-items-end">
                    <div class="col-md-8 form-group mb-md-0">
                        <label for="produto_id">Produto</label>
                        <select name="produto_id" id="produto_id" class="form-control mesa-select-pesquisavel @error('produto_id') is-invalid @enderror" tabindex="2" required {{ !empty($somenteLeitura) ? 'disabled' : '' }}>
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
                            tabindex="3"
                            class="form-control @error('quantidade') is-invalid @enderror"
                            required
                            {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                        >
                        @error('quantidade')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group mb-0">
                        <button type="submit" id="adicionar_produto" class="btn btn-primary btn-block" tabindex="4" {{ !empty($somenteLeitura) ? 'disabled' : '' }}>Adicionar</button>
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
                                            {{ !empty($somenteLeitura) ? 'disabled' : '' }}
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pagamentos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 border-right pr-lg-4">
                    <form
                        action="{{ route('pedidos.pagamentos.store', $pedido) }}"
                        method="POST"
                        id="form_pagamentos"
                        autocomplete="off"
                        data-valor-pendente="{{ number_format($pedido->valor_total - $valorPago, 2, '.', '') }}"
                    >
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button
                                        type="button"
                                        class="input-group-text pagamento-forma pagamento-preencher-restante"
                                        data-input="#valor_especie"
                                        tabindex="-1"
                                        {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                                    >
                                        Espécie
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    name="valor_especie"
                                    id="valor_especie"
                                    value="{{ old('valor_especie', '0,00') }}"
                                    class="form-control pagamento-valor @error('valor_especie') is-invalid @enderror"
                                    placeholder="0,00"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    aria-label="Valor pago em espécie"
                                    tabindex="5"
                                    {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                                >
                                @error('valor_especie')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button
                                        type="button"
                                        class="input-group-text pagamento-forma pagamento-preencher-restante"
                                        data-input="#valor_pix"
                                        tabindex="-1"
                                        {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                                    >
                                        Pix
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    name="valor_pix"
                                    id="valor_pix"
                                    value="{{ old('valor_pix', '0,00') }}"
                                    class="form-control pagamento-valor @error('valor_pix') is-invalid @enderror"
                                    placeholder="0,00"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    aria-label="Valor pago em Pix"
                                    tabindex="6"
                                    {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                                >
                                @error('valor_pix')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button
                                        type="button"
                                        class="input-group-text pagamento-forma pagamento-preencher-restante"
                                        data-input="#valor_cartao"
                                        tabindex="-1"
                                        {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                                    >
                                        Cartão
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    name="valor_cartao"
                                    id="valor_cartao"
                                    value="{{ old('valor_cartao', '0,00') }}"
                                    class="form-control pagamento-valor @error('valor_cartao') is-invalid @enderror"
                                    placeholder="0,00"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    aria-label="Valor pago em cartão"
                                    tabindex="7"
                                    {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                                >
                                @error('valor_cartao')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @error('pagamento')
                            <div class="text-danger small mb-3">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
                <div class="col-lg-5 border-right px-lg-4 mt-4 mt-lg-0">
                    <label class="mb-2">Pagamentos lançados</label>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Forma de pagamento</th>
                                    <th>Valor pago</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pedido->pagamentos as $pagamento)
                                    <tr>
                                        <td>{{ $pagamento->forma_pagamento_descricao }}</td>
                                        <td>{{ $pagamento->valor_formatado }}</td>
                                        <td class="text-right">
                                            <form action="{{ route('pedidos.pagamentos.destroy', [$pedido, $pagamento]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-remover-pagamento
                                                    data-forma-pagamento="{{ $pagamento->forma_pagamento_descricao }}"
                                                    {{ !empty($somenteLeitura) ? 'disabled' : '' }}
                                                >
                                                    Remover
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Nenhum pagamento adicionado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-3 pl-lg-4 mt-4 mt-lg-0">
                    <div class="resumo-pagamento">
                        <span class="d-block text-muted small">Resumo</span>
                        <div class="resumo-pagamento-linha">
                            <span>Total</span>
                            <strong>{{ $pedido->valor_total_formatado }}</strong>
                        </div>
                        <div class="resumo-pagamento-linha">
                            <span>Recebido</span>
                            <strong>R$ {{ number_format($valorPago, 2, ',', '.') }}</strong>
                        </div>
                        <div class="resumo-pagamento-linha">
                            <span>Troco</span>
                            <strong>R$ {{ number_format($troco, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a
                href="{{ isset($modoPedido) ? route('pedidos.index') : route('mesas.index') }}"
                class="btn btn-secondary mr-2"
                data-voltar-mesa
                tabindex="8"
            >Voltar (Esc)</a>
            @if (empty($somenteLeitura))
                <form action="{{ route('pedidos.cancelar', $pedido) }}" method="POST" class="d-inline-block mr-2">
                    @csrf
                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                        data-cancelar-mesa
                        data-mensagem-cancelamento="{{ isset($modoPedido) ? 'O pedido será cancelado e permanecerá listado como cancelado.' : 'O atendimento será cancelado e a mesa ficará livre.' }}"
                        tabindex="9"
                    >Cancelar (F8)</button>
                </form>
            @endif
            @if (isset($modoPedido) && empty($somenteLeitura))
                <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST" class="d-inline-block mr-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" data-excluir-pedido>Excluir</button>
                </form>
            @endif
            @if (!isset($modoPedido))
            <form action="{{ route('pedidos.finalizar', $pedido) }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-success" data-finalizar-mesa tabindex="10">Finalizar (F9)</button>
            </form>
            @endif
        </div>
        </div>
    </div>
@stop

@section('js')
    <script>
    $(function () {
        var selectCliente = $('#cliente_id');
        var formCliente = $('#form_cliente');
        var clienteSalvo = selectCliente.val();
        var salvandoCliente = false;
        var selectProduto = $('#produto_id');
        var quantidade = $('#quantidade');
        var formProduto = $('#form_produto');
        var botaoAdicionar = $('#adicionar_produto');
        var formPagamentos = $('#form_pagamentos');
        var somenteLeitura = $('[data-tela-pedido]').data('somente-leitura') === 1;

        if (!somenteLeitura) {
            $('.mesa-select-pesquisavel').select2({
                theme: 'bootstrap4',
                width: '100%',
                language: {
                    noResults: function () {
                        return 'Nenhum resultado encontrado';
                    }
                }
            });
        }

        selectCliente.on('select2:select', function () {
            var novoCliente = selectCliente.val();

            if (salvandoCliente || novoCliente === clienteSalvo) {
                return;
            }

            var dadosCliente = formCliente.serialize();

            salvandoCliente = true;
            selectCliente.prop('disabled', true).trigger('change.select2');

            $.ajax({
                url: formCliente.attr('action'),
                method: 'POST',
                data: dadosCliente,
                headers: {
                    Accept: 'application/json'
                }
            }).done(function () {
                clienteSalvo = novoCliente;
                selectProduto.select2('open');
            }).fail(function () {
                selectCliente.val(clienteSalvo).trigger('change.select2');
            }).always(function () {
                selectCliente.prop('disabled', false).trigger('change.select2');
            });
        });

        selectProduto.on('select2:select', function () {
            quantidade.trigger('focus').select();
        });

        quantidade.on('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                botaoAdicionar.trigger('focus');
            }
        });

        botaoAdicionar.on('keydown', function (event) {
            if (event.key === 'Enter' || event.key === 'Tab') {
                event.preventDefault();
                formProduto.get(0).requestSubmit();
            }
        });

        $(document).on('select2:open', '.mesa-select-pesquisavel', function () {
            var campoPesquisa = document.querySelector(
                '.select2-container--open .select2-search__field'
            );

            if (campoPesquisa) {
                campoPesquisa.focus();
            }
        });

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

        $('[data-remover-pagamento]').on('click', function (event) {
            event.preventDefault();

            var botao = $(this);
            var formulario = botao.closest('form');

            Swal.fire({
                title: 'Remover pagamento?',
                text: 'Deseja remover o pagamento em ' + botao.data('forma-pagamento') + '?',
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

        $('[data-cancelar-mesa]').on('click', function (event) {
            event.preventDefault();

            var formulario = $(this).closest('form');

            Swal.fire({
                title: 'Cancelar?',
                text: $(this).data('mensagem-cancelamento'),
                icon: 'warning',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Cancelar',
                cancelButtonText: 'Voltar',
                confirmButtonColor: '#dc3545',
                padding: '2rem 2.5rem'
            }).then(function (resultado) {
                if (resultado.value) {
                    formulario.get(0).submit();
                }
            });
        });

        $('[data-excluir-pedido]').on('click', function (event) {
            event.preventDefault();

            var formulario = $(this).closest('form');

            Swal.fire({
                title: 'Excluir pedido?',
                text: 'Esta ação removerá o pedido da listagem.',
                icon: 'warning',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Excluir',
                cancelButtonText: 'Voltar',
                confirmButtonColor: '#dc3545',
                padding: '2rem 2.5rem'
            }).then(function (resultado) {
                if (resultado.value) {
                    formulario.get(0).submit();
                }
            });
        });

        $(document).on('keydown', function (event) {
            if (Swal.isVisible() || $('.select2-container--open').length) {
                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                $('[data-voltar-mesa]').get(0).click();
            }

            if (event.key === 'F8' && $('[data-cancelar-mesa]').length) {
                event.preventDefault();
                $('[data-cancelar-mesa]').trigger('click');
            }

            if (event.key === 'F9' && $('[data-finalizar-mesa]').length) {
                event.preventDefault();
                $('[data-finalizar-mesa]').trigger('click');
            }
        });

        function valorParaNumero(valor) {
            return parseFloat(String(valor).replace(/\./g, '').replace(',', '.')) || 0;
        }

        function formatarValorPagamento(campo) {
            var digitos = campo.value.replace(/\D/g, '').slice(0, 10);
            var valor = Number(digitos || 0) / 100;

            campo.value = valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function preencherValorPendente(campo) {
            var valorPendente = parseFloat(formPagamentos.data('valor-pendente')) || 0;

            $('.pagamento-valor').not(campo).each(function () {
                valorPendente -= valorParaNumero($(this).val());
            });

            valorPendente = Math.max(0, valorPendente);
            $(campo).val(valorPendente.toFixed(2).replace('.', ',')).trigger('focus').select();
        }

        $('.pagamento-preencher-restante').on('click', function () {
            preencherValorPendente($(this).data('input'));
        });

        $('.pagamento-valor').on('keydown', function (event) {
            if (event.key === '-') {
                event.preventDefault();
                preencherValorPendente(this);
                return;
            }

            if (event.key === 'Enter') {
                event.preventDefault();

                if (valorParaNumero($(this).val()) > 0) {
                    $('.pagamento-valor').not(this).val('0,00');
                    formPagamentos.get(0).requestSubmit();
                }
            }

            if (event.key === 'Tab' && !event.shiftKey) {
                event.preventDefault();

                var camposPagamento = $('.pagamento-valor');
                var indiceAtual = camposPagamento.index(this);
                var proximoCampo = camposPagamento.eq(indiceAtual + 1);

                if (proximoCampo.length) {
                    proximoCampo.trigger('focus');
                } else {
                    $('[data-cancelar-mesa]').trigger('focus');
                }
            }
        });

        $('.pagamento-valor').on('input', function () {
            formatarValorPagamento(this);
        });

        $('.pagamento-valor').on('focus', function () {
            if ($(this).val() === '0,00') {
                $(this).select();
            }
        });

        $('.pagamento-valor').on('blur', function () {
            $(this).val('0,00');
        });

        if (!somenteLeitura && formProduto.data('focar-produto') === 1) {
            selectProduto.select2('open');
        }
    });
    </script>
@stop

@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Produtos</h1>
        <a href="{{ route('produtos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produtos as $produto)
                        <tr>
                            <td>
                                @if ($produto->imagem)
                                    <img
                                        src="{{ asset('storage/' . $produto->imagem) }}"
                                        alt=""
                                        class="img-thumbnail"
                                        style="width: 48px; height: 48px; object-fit: cover;"
                                    >
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $produto->nome }}</td>
                            <td>{{ $produto->preco_formatado }}</td>
                            <td>
                                @if ($produto->ativo)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-secondary">Inativo</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>
                                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Deseja excluir este produto?')"
                                    >
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Nenhum produto cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

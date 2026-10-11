@extends('adminlte::page')

@section('title', 'Funcionários')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Funcionários</h1>
        <a href="{{ route('funcionarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo
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
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($funcionarios as $funcionario)
                        <tr>
                            <td>{{ $funcionario->nome }}</td>
                            <td>{{ $funcionario->email }}</td>
                            <td>
                                @if ($funcionario->ativo)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-secondary">Inativo</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('funcionarios.edit', $funcionario) }}" class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>
                                <form action="{{ route('funcionarios.destroy', $funcionario) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Deseja excluir este funcionário?')"
                                    >
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Nenhum funcionário cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

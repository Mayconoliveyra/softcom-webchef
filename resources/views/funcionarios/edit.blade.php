@extends('adminlte::page')

@section('title', 'Editar funcionário')

@section('content_header')
    <h1>Editar funcionário</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('funcionarios.update', $funcionario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                @include('funcionarios._form')
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('funcionarios.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
@stop

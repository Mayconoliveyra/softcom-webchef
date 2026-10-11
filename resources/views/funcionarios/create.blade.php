@extends('adminlte::page')

@section('title', 'Cadastrar funcionário')

@section('content_header')
    <h1>Cadastrar funcionário</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('funcionarios.store') }}" method="POST">
            @csrf

            <div class="card-body">
                @include('funcionarios._form')
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('funcionarios.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
    </div>
@stop

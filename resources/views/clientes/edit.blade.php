@extends('adminlte::page')

@section('title', 'Editar cliente')

@section('content_header')
    <h1>Editar cliente</h1>
@stop

@section('js')
    @include('clientes._form_script')
@stop

@section('content')
    <div class="card">
        <form action="{{ route('clientes.update', $cliente) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="card-body">
                @include('clientes._form')
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
@stop

@extends('adminlte::page')

@section('title', 'Cadastrar cliente')

@section('content_header')
    <h1>Cadastrar cliente</h1>
@stop

@section('js')
    @include('clientes._form_script')
@stop

@section('content')
    <div class="card">
        <form action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="card-body">
                @include('clientes._form')
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
    </div>
@stop

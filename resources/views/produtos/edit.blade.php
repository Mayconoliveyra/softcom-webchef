@extends('adminlte::page')

@section('title', 'Editar produto')

@section('content_header')
    <h1>Editar produto</h1>
@stop

@section('js')
    @include('produtos._form_script')
@stop

@section('content')
    <div class="card">
        <form action="{{ route('produtos.update', $produto) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="card-body">
                @include('produtos._form')
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
@stop

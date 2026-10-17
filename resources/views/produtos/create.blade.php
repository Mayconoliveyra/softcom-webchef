@extends('adminlte::page')

@section('title', 'Cadastrar produto')

@section('content_header')
    <h1>Cadastrar produto</h1>
@stop

@section('js')
    @include('produtos._form_script')
@stop

@section('content')
    <div class="card">
        <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf

            <div class="card-body">
                @include('produtos._form')
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
    </div>
@stop

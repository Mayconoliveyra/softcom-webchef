@php
    $cliente = $cliente ?? null;
    $tipoPessoa = old('tipo_pessoa', $cliente ? $cliente->tipo_pessoa : 'fisica');
@endphp

<div class="form-group">
    <label for="tipo_pessoa">Tipo de pessoa</label>
    <select
        name="tipo_pessoa"
        id="tipo_pessoa"
        class="form-control @error('tipo_pessoa') is-invalid @enderror"
        required
    >
        <option value="fisica" {{ $tipoPessoa === 'fisica' ? 'selected' : '' }}>Pessoa física</option>
        <option value="juridica" {{ $tipoPessoa === 'juridica' ? 'selected' : '' }}>Pessoa jurídica</option>
    </select>
    @error('tipo_pessoa')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="documento" id="label_documento">CPF</label>
    <input
        type="text"
        name="documento"
        id="documento"
        value="{{ old('documento', $cliente ? $cliente->documento : '') }}"
        class="form-control @error('documento') is-invalid @enderror"
        inputmode="numeric"
        autocomplete="off"
    >
    @error('documento')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="nome" id="label_nome">Nome</label>
    <input
        type="text"
        name="nome"
        id="nome"
        value="{{ old('nome', $cliente ? $cliente->nome : '') }}"
        class="form-control @error('nome') is-invalid @enderror"
        autocomplete="off"
        required
        autofocus
    >
    @error('nome')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group {{ $tipoPessoa === 'juridica' ? '' : 'd-none' }}" id="grupo_razao_social">
    <label for="razao_social">Razão social</label>
    <input
        type="text"
        name="razao_social"
        id="razao_social"
        value="{{ old('razao_social', $cliente ? $cliente->razao_social : '') }}"
        class="form-control @error('razao_social') is-invalid @enderror"
        autocomplete="off"
        {{ $tipoPessoa === 'juridica' ? 'required' : '' }}
    >
    @error('razao_social')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

@if ($cliente)
    <div class="form-group mb-0">
        <div class="custom-control custom-switch">
            <input
                type="checkbox"
                name="ativo"
                id="ativo"
                value="1"
                class="custom-control-input"
                {{ old('ativo', $cliente->ativo) ? 'checked' : '' }}
            >
            <label class="custom-control-label" for="ativo">Ativo</label>
        </div>
    </div>
@endif

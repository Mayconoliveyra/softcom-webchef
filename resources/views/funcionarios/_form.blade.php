<div class="form-group">
    <label for="nome">Nome</label>
    <input
        type="text"
        name="nome"
        id="nome"
        value="{{ old('nome', isset($funcionario) ? $funcionario->nome : '') }}"
        class="form-control @error('nome') is-invalid @enderror"
        autocomplete="off"
        required
        autofocus
    >
    @error('nome')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="email">E-mail</label>
    <input
        type="email"
        name="email"
        id="email"
        value="{{ old('email', isset($funcionario) ? $funcionario->email : '') }}"
        class="form-control @error('email') is-invalid @enderror"
        autocomplete="off"
        required
    >
    @error('email')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="senha">{{ isset($funcionario) ? 'Nova senha' : 'Senha' }}</label>
    <input
        type="password"
        name="senha"
        id="senha"
        class="form-control @error('senha') is-invalid @enderror"
        autocomplete="new-password"
        {{ isset($funcionario) ? '' : 'required' }}
    >
    @if (isset($funcionario))
        <small class="form-text text-muted">Deixe em branco para manter a senha atual.</small>
    @endif
    @error('senha')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="senha_confirmation">Confirme a senha</label>
    <input
        type="password"
        name="senha_confirmation"
        id="senha_confirmation"
        class="form-control"
        autocomplete="new-password"
        {{ isset($funcionario) ? '' : 'required' }}
    >
</div>

@if (isset($funcionario))
    <div class="form-group mb-0">
        <div class="custom-control custom-switch">
            <input
                type="checkbox"
                name="ativo"
                id="ativo"
                value="1"
                class="custom-control-input"
                {{ old('ativo', $funcionario->ativo) ? 'checked' : '' }}
            >
            <label class="custom-control-label" for="ativo">Ativo</label>
        </div>
    </div>
@endif

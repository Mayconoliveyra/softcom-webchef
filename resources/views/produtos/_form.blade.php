@php
    $produto = $produto ?? null;
    $preco = old('preco', $produto ? number_format($produto->preco, 2, ',', '') : '0,00');
@endphp

<div class="row">
    <div class="col-md-9">
        <div class="form-group">
            <label for="nome">Nome <span class="text-danger">*</span></label>
            <input
                type="text"
                name="nome"
                id="nome"
                value="{{ old('nome', $produto ? $produto->nome : '') }}"
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
            <label for="descricao">Descrição</label>
            <textarea
                name="descricao"
                id="descricao"
                class="form-control @error('descricao') is-invalid @enderror"
                rows="3"
                autocomplete="off"
            >{{ old('descricao', $produto ? $produto->descricao : '') }}</textarea>
            @error('descricao')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="preco">Preço <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">R$</span>
                </div>
                <input
                    type="text"
                    name="preco"
                    id="preco"
                    value="{{ $preco }}"
                    class="form-control @error('preco') is-invalid @enderror"
                    inputmode="numeric"
                    autocomplete="off"
                    required
                >
                @error('preco')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if ($produto)
            <div class="form-group mb-0">
                <div class="custom-control custom-switch">
                    <input
                        type="checkbox"
                        name="ativo"
                        id="ativo"
                        value="1"
                        class="custom-control-input"
                        {{ old('ativo', $produto->ativo) ? 'checked' : '' }}
                    >
                    <label class="custom-control-label" for="ativo">Ativo</label>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-3">
        <div class="form-group produto-imagem-upload mb-0">
            <span class="produto-imagem-upload__titulo">Imagem</span>
            <input
                type="file"
                name="imagem"
                id="imagem"
                class="sr-only"
                accept=".jpg,.jpeg,.png,.gif,.bmp,.svg,.webp"
            >

            <div class="produto-imagem-upload__container">
                <label for="imagem" id="area_imagem" class="produto-imagem-upload__area">
                    <img
                        id="previsualizacao_imagem"
                        src="{{ $produto && $produto->imagem ? asset('storage/' . $produto->imagem) : '' }}"
                        alt=""
                        class="{{ $produto && $produto->imagem ? '' : 'd-none' }}"
                    >
                    <span
                        id="imagem_vazia"
                        class="produto-imagem-upload__vazio text-muted {{ $produto && $produto->imagem ? 'd-none' : '' }}"
                    >
                        <span class="produto-imagem-upload__icone">
                            <i class="far fa-image"></i>
                        </span>
                        Nenhuma imagem selecionada
                    </span>

                    <span class="produto-imagem-upload__acao">
                        <i class="fas fa-upload"></i>
                        <span id="texto_botao_imagem">{{ $produto && $produto->imagem ? 'Trocar imagem' : 'Selecionar imagem' }}</span>
                    </span>
                </label>

                @if ($produto && $produto->imagem)
                    <input type="hidden" name="remover_imagem" id="remover_imagem" value="0">
                    <button
                        type="button"
                        id="botao_remover_imagem"
                        class="produto-imagem-upload__remover"
                        aria-label="Remover imagem"
                        title="Remover imagem"
                    >
                        <i class="fas fa-trash"></i>
                    </button>
                @endif
            </div>

            @error('imagem')
                <span class="text-danger d-block mt-2">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var preco = document.getElementById('preco');
    var imagem = document.getElementById('imagem');
    var areaImagem = document.getElementById('area_imagem');
    var previsualizacaoImagem = document.getElementById('previsualizacao_imagem');
    var imagemVazia = document.getElementById('imagem_vazia');
    var textoBotaoImagem = document.getElementById('texto_botao_imagem');
    var removerImagem = document.getElementById('remover_imagem');
    var botaoRemoverImagem = document.getElementById('botao_remover_imagem');

    function formatarPreco() {
        var digitos = preco.value.replace(/\D/g, '').slice(0, 10);
        var valor = Number(digitos || 0) / 100;

        preco.value = valor.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    // Cada dígito representa centavos: 540 se torna 5,40.
    preco.addEventListener('input', formatarPreco);

    preco.addEventListener('focus', function () {
        if (preco.value === '0,00') {
            preco.select();
        }
    });

    imagem.addEventListener('change', function () {
        var arquivo = imagem.files[0];

        if (! arquivo) {
            return;
        }

        var leitor = new FileReader();

        leitor.onload = function (evento) {
            previsualizacaoImagem.src = evento.target.result;
            previsualizacaoImagem.classList.remove('d-none');
            imagemVazia.classList.add('d-none');
            textoBotaoImagem.textContent = 'Trocar imagem';
            areaImagem.classList.add('previsualizacao-atualizada');

            if (removerImagem) {
                removerImagem.value = '0';
                botaoRemoverImagem.classList.remove('d-none');
            }
        };

        leitor.readAsDataURL(arquivo);
    });

    areaImagem.addEventListener('mouseleave', function () {
        areaImagem.classList.remove('previsualizacao-atualizada');
    });

    imagem.addEventListener('blur', function () {
        areaImagem.classList.remove('previsualizacao-atualizada');
    });

    if (botaoRemoverImagem) {
        botaoRemoverImagem.addEventListener('click', function () {
            removerImagem.value = '1';
            imagem.value = '';
            previsualizacaoImagem.src = '';
            previsualizacaoImagem.classList.add('d-none');
            imagemVazia.classList.remove('d-none');
            textoBotaoImagem.textContent = 'Selecionar imagem';
            areaImagem.classList.remove('previsualizacao-atualizada');
            botaoRemoverImagem.classList.add('d-none');
        });
    }

    formatarPreco();
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tipoPessoa = document.getElementById('tipo_pessoa');
        var documento = document.getElementById('documento');
        var labelNome = document.getElementById('label_nome');
        var labelDocumento = document.getElementById('label_documento');
        var grupoRazaoSocial = document.getElementById('grupo_razao_social');
        var razaoSocial = document.getElementById('razao_social');

        function somenteNumeros(valor) {
            return valor.replace(/\D/g, '');
        }

        function formatarCpf(valor) {
            var numeros = somenteNumeros(valor).slice(0, 11);

            return numeros
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }

        function formatarCnpj(valor) {
            var numeros = somenteNumeros(valor).slice(0, 14);

            return numeros
                .replace(/^(\d{2})(\d)/, '$1.$2')
                .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1/$2')
                .replace(/(\d{4})(\d)/, '$1-$2');
        }

        function atualizarFormulario(limparDocumento, limparRazaoSocial) {
            var pessoaJuridica = tipoPessoa.value === 'juridica';

            labelNome.textContent = pessoaJuridica ? 'Nome fantasia' : 'Nome';
            labelDocumento.textContent = pessoaJuridica ? 'CNPJ' : 'CPF';
            documento.maxLength = pessoaJuridica ? 18 : 14;
            razaoSocial.required = pessoaJuridica;
            grupoRazaoSocial.classList.toggle('d-none', !pessoaJuridica);

            if (limparDocumento) {
                documento.value = '';
            } else {
                documento.value = pessoaJuridica
                    ? formatarCnpj(documento.value)
                    : formatarCpf(documento.value);
            }

            if (! pessoaJuridica && limparRazaoSocial) {
                razaoSocial.value = '';
            }
        }

        tipoPessoa.addEventListener('change', function () {
            atualizarFormulario(true, true);
        });

        documento.addEventListener('input', function () {
            documento.value = tipoPessoa.value === 'juridica'
                ? formatarCnpj(documento.value)
                : formatarCpf(documento.value);
        });

        atualizarFormulario(false, false);
    });
</script>

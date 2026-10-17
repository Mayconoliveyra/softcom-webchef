# Gestão de mesas e pedidos

Status: Em análise
Criado em: 2026-10-17

## Problema ou oportunidade

O restaurante precisa registrar atendimentos em mesas antes de gerar pedidos e manter os pedidos finalizados organizados para consulta, edição, cancelamento e exclusão lógica.

## Contexto conhecido

O sistema já possui cadastros de clientes e produtos. Há um cliente padrão chamado CONSUMIDOR, usado quando não houver identificação do cliente no atendimento.

## Objetivo

Permitir abrir e acompanhar atendimentos em mesas, lançar produtos e transformar um atendimento finalizado em pedido.

## Proposta consolidada

O painel exibirá 30 mesas pré-cadastradas, numeradas de 1 a 30. Cada mesa poderá ter somente um atendimento aberto por vez.

Ao abrir uma mesa, o atendimento iniciará com o cliente CONSUMIDOR. O usuário poderá escolher outro cliente a qualquer momento antes da finalização. Os itens do atendimento usarão produtos ativos e quantidades inteiras, com mínimo de uma unidade. A tela apresentará subtotais por item e o total atualizado do atendimento.

Ao finalizar a mesa, o atendimento será encerrado, a mesa ficará disponível novamente e será criado um pedido. O pedido manterá os dados dos itens e os preços praticados na finalização, preservando o histórico caso o cadastro do produto seja alterado depois.

## Visão futura

Pagamentos, contas, formas de pagamento e fechamento financeiro não fazem parte deste módulo inicial. Eles poderão ser relacionados aos pedidos em uma etapa posterior.

## Escopo inicial

- Criar 30 mesas iniciais.
- Exibir um painel de mesas disponíveis e ocupadas.
- Abrir atendimento com cliente CONSUMIDOR como padrão.
- Permitir alterar o cliente do atendimento.
- Lançar, alterar quantidade e remover itens de produto.
- Calcular subtotais e total da mesa.
- Finalizar mesa e gerar pedido.
- Listar pedidos finalizados e cancelados.
- Editar pedido finalizado sem reabrir a mesa.
- Cancelar pedido sem exigir motivo.
- Excluir pedido logicamente da listagem.

## Fora de escopo

- Contas e pagamentos.
- Reabertura da mesa ao editar pedido finalizado.
- Edição de pedido cancelado.
- Registro obrigatório de motivo de cancelamento.
- Quantidades fracionadas de produtos.

## Alternativas consideradas

- Reabrir a mesa ao editar um pedido finalizado: descartado, pois a mesa pode já estar sendo usada por outro atendimento.
- Exigir cliente antes de abrir a mesa: descartado em favor do cliente padrão CONSUMIDOR, que pode ser substituído.
- Exigir motivo de cancelamento: descartado para manter o fluxo inicial simples.

## Premissas, riscos e dependências

- Apenas produtos ativos devem poder ser lançados em novos atendimentos.
- Cada pedido precisa preservar os dados dos itens no momento da finalização para não depender de alterações posteriores no cadastro de produtos.
- A exclusão de pedido será lógica, como nos demais cadastros do sistema.

## Pontos em aberto

Nenhum ponto de negócio essencial permanece em aberto para o recorte inicial.

## Decisões confirmadas

- Existem 30 mesas pré-cadastradas inicialmente.
- Uma mesa possui somente um atendimento aberto por vez.
- O cliente inicial é CONSUMIDOR e pode ser alterado.
- As quantidades são inteiras e começam em uma unidade.
- Finalizar atendimento libera a mesa e cria um pedido.
- Pedidos finalizados podem ser editados sem reabrir a mesa.
- Pedidos cancelados continuam visíveis, mas não podem ser editados.
- Cancelamento não exige motivo.
- Pedidos excluídos deixam de aparecer na listagem.

## Próximo passo recomendado

Usar `planejar-implementacao` para investigar o projeto e especificar a estrutura de dados, rotas, telas e validações antes da implementação.
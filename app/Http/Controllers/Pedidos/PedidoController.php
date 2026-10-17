<?php

namespace App\Http\Controllers\Pedidos;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\PedidoPagamento;
use App\Models\Produto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pedidos = Pedido::whereIn('status', [
                Pedido::STATUS_FINALIZADO,
                Pedido::STATUS_CANCELADO,
            ])
            ->with(['mesa', 'cliente'])
            ->orderByDesc('finalizado_em')
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function edit(Pedido $pedido)
    {
        if ($pedido->status === Pedido::STATUS_ABERTO) {
            return redirect()->route('mesas.show', $pedido->mesa_id);
        }

        $pedido->load(['mesa', 'cliente', 'itens', 'pagamentos']);

        $clientes = Cliente::where(function ($query) use ($pedido) {
                $query->where('ativo', true)
                    ->orWhere('id', $pedido->cliente_id);
            })
            ->orderBy('nome')
            ->get();

        $produtos = Produto::where('ativo', true)->orderBy('nome')->get();
        $mesa = $pedido->mesa;
        $valorPago = $pedido->pagamentos->sum('valor');
        $troco = $valorPago - $pedido->valor_total;
        $valorPendente = max(0, $pedido->valor_total - $valorPago);
        $modoPedido = true;
        $somenteLeitura = $pedido->status === Pedido::STATUS_CANCELADO;

        return view('mesas.show', compact(
            'mesa',
            'pedido',
            'clientes',
            'produtos',
            'valorPago',
            'troco',
            'valorPendente',
            'modoPedido',
            'somenteLeitura'
        ));
    }

    public function atualizarCliente(Request $request, Pedido $pedido)
    {
        if ($this->pedidoCancelado($pedido)) {
            return $this->redirecionarPedidoCancelado();
        }

        $dados = $request->validate([
            'cliente_id' => ['required', 'integer'],
        ]);

        $cliente = Cliente::where('id', $dados['cliente_id'])
            ->where('ativo', true)
            ->first();

        if (! $cliente) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Selecione um cliente ativo.',
                ], 422);
            }

            return back()->with('error', 'Selecione um cliente ativo.');
        }

        $pedido->update(['cliente_id' => $cliente->id]);

        if ($request->expectsJson()) {
            return response()->json([
                'cliente' => [
                    'id' => $cliente->id,
                    'nome' => $cliente->nome,
                ],
            ]);
        }

        return back()->with('success', 'Cliente atualizado.');
    }

    public function adicionarItem(Request $request, Pedido $pedido)
    {
        if ($this->pedidoCancelado($pedido)) {
            return $this->redirecionarPedidoCancelado();
        }

        $dados = $request->validate([
            'produto_id' => ['required', 'integer'],
            'quantidade' => ['required', 'integer', 'min:1'],
        ]);

        $produto = Produto::where('id', $dados['produto_id'])
            ->where('ativo', true)
            ->first();

        if (! $produto) {
            return back()->with('error', 'Selecione um produto ativo.');
        }

        $pedido->itens()->create([
            'produto_id' => $produto->id,
            'produto_nome' => $produto->nome,
            'preco_unitario' => $produto->preco,
            'quantidade' => $dados['quantidade'],
            'subtotal' => $produto->preco * $dados['quantidade'],
        ]);

        $this->atualizarTotal($pedido);

        return back()
            ->with('success', 'Produto adicionado ao pedido.')
            ->with('focar_produto', true);
    }

    public function removerItem(Pedido $pedido, PedidoItem $item)
    {
        if ($this->pedidoCancelado($pedido)) {
            return $this->redirecionarPedidoCancelado();
        }

        $this->garantirItemDoPedido($pedido, $item);
        $item->delete();
        $this->atualizarTotal($pedido);

        return back()->with('success', 'Produto removido do pedido.');
    }

    public function adicionarPagamento(Request $request, Pedido $pedido)
    {
        if ($this->pedidoCancelado($pedido)) {
            return $this->redirecionarPedidoCancelado();
        }

        $request->merge([
            'valor_especie' => $this->normalizarValor($request->input('valor_especie')),
            'valor_pix' => $this->normalizarValor($request->input('valor_pix')),
            'valor_cartao' => $this->normalizarValor($request->input('valor_cartao')),
        ]);

        $dados = $request->validate([
            'valor_especie' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
            'valor_pix' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
            'valor_cartao' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
        ]);

        $pagamentos = [
            PedidoPagamento::FORMA_ESPECIE => $dados['valor_especie'],
            PedidoPagamento::FORMA_PIX => $dados['valor_pix'],
            PedidoPagamento::FORMA_CARTAO => $dados['valor_cartao'],
        ];

        $pagamentos = array_filter($pagamentos, function ($valor) {
            return $valor !== null;
        });

        if (empty($pagamentos)) {
            return back()
                ->withErrors(['pagamento' => 'Informe ao menos um valor para adicionar o pagamento.'])
                ->withInput();
        }

        foreach ($pagamentos as $formaPagamento => $valor) {
            $pedido->pagamentos()->create([
                'forma_pagamento' => $formaPagamento,
                'valor' => $valor,
            ]);
        }

        return back()->with('success', 'Pagamento adicionado.');
    }

    public function removerPagamento(Pedido $pedido, PedidoPagamento $pagamento)
    {
        if ($this->pedidoCancelado($pedido)) {
            return $this->redirecionarPedidoCancelado();
        }

        $this->garantirPagamentoDoPedido($pedido, $pagamento);
        $pagamento->delete();

        return back()->with('success', 'Pagamento removido.');
    }

    public function finalizar(Pedido $pedido)
    {
        if ($pedido->status !== Pedido::STATUS_ABERTO) {
            return back()->with('error', 'Apenas atendimentos abertos podem ser finalizados.');
        }

        if (! $pedido->itens()->exists()) {
            return back()->with('error', 'Adicione ao menos um produto antes de finalizar.');
        }

        $valorPago = $pedido->pagamentos()->sum('valor');

        if ($this->valorEmCentavos($valorPago) < $this->valorEmCentavos($pedido->valor_total)) {
            return back()->with('error', 'O total pago deve ser igual ou maior que o total da mesa para finalizar.');
        }

        $pedido->update([
            'status' => Pedido::STATUS_FINALIZADO,
            'finalizado_em' => now(),
        ]);

        return redirect()
            ->route('mesas.index')
            ->with('success', 'Mesa finalizada com sucesso.');
    }

    public function cancelar(Pedido $pedido)
    {
        if ($pedido->status === Pedido::STATUS_ABERTO) {
            $pedido->delete();

            return redirect()
                ->route('mesas.index')
                ->with('success', 'Mesa cancelada com sucesso.');
        }

        if ($pedido->status === Pedido::STATUS_CANCELADO) {
            return back()->with('error', 'Este pedido já está cancelado.');
        }

        $pedido->update([
            'status' => Pedido::STATUS_CANCELADO,
            'cancelado_em' => now(),
        ]);

        return redirect()
            ->route('pedidos.index')
            ->with('success', 'Pedido cancelado com sucesso.');
    }

    public function destroy(Pedido $pedido)
    {
        if ($pedido->status === Pedido::STATUS_ABERTO) {
            return back()->with('error', 'Atendimentos abertos não podem ser excluídos.');
        }

        $pedido->delete();

        return redirect()
            ->route('pedidos.index')
            ->with('success', 'Pedido excluído com sucesso.');
    }

    private function atualizarTotal(Pedido $pedido)
    {
        $pedido->update([
            'valor_total' => $pedido->itens()->sum('subtotal'),
        ]);
    }

    private function pedidoCancelado(Pedido $pedido)
    {
        return $pedido->status === Pedido::STATUS_CANCELADO;
    }

    private function redirecionarPedidoCancelado()
    {
        return redirect()
            ->route('pedidos.index')
            ->with('error', 'Pedidos cancelados não podem ser editados.');
    }

    private function garantirItemDoPedido(Pedido $pedido, PedidoItem $item)
    {
        if ($item->pedido_id !== $pedido->id) {
            abort(404);
        }
    }

    private function garantirPagamentoDoPedido(Pedido $pedido, PedidoPagamento $pagamento)
    {
        if ($pagamento->pedido_id !== $pedido->id) {
            abort(404);
        }
    }

    private function normalizarValor($valor)
    {
        $valor = trim((string) $valor);

        if ($valor === '') {
            return null;
        }

        $valorNormalizado = str_replace(['.', ','], ['', '.'], $valor);

        if ((float) $valorNormalizado <= 0) {
            return null;
        }

        return $valorNormalizado;
    }

    private function valorEmCentavos($valor)
    {
        return (int) round(((float) $valor) * 100);
    }
}

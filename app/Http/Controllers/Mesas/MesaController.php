<?php

namespace App\Http\Controllers\Mesas;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Produto;

class MesaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mesas = Mesa::where('ativo', true)
            ->with('pedidoAberto.cliente')
            ->orderBy('numero')
            ->get();

        return view('mesas.index', compact('mesas'));
    }

    public function abrir(Mesa $mesa)
    {
        if (! $mesa->ativo) {
            return redirect()
                ->route('mesas.index')
                ->with('error', 'Esta mesa está inativa.');
        }

        if ($mesa->pedidoAberto()->exists()) {
            return redirect()
                ->route('mesas.index')
                ->with('error', 'Esta mesa já possui um atendimento aberto.');
        }

        $cliente = Cliente::where('padrao', true)->where('ativo', true)->first();

        if (! $cliente) {
            return redirect()
                ->route('mesas.index')
                ->with('error', 'O cliente padrão CONSUMIDOR não foi encontrado.');
        }

        $pedido = Pedido::create([
            'mesa_id' => $mesa->id,
            'cliente_id' => $cliente->id,
            'status' => Pedido::STATUS_ABERTO,
            'valor_total' => 0,
        ]);

        return redirect()->route('mesas.show', $mesa);
    }

    public function show(Mesa $mesa)
    {
        $pedido = $mesa->pedidoAberto()
            ->with(['mesa', 'cliente', 'itens', 'pagamentos'])
            ->first();

        if (! $pedido) {
            return redirect()
                ->route('mesas.index')
                ->with('error', 'Esta mesa não possui um atendimento aberto.');
        }

        $clientes = Cliente::where(function ($query) use ($pedido) {
                $query->where('ativo', true)
                    ->orWhere('id', $pedido->cliente_id);
            })
            ->orderBy('nome')
            ->get();

        $produtos = Produto::where('ativo', true)->orderBy('nome')->get();
        $valorPago = $pedido->pagamentos->sum('valor');
        $troco = $valorPago - $pedido->valor_total;
        $valorPendente = max(0, $pedido->valor_total - $valorPago);

        return view('mesas.show', compact(
            'mesa',
            'pedido',
            'clientes',
            'produtos',
            'valorPago',
            'troco',
            'valorPendente'
        ));
    }
}

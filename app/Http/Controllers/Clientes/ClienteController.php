<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $clientes = Cliente::orderBy('nome')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $dados = $this->dadosValidados($request);
        $dados['ativo'] = true;
        $dados['padrao'] = false;

        Cliente::create($dados);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function edit(Cliente $cliente)
    {
        if ($cliente->padrao) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'O cliente padrão não pode ser editado.');
        }

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        if ($cliente->padrao) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'O cliente padrão não pode ser editado.');
        }

        $dados = $this->dadosValidados($request, $cliente);
        $cliente->update($dados);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->padrao) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'O cliente padrão não pode ser excluído.');
        }

        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente excluído com sucesso.');
    }

    private function dadosValidados(Request $request, Cliente $cliente = null)
    {
        // Mantém CPF e CNPJ sem caracteres de formatação no banco.
        $documento = preg_replace('/\D/', '', (string) $request->input('documento'));
        $request->merge(['documento' => $documento ?: null]);

        // O documento só precisa ser único entre clientes não excluídos.
        $regraDocumento = Rule::unique('cadastro_clientes', 'documento')
            ->whereNull('deleted_at');

        if ($cliente) {
            $regraDocumento->ignore($cliente->id);
        }

        $regras = [
            'nome' => ['required', 'string', 'max:255'],
            'tipo_pessoa' => ['required', Rule::in(['fisica', 'juridica'])],
            'razao_social' => ['nullable', 'string', 'max:255', 'required_if:tipo_pessoa,juridica'],
            'documento' => ['nullable', 'string', $regraDocumento],
        ];

        if ($request->input('tipo_pessoa') === 'fisica') {
            $regras['documento'][] = 'digits:11';
        } elseif ($request->input('tipo_pessoa') === 'juridica') {
            $regras['documento'][] = 'digits:14';
        }

        $dados = $request->validate($regras);
        $dados['ativo'] = $cliente ? $request->has('ativo') : true;

        return $dados;
    }
}

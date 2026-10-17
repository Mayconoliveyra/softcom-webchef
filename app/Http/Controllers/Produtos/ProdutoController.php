<?php

namespace App\Http\Controllers\Produtos;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $produtos = Produto::orderBy('nome')->get();

        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $dados = $this->dadosValidados($request);
        $dados['ativo'] = true;

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        Produto::create($dados);

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto cadastrado com sucesso.');
    }

    public function edit(Produto $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $dados = $this->dadosValidados($request);
        $dados['ativo'] = $request->has('ativo');

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }

            $dados['imagem'] = $request->file('imagem')->store('produtos', 'public');
        } elseif ($request->boolean('remover_imagem') && $produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
            $dados['imagem'] = null;
        }

        $produto->update($dados);

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto excluído com sucesso.');
    }

    private function dadosValidados(Request $request)
    {
        // Normaliza o formato brasileiro do preço antes da validação.
        $preco = str_replace(['.', ','], ['', '.'], (string) $request->input('preco'));
        $request->merge(['preco' => $preco]);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'imagem' => ['nullable', 'image', 'max:2048'],
            'remover_imagem' => ['nullable', 'boolean'],
        ]);

        unset($dados['imagem'], $dados['remover_imagem']);

        return $dados;
    }
}

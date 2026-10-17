<?php

namespace App\Http\Controllers\Funcionarios;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $funcionarios = User::orderBy('nome')->get();

        return view('funcionarios.index', compact('funcionarios'));
    }

    public function create()
    {
        return view('funcionarios.create');
    }

    public function store(Request $request)
    {
        $dados = $this->dadosValidados($request);
        $dados['senha'] = Hash::make($dados['senha']);
        $dados['padrao'] = false;

        User::create($dados);

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário cadastrado com sucesso.');
    }

    public function edit(User $funcionario)
    {
        if ($funcionario->padrao) {
            return redirect()
                ->route('funcionarios.index')
                ->with('error', 'O funcionário padrão não pode ser editado.');
        }

        return view('funcionarios.edit', compact('funcionario'));
    }

    public function update(Request $request, User $funcionario)
    {
        if ($funcionario->padrao) {
            return redirect()
                ->route('funcionarios.index')
                ->with('error', 'O funcionário padrão não pode ser editado.');
        }

        $dados = $this->dadosValidados($request, $funcionario);

        if (! $dados['ativo'] && $funcionario->id === auth()->id()) {
            return back()
                ->withInput()
                ->with('error', 'Você não pode desativar o próprio usuário.');
        }

        if (empty($dados['senha'])) {
            unset($dados['senha']);
        } else {
            $dados['senha'] = Hash::make($dados['senha']);
        }

        $funcionario->update($dados);

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário atualizado com sucesso.');
    }

    public function destroy(User $funcionario)
    {
        if ($funcionario->padrao) {
            return redirect()
                ->route('funcionarios.index')
                ->with('error', 'O funcionário padrão não pode ser excluído.');
        }

        if ($funcionario->id === auth()->id()) {
            return redirect()
                ->route('funcionarios.index')
                ->with('error', 'Você não pode excluir o próprio usuário.');
        }

        $funcionario->delete();

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário excluído com sucesso.');
    }

    private function dadosValidados(Request $request, User $funcionario = null)
    {
        // O e-mail só precisa ser único entre funcionários não excluídos.
        $regraEmail = Rule::unique('cadastro_funcionarios', 'email')
            ->whereNull('deleted_at');

        if ($funcionario) {
            $regraEmail->ignore($funcionario->id);
        }

        $regras = [
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', $regraEmail],
            'senha' => [$funcionario ? 'nullable' : 'required', 'string', 'min:4', 'confirmed'],
        ];

        $dados = $request->validate($regras);
        $dados['ativo'] = $funcionario ? $request->has('ativo') : true;

        return $dados;
    }
}

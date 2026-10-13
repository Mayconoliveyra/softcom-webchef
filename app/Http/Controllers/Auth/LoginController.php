<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // Limita tentativas falhas por e-mail e endereço IP.
    use ThrottlesLogins;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        // Impede que usuários já autenticados retornem para a tela de login.
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->dadosLoginValidados($request);

        // Bloqueia novas tentativas após exceder o limite definido pelo trait.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $this->validarEmailUnico($request);

        // Procura as credenciais e compara a senha digitada com o hash salvo no banco.
        if (Auth::attempt($this->credentials($request))) {
            // Impede que uma sessão criada antes do login seja reutilizada após autenticar.
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            return redirect()->intended($this->redirectTo);
        }

        // Registra a falha para contabilizar o limite de tentativas.
        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'email' => 'Estas credenciais não correspondem aos nossos registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // Descarta dados da sessão anterior e cria um novo token CSRF.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function username()
    {
        return 'email';
    }

    private function dadosLoginValidados(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    }

    private function credentials(Request $request)
    {
        // "ativo" garante que contas desativadas não possam iniciar sessão.
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'ativo' => true,
        ];
    }

    private function validarEmailUnico(Request $request)
    {
        // Impede autenticação ambígua caso exista mais de uma conta ativa com o mesmo e-mail.
        $quantidadeFuncionarios = User::where('email', $request->input('email'))
            ->where('ativo', true)
            ->count();

        if ($quantidadeFuncionarios > 1) {
            throw ValidationException::withMessages([
                'email' => 'Existe mais de um funcionário ativo com este e-mail. Procure o administrador.',
            ]);
        }
    }
}

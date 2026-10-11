<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), [
            'ativo' => true,
        ]);
    }

    protected function attemptLogin(Request $request)
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

        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }
}

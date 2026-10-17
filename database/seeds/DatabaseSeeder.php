<?php

use App\Models\Cliente;
use App\Models\Mesa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $name = env('DEFAULT_USER_NAME');
        $email = env('DEFAULT_USER_EMAIL');
        $password = env('DEFAULT_USER_PASSWORD');

        if (! $name || ! $email || ! $password) {
            throw new \RuntimeException(
                'Configure DEFAULT_USER_NAME, DEFAULT_USER_EMAIL e DEFAULT_USER_PASSWORD no arquivo .env.'
            );
        }

        // withTrashed() considera também registros excluídos logicamente(deleted_at).

        User::withTrashed()->firstOrCreate(
            ['email' => $email],
            [
                'nome' => $name,
                'senha' => Hash::make($password),
                'ativo' => true,
                'padrao' => true,
            ]
        );

        Cliente::withTrashed()->firstOrCreate(
            ['nome' => 'CONSUMIDOR'],
            [
                'razao_social' => null,
                'tipo_pessoa' => 'fisica',
                'documento' => null,
                'ativo' => true,
                'padrao' => true,
            ]
        );

        for ($numero = 1; $numero <= 30; $numero++) {
            Mesa::withTrashed()->firstOrCreate(
                ['numero' => $numero],
                ['ativo' => true]
            );
        }
    }
}

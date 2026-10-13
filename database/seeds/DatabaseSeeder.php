<?php

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

        User::firstOrCreate(
            ['email' => $email],
            [
                'nome' => $name,
                'senha' => Hash::make($password),
                'ativo' => true,
            ]
        );
    }
}

<?php

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        $clientes = [
            [
                'nome' => 'CONSUMIDOR',
                'razao_social' => null,
                'tipo_pessoa' => 'fisica',
                'documento' => null,
                'ativo' => true,
                'padrao' => true,
            ],
            [
                'nome' => 'Ana Souza',
                'razao_social' => null,
                'tipo_pessoa' => 'fisica',
                'documento' => null,
                'ativo' => true,
                'padrao' => false,
            ],
            [
                'nome' => 'Bruno Oliveira',
                'razao_social' => null,
                'tipo_pessoa' => 'fisica',
                'documento' => null,
                'ativo' => true,
                'padrao' => false,
            ],
            [
                'nome' => 'Carla Mendes',
                'razao_social' => null,
                'tipo_pessoa' => 'fisica',
                'documento' => null,
                'ativo' => true,
                'padrao' => false,
            ],
            [
                'nome' => 'Diego Santos',
                'razao_social' => null,
                'tipo_pessoa' => 'fisica',
                'documento' => null,
                'ativo' => true,
                'padrao' => false,
            ],
            [
                'nome' => 'Fernanda Lima',
                'razao_social' => null,
                'tipo_pessoa' => 'fisica',
                'documento' => null,
                'ativo' => true,
                'padrao' => false,
            ],
            [
                'nome' => 'Mercado Central',
                'razao_social' => 'Mercado Central LTDA',
                'tipo_pessoa' => 'juridica',
                'documento' => null,
                'ativo' => true,
                'padrao' => false,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::withTrashed()->firstOrCreate(
                ['nome' => $cliente['nome']],
                $cliente
            );
        }
    }
}

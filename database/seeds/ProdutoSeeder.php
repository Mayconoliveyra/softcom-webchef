<?php

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        $produtos = [
            ['nome' => 'Pizza Calabresa Grande', 'descricao' => 'Molho, muçarela, calabresa e cebola.', 'preco' => 55.00],
            ['nome' => 'Pizza Marguerita Grande', 'descricao' => 'Molho, muçarela, tomate e manjericão.', 'preco' => 52.00],
            ['nome' => 'Pizza Frango com Catupiry Grande', 'descricao' => 'Molho, muçarela, frango desfiado e catupiry.', 'preco' => 58.00],
            ['nome' => 'Porção de Batata Frita', 'descricao' => 'Batata frita crocante, serve duas pessoas.', 'preco' => 25.00],
            ['nome' => 'Coca-Cola 350 ml', 'descricao' => 'Refrigerante em lata.', 'preco' => 5.00],
            ['nome' => 'Coca-Cola 1 L', 'descricao' => 'Refrigerante em garrafa.', 'preco' => 10.00],
            ['nome' => 'Guaraná 350 ml', 'descricao' => 'Refrigerante em lata.', 'preco' => 5.00],
            ['nome' => 'Água Mineral 500 ml', 'descricao' => 'Água sem gás.', 'preco' => 3.50],
            ['nome' => 'Suco de Laranja 300 ml', 'descricao' => 'Suco natural.', 'preco' => 8.00],
            ['nome' => 'Cerveja Long Neck', 'descricao' => 'Cerveja pilsen 330 ml.', 'preco' => 9.00],
        ];

        foreach ($produtos as $produto) {
            Produto::withTrashed()->firstOrCreate(
                ['nome' => $produto['nome']],
                [
                    'descricao' => $produto['descricao'],
                    'preco' => $produto['preco'],
                    'imagem' => null,
                    'ativo' => true,
                ]
            );
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

    protected $table = 'cadastro_produtos';

    protected $fillable = [
        'nome', 'descricao', 'preco', 'imagem', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getPrecoFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->preco, 2, ',', '.');
    }
}

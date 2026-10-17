<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model
{
    use SoftDeletes;

    protected $table = 'cadastro_mesas';

    protected $fillable = [
        'numero', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pedidoAberto()
    {
        return $this->hasOne(Pedido::class, 'mesa_id')
            ->where('status', Pedido::STATUS_ABERTO);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    const STATUS_ABERTO = 'aberto';
    const STATUS_FINALIZADO = 'finalizado';
    const STATUS_CANCELADO = 'cancelado';

    protected $fillable = [
        'mesa_id', 'cliente_id', 'status', 'valor_total', 'finalizado_em', 'cancelado_em',
    ];

    protected $dates = [
        'finalizado_em', 'cancelado_em',
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class)->orderBy('id');
    }

    public function pagamentos()
    {
        return $this->hasMany(PedidoPagamento::class)->orderBy('id');
    }

    public function getValorTotalFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor_total, 2, ',', '.');
    }
}

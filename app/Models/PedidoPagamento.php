<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoPagamento extends Model
{
    const FORMA_ESPECIE = 'especie';
    const FORMA_PIX = 'pix';
    const FORMA_CARTAO = 'cartao';

    protected $table = 'pedido_pagamentos';

    protected $fillable = [
        'pedido_id', 'forma_pagamento', 'valor',
    ];

    public static function formasPagamento()
    {
        return [
            self::FORMA_ESPECIE => 'Espécie',
            self::FORMA_PIX => 'Pix',
            self::FORMA_CARTAO => 'Cartão',
        ];
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function getFormaPagamentoDescricaoAttribute()
    {
        $formas = self::formasPagamento();

        return isset($formas[$this->forma_pagamento]) ? $formas[$this->forma_pagamento] : $this->forma_pagamento;
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'cadastro_clientes';

    protected $fillable = [
        'nome', 'razao_social', 'tipo_pessoa', 'documento', 'ativo', 'padrao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'padrao' => 'boolean',
    ];

    public function getDocumentoFormatadoAttribute()
    {
        if (empty($this->documento)) {
            return null;
        }

        if ($this->tipo_pessoa === 'fisica') {
            return preg_replace(
                '/^(\d{3})(\d{3})(\d{3})(\d{2})$/',
                '$1.$2.$3-$4',
                $this->documento
            );
        }

        return preg_replace(
            '/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/',
            '$1.$2.$3/$4-$5',
            $this->documento
        );
    }
}

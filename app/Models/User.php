<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'cadastro_funcionarios';

    protected $fillable = [
        'nome', 'email', 'senha', 'ativo', 'padrao',
    ];

    protected $hidden = [
        'senha',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'padrao' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function getNameAttribute()
    {
        // Mantém compatibilidade com componentes do Laravel que usam "name".
        return $this->nome;
    }
}

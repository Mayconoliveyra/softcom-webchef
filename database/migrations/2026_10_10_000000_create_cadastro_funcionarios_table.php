<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadastroFuncionariosTable extends Migration
{
    public function up()
    {
        Schema::create('cadastro_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('senha');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            // Mantém o registro no banco quando o funcionário for excluído.
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cadastro_funcionarios');
    }
}

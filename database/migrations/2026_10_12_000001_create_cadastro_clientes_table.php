<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadastroClientesTable extends Migration
{
    public function up()
    {
        Schema::create('cadastro_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('razao_social')->nullable();
            $table->enum('tipo_pessoa', ['fisica', 'juridica']);
            $table->string('documento')->nullable();
            $table->boolean('ativo')->default(true);
            $table->boolean('padrao')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cadastro_clientes');
    }
}

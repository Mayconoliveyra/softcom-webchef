<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->constrained('cadastro_mesas');
            $table->foreignId('cliente_id')->constrained('cadastro_clientes');
            $table->enum('status', ['aberto', 'finalizado', 'cancelado']);
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->timestamp('finalizado_em')->nullable();
            $table->timestamp('cancelado_em')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}

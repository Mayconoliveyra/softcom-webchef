<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', 'Auth\\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\\LoginController@login')->name('login.store');
Route::post('/logout', 'Auth\\LoginController@logout')->name('logout');

Route::get('/dashboard', 'Dashboard\\DashboardController@index')->name('dashboard');

Route::get('/funcionarios', 'Funcionarios\\FuncionarioController@index')->name('funcionarios.index');
Route::get('/funcionarios/create', 'Funcionarios\\FuncionarioController@create')->name('funcionarios.create');
Route::post('/funcionarios', 'Funcionarios\\FuncionarioController@store')->name('funcionarios.store');
Route::get('/funcionarios/{funcionario}/edit', 'Funcionarios\\FuncionarioController@edit')->name('funcionarios.edit');
Route::put('/funcionarios/{funcionario}', 'Funcionarios\\FuncionarioController@update')->name('funcionarios.update');
Route::delete('/funcionarios/{funcionario}', 'Funcionarios\\FuncionarioController@destroy')->name('funcionarios.destroy');

Route::get('/clientes', 'Clientes\\ClienteController@index')->name('clientes.index');
Route::get('/clientes/create', 'Clientes\\ClienteController@create')->name('clientes.create');
Route::post('/clientes', 'Clientes\\ClienteController@store')->name('clientes.store');
Route::get('/clientes/{cliente}/edit', 'Clientes\\ClienteController@edit')->name('clientes.edit');
Route::put('/clientes/{cliente}', 'Clientes\\ClienteController@update')->name('clientes.update');
Route::delete('/clientes/{cliente}', 'Clientes\\ClienteController@destroy')->name('clientes.destroy');

Route::get('/produtos', 'Produtos\\ProdutoController@index')->name('produtos.index');
Route::get('/produtos/create', 'Produtos\\ProdutoController@create')->name('produtos.create');
Route::post('/produtos', 'Produtos\\ProdutoController@store')->name('produtos.store');
Route::get('/produtos/{produto}/edit', 'Produtos\\ProdutoController@edit')->name('produtos.edit');
Route::put('/produtos/{produto}', 'Produtos\\ProdutoController@update')->name('produtos.update');
Route::delete('/produtos/{produto}', 'Produtos\\ProdutoController@destroy')->name('produtos.destroy');

Route::get('/mesas', 'Mesas\\MesaController@index')->name('mesas.index');
Route::post('/mesas/{mesa}/abrir', 'Mesas\\MesaController@abrir')->name('mesas.abrir');
Route::get('/mesas/{mesa}', 'Mesas\\MesaController@show')->name('mesas.show');

Route::get('/pedidos', 'Pedidos\\PedidoController@index')->name('pedidos.index');
Route::get('/pedidos/{pedido}/edit', 'Pedidos\\PedidoController@edit')->name('pedidos.edit');
Route::put('/pedidos/{pedido}/cliente', 'Pedidos\\PedidoController@atualizarCliente')->name('pedidos.cliente.update');
Route::post('/pedidos/{pedido}/itens', 'Pedidos\\PedidoController@adicionarItem')->name('pedidos.itens.store');
Route::delete('/pedidos/{pedido}/itens/{item}', 'Pedidos\\PedidoController@removerItem')->name('pedidos.itens.destroy');
Route::post('/pedidos/{pedido}/pagamentos', 'Pedidos\\PedidoController@adicionarPagamento')->name('pedidos.pagamentos.store');
Route::delete('/pedidos/{pedido}/pagamentos/{pagamento}', 'Pedidos\\PedidoController@removerPagamento')->name('pedidos.pagamentos.destroy');
Route::post('/pedidos/{pedido}/finalizar', 'Pedidos\\PedidoController@finalizar')->name('pedidos.finalizar');
Route::post('/pedidos/{pedido}/cancelar', 'Pedidos\\PedidoController@cancelar')->name('pedidos.cancelar');
Route::delete('/pedidos/{pedido}', 'Pedidos\\PedidoController@destroy')->name('pedidos.destroy');

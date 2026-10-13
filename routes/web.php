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

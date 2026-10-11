<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('/dashboard', 'Dashboard\\DashboardController@index')->name('dashboard');

Route::resource('funcionarios', 'Funcionarios\\FuncionarioController')->only([
    'index', 'create', 'store', 'edit', 'update', 'destroy',
]);

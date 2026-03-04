<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['seguranca']], function () {

//    Route::get('seguranca/usuario/logout', [\App\Http\Controllers\UsuarioLocalController::class, 'logout'])->name('logout');

    Route::controller(\App\Http\Controllers\UsuarioLocalController::class)->group(function () {
        Route::get('admin/usuario/create', 'create');
        Route::get('admin/usuario', 'index');
        Route::post('admin/usuario', 'store');
//        Route::get('admin/usuario/{usuario}/edit', 'edit');
        Route::match(['put', 'patch'], 'admin/usuario/{usuario}', 'update');
        Route::get('admin/usuario/info', 'info');
        Route::delete('admin/usuario/{usuario}', 'destroy');
        Route::get('admin/usuario/validar-dados', 'validarDados');
        Route::get('admin/usuario/grid', 'grid');
        Route::put('admin/usuario/modificar-situacao/{usuario}/{situacao}', 'ativarDesativar');
        Route::get('admin/usuario/info', 'info');
    });

    Route::controller(\App\Http\Controllers\PerfilController::class)->group(function () {
        Route::get('admin/perfil', 'index');
//        Route::get('admin/{perfil}/edit', 'edit');
    });

});



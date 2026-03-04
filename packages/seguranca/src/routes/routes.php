<?php

use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;
use GapPay\Seguranca\Controllers\ConfiguracoesController;
use GapPay\Seguranca\Controllers\AcaoController;
use GapPay\Seguranca\Controllers\AutenticacaoController;
use GapPay\Seguranca\Controllers\TelaController;
use GapPay\Seguranca\Controllers\UsuarioController;

//Rotas protegidas com prefixo api/*
Route::middleware(['seguranca'])->group(function () {
    Route::controller(UsuarioController::class)->group(function () {
        Route::get('api/usuario/info', 'info');
        Route::get('usuario/dados-srh/{cpf}', 'dadosSRH');
    });

    Route::controller(AcaoController::class)->group(function () {
        Route::get('api/acao/grid', [AcaoController::class, 'grid']);
        Route::post('api/acao', [AcaoController::class, 'store']);
        Route::get('api/acao/create', [AcaoController::class, 'create']);
        Route::get('api/acao/{acao}/edit', [AcaoController::class, 'edit']);
        Route::match(['put', 'patch'], 'api/acao/{acao}', [AcaoController::class, 'update']);
        Route::delete('api/acao/{acao}', [AcaoController::class, 'destroy']);
    });

    Route::controller(PerfilController::class)->group(function () {
        Route::get('api/perfil/grid', [PerfilController::class, 'grid']);
        Route::post('api/perfil', [PerfilController::class, 'store']);
        Route::get('api/perfil/create', [PerfilController::class, 'create']);
        Route::get('api/perfil/{perfil}/edit', [PerfilController::class, 'edit']);
        Route::match(['put', 'patch'],'api/perfil/{perfil}', [PerfilController::class, 'update']);
        Route::delete('api/perfil/{perfil}', [PerfilController::class, 'delete']);
    });

//    Route::resource('api/menu', \GapPay\Seguranca\Controllers\MenuController::class);

    Route::controller(TelaController::class)->group(function () {
        Route::post('api/seguranca/telas/analisar', [TelaController::class, 'analisar']);
        Route::post('api/seguranca/dependencia', [TelaController::class, 'store']);
    });

    Route::controller(ConfiguracoesController::class)->group(function() {
        Route::get('api/usuario/configuracoes', [ConfiguracoesController::class, 'edit']);
        Route::match(['put', 'patch'],'api/usuario/configuracoes', [ConfiguracoesController::class, 'update']);
        Route::match(['put', 'patch'],'api/configuracoes/senha', [ConfiguracoesController::class, 'senha']);
    });

//    Route::controller()

//    Route::get('acesso', [AcessoController::class, 'info']);
//    Route::post('acao', [AcaoController::class, 'store']);
});

//Rotas públicas
Route::middleware(['api'])->prefix('api')->group(function () {
    Route::get('atualizar-senha/{hash}', [AutenticacaoController::class, 'validarHash']);
    Route::post('atualizar-senha/{hash}', [AutenticacaoController::class, 'atualizarSenha']);
    Route::post('usuario/enviar-email', [AutenticacaoController::class, 'enviarEmail']);
    Route::get('logout', [AutenticacaoController::class, 'logout'])->name('logout');
    Route::post('login', [AutenticacaoController::class, 'login'])->name('login');
});

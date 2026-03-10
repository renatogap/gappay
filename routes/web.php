<?php

use App\Http\Controllers\UsuarioLocalController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;


Route::get('/', [UsuarioLocalController::class, 'login'])->name('tela.login');
Route::get('/login', [UsuarioLocalController::class, 'login'])->name('tela.login');
Route::post('/login', [\GapPay\Seguranca\Controllers\AutenticacaoController::class, 'login'])->name('login');

Route::get('/login/atualizar-senha', [\App\Http\Controllers\UsuarioLocalController::class, 'telaAtualizacaoSenhaViaHash'])->name('atualizar-senha');
Route::view('/atualizar-senha', 'layouts.vue');
Route::view('/atualizar-senha/{hash}', 'layouts.vue');
Route::view('/login/esqueci-email-senha', 'layouts.vue');

Route::get('cardapio/tipo-cardapio/thumb/{id}', 'CardapioController@verThumbTipoCardapio'); //Publico

//Cliente

Route::controller(\App\Http\Controllers\ClienteController::class)->group(function () {
    Route::get('cliente', 'index'); //Publico
    Route::get('cliente/login/{codigo}', 'login'); //Publico
    Route::get('cliente/cardapios', 'cardapios');
    Route::get('cliente/cardapio/ver-foto/{id}', 'verFoto');
    Route::get('cliente/cardapio/ver-thumb/{id}', 'verThumb');
    Route::get('cliente/cardapio/{id_tipo_cardapio}', 'cardapio');
    Route::get('cliente/cardapio/item/{id}', 'pedidoItem');
    
});


Route::middleware(['session.cliente'])->group(function () {
    Route::get('cliente/home', [ClienteController::class, 'home']);
    Route::get('cliente/pedidos', [ClienteController::class, 'pedidos']);
    Route::get('cliente/saldo', [ClienteController::class, 'saldo']);
    Route::get('cliente/extrato', [ClienteController::class, 'extrato']);
    Route::get('cliente/recarga', [ClienteController::class, 'recarga']);
    Route::get('cliente/logout', [ClienteController::class, 'logout']);

    Route::post('cliente/recarga/store', [ClienteController::class, 'recargaStore']);
    Route::get('cliente/recarga/success', [ClienteController::class, 'recargaSuccess']);
    Route::get('cliente/recarga/cancel', [ClienteController::class, 'recargaCancel']);

    Route::get('cliente/cardapio/show/{id_tipo_cardapio}', [ClienteController::class, 'getCardapioDoPDV']);
    Route::post('cliente/cardapio/add-pedido-cliente', [ClienteController::class, 'addPedidoCliente']);
    Route::post('cliente/cardapio/remove-item-pedido-cliente', [ClienteController::class, 'removeItemPedidoCliente']);
    Route::get('cliente/confirmar-pedido', [ClienteController::class, 'confirmarPedido']);
    Route::get('cliente/pedido/finalizar', [ClienteController::class, 'finalizarPedido']);
    Route::get('cliente/meus-pedidos', [ClienteController::class, 'meusPedidos']);
    Route::get('cliente/meu-pedido/{pedido_id}', [ClienteController::class, 'meuPedido']);
});


Route::get('locale/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return redirect()->back();
})->name('set-locale');

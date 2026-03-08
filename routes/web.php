<?php

use App\Http\Controllers\UsuarioLocalController;
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
    Route::get('cliente/pedido/{pedido_id}/entregue', 'entregarPedido'); //Público - QR Code
});


Route::middleware(['session.cliente'])->group(function () {
    Route::controller(\App\Http\Controllers\ClienteController::class)->group(function () {

        Route::get('cliente/home', 'home');
        Route::get('cliente/pedidos', 'pedidos');
        Route::get('cliente/saldo', 'saldo');
        Route::get('cliente/extrato', 'extrato');
        Route::get('cliente/recarga', 'recarga');
        Route::get('cliente/logout', 'logout');

        Route::post('cliente/recarga/store', 'recargaStore');
        Route::get('cliente/recarga/success', 'recargaSuccess');
        Route::get('cliente/recarga/cancel', 'recargaCancel');

        Route::get('cliente/cardapio/show/{id_tipo_cardapio}', 'getCardapioDoPDV');
        Route::post('cliente/cardapio/add-pedido-cliente', 'addPedidoCliente');
        Route::post('cliente/cardapio/remove-item-pedido-cliente', 'removeItemPedidoCliente');
        Route::get('cliente/confirmar-pedido', 'confirmarPedido');
        Route::get('cliente/pedido/finalizar', 'finalizarPedido');
        Route::get('cliente/meus-pedidos', 'meusPedidos');
        Route::get('cliente/meu-pedido/{pedido_id}', 'meuPedido');
    });
});


Route::get('locale/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return redirect()->back();
})->name('set-locale');

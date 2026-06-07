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

    
// Cadastro de cliente (Responsável)
Route::get('cliente/login', [ClienteController::class, 'loginResponsavelTela'] )->name('tela.login.responsavel');
Route::post('cliente/login', [ClienteController::class, 'loginResponsavel'] )->name('login.responsavel');
Route::get('cliente/cadastro', [ClienteController::class, 'cadastro'] )->name('tela.cadastro');
Route::post('cliente/cadastro/store', [ClienteController::class, 'cadastroStore'] )->name('cliente.cadastro.store');


//Cadastrar cartão cliente (aluno)
Route::middleware(['session.responsavel'])->group(function () {
    Route::get('cliente/alunos', [ClienteController::class, 'gridAlunosResponsavel'] )->name('tela.alunos');
    Route::get('cliente/aluno/create', [ClienteController::class, 'cadastroAluno'] )->name('tela.cadastro.aluno');
    Route::post('cliente/aluno/store', [ClienteController::class, 'cadastroAlunoStore']);
    Route::get('cliente/aluno/{id}/edit', [ClienteController::class, 'editAluno'] )->name('tela.edit.aluno');
    Route::put('cliente/aluno/{id}/update', [ClienteController::class, 'updateAluno'] )->name('tela.update.aluno');
    Route::delete('cliente/aluno/{id}/delete', [ClienteController::class, 'deleteAluno'] )->name('tela.delete.aluno');
    Route::post('cliente/trocar-aluno', [ClienteController::class, 'trocarAluno']);
});

Route::get('cliente/senha/recuperar', [ClienteController::class, 'senhaRecuperarTela']);
Route::post('cliente/senha/recuperar', [ClienteController::class, 'senhaRecuperarCliente']);
Route::get('cliente/senha/redefinir', [ClienteController::class, 'senhaRedefinirTela']);
Route::post('cliente/senha/redefinir', [ClienteController::class, 'senhaRedefinirCliente']);
// Fim Cadastro de cliente (Responsável)

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

 

    Route::get('debug/session', function () {
        dd(session('cliente'));
    });
});


Route::get('locale/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return redirect()->back();
})->name('set-locale');


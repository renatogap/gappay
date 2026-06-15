<?php

use App\Http\Controllers\UsuarioLocalController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;


Route::get('/', [UsuarioLocalController::class, 'login'])->name('tela.login');
Route::get('/login', [UsuarioLocalController::class, 'login'])->name('tela.login');
Route::post('/login', [\GapPay\Seguranca\Controllers\AutenticacaoController::class, 'login'])->name('login');

Route::get('/login/atualizar-senha', [UsuarioLocalController::class, 'telaAtualizacaoSenhaViaHash'])->name('atualizar-senha');
Route::view('/atualizar-senha', 'layouts.vue');
Route::view('/atualizar-senha/{hash}', 'layouts.vue');
Route::view('/login/esqueci-email-senha', 'layouts.vue');

Route::get('cardapio/tipo-cardapio/thumb/{id}', 'CardapioController@verThumbTipoCardapio'); //Publico

//Cliente - Rotas públicas
Route::controller(ClienteController::class)->group(function () {
    Route::get('cliente', 'index'); //Publico
    //Route::get('cliente/login/{codigo}', 'login'); //Publico
    Route::get('cliente/cardapios', 'cardapios');
    Route::get('cliente/cardapio/ver-foto/{id}', 'verFoto');
    Route::get('cliente/cardapio/ver-thumb/{id}', 'verThumb');
    Route::get('cliente/cardapio/{id_tipo_cardapio}', 'cardapio');
    Route::get('cliente/cardapio/item/{id}', 'pedidoItem');

    // (Responsável)
    Route::get('cliente/login', 'loginResponsavelTela')->name('tela.login.responsavel');
    Route::post('cliente/login', 'loginResponsavel')->name('login.responsavel');
    Route::get('cliente/cadastro', 'cadastro')->name('tela.cadastro');
    Route::post('cliente/cadastro/store', 'cadastroStore')->name('cliente.cadastro.store');

    Route::get('cliente/senha/recuperar', 'senhaRecuperarTela');
    Route::post('cliente/senha/recuperar', 'senhaRecuperarCliente');
    Route::get('cliente/senha/redefinir', 'senhaRedefinirTela');
    Route::post('cliente/senha/redefinir', 'senhaRedefinirCliente');
});


Route::middleware(['session.responsavel'])->group(function () {

    Route::controller(ClienteController::class)->group(function () {
        Route::get('cliente/alunos', 'gridAlunosResponsavel')->name('tela.alunos'); // Listar alunos do responsável
        Route::get('cliente/aluno/create', 'cadastroAluno')->name('tela.cadastro.aluno'); // Tela de cadastro de alunos do responsável
        Route::post('cliente/aluno/store', 'cadastroAlunoStore'); // Store do cadastro de alunos do responsável
        Route::get('cliente/aluno/{id}/edit', 'editAluno')->name('tela.edit.aluno'); // Tela de edição de aluno do responsável
        Route::put('cliente/aluno/{id}/update', 'updateAluno')->name('tela.update.aluno'); // Update de aluno do responsável   
        Route::delete('cliente/aluno/{id}/delete', 'deleteAluno')->name('tela.delete.aluno');
        Route::post('cliente/trocar-aluno', 'trocarAluno');
        Route::get('cliente/meus-dados', 'dadosResponsavel')->name('tela.meus-dados'); // Tela de dados do responsável
        Route::put('cliente/meus-dados/update', 'updateDadosResponsavel')->name('update.meus-dados'); // Update dos dados do responsável
    });
});
// Fim (Responsável)

Route::middleware(['session.cliente'])->group(function () {

    Route::controller(ClienteController::class)->group(function () {
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

    Route::get('debug/session', function () {
        dd(session('cliente'));
    });
});


Route::get('locale/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return redirect()->back();
})->name('set-locale');


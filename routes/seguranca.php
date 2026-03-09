<?php

use App\Http\Controllers\CardapioController;
use App\Http\Controllers\CartaoClienteController;
use App\Http\Controllers\CartaoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CozinhaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PortariaController;
use App\Http\Controllers\RelatoriosController;
use App\Http\Controllers\UsuarioLocalController;

Route::group(['middleware' => ['web']], function () {

});


/*
 * Recursos protegidos pelo Segurança
 * Dar as permissoes abaixo a qualquer usuário cadastrado:
 * seguranca/usuario/home
 * seguranca/usuario/logout
 *
 */
Route::group(['middleware' => ['seguranca']], function () {
    //Route::get('/home', [\App\Http\Controllers\UsuarioLocalController::class, 'home'])->name('home');

    Route::get('/home', [UsuarioLocalController::class, 'home'])->name('home');

    Route::controller(\App\Http\Controllers\UsuarioLocalController::class)->group(function () {
        Route::get('admin/usuario', 'index');
        Route::get('admin/usuario/grid', 'grid');
        Route::get('admin/usuario/criar', 'criar')->name('usuario.create');
        Route::post('admin/usuario/store', 'store')->name('usuario.store');
        Route::get('admin/usuario/editar/{usuario}', 'editar')->name('usuario.edit');
        Route::post('admin/usuario/update/{usuario}', 'update')->name('usuario.update');
        // Route::post('admin/usuario/info', 'info');
        Route::post('admin/usuario/reativar/{usuario}', 'reativar')->name('usuario.reativar');
        Route::post('admin/usuario/excluir/{usuario}', 'UsuarioLocalController@excluir')->name('usuario.delete');
    });

    Route::get('cartao', [CartaoController::class, 'index']);
    Route::get('cartao/create', [CartaoController::class, 'create']);
    Route::post('cartao/store', [CartaoController::class, 'store']);
    Route::get('cartao/edit/{codigo}', [CartaoController::class, 'edit']);
    Route::post('cartao/bloqueia-desbloqueia', [CartaoController::class, 'bloqueiaDesbloqueia']);

    Route::get('cartao/gerar-qrcode/{id}', [CartaoController::class, 'gerarQrCode']);
    Route::get('cartao/gerar-cartoes', [CartaoController::class, 'gerarCartoes']);

    Route::get('cartao-cliente', [CartaoClienteController::class, 'index']);
    Route::get('cartao-cliente/pesquisar', [CartaoClienteController::class, 'pesquisar']);
    Route::get('cartao-cliente/create/{codigo}', [CartaoClienteController::class, 'create']);
    Route::post('cartao-cliente/store', [CartaoClienteController::class, 'store']);
    Route::get('cartao-cliente/edit/{id}', [CartaoClienteController::class, 'edit']);
    Route::post('cartao-cliente/bloqueia-desbloqueia', [CartaoClienteController::class, 'bloqueiaDesbloqueia']);
    Route::post('cartao-cliente/devolver-cartao', [CartaoClienteController::class, 'devolverCartao']);
    Route::post('cartao-cliente/zerar-cartao', [CartaoClienteController::class, 'zerarCartao']);
    Route::get('cartao-cliente/leitor-transferencia', [CartaoClienteController::class, 'leitorTransferencia']);
    Route::get('cartao-cliente/dados-transferencia/{codigo}', [CartaoClienteController::class, 'dadosTransferencia']);
    Route::get('cartao-cliente/salvar-transferencia', [CartaoClienteController::class, 'salvarTransferencia']);
    Route::get('cartao-cliente/confirma-transferencia', [CartaoClienteController::class, 'confirmaTransferencia']);



    Route::get('cartao-cliente/leitor', [CartaoClienteController::class, 'leitorCartao']);
    Route::get('cartao-cliente/add-credito/{cartao}', [CartaoClienteController::class, 'addCredito']);
    Route::post('cartao-cliente/salvar-credito', [CartaoClienteController::class, 'salvarCredito']);
    Route::get('cartao-cliente/confirma-credito', [CartaoClienteController::class, 'confirmaCredito']);
    Route::get('cartao-cliente/localizar-cartao/{codigo}', [CartaoClienteController::class, 'localizarCartao']);
    Route::get('cartao-cliente/transferir-credito/{codigo}', [CartaoClienteController::class, 'transferirCredito']);



    //Cardápio ADM
    Route::get('cardapio', [CardapioController::class, 'index']);
    Route::get('cardapio/create', [CardapioController::class, 'create']);
    Route::get('cardapio/edit/{id}', [CardapioController::class, 'edit']);
    Route::post('cardapio/store', [CardapioController::class, 'store']);
    Route::get('cardapio/ver-foto/{id}', [CardapioController::class, 'verFoto']);
    Route::get('cardapio/ver-thumb/{id}', [CardapioController::class, 'verThumb']);
    Route::post('cardapio/salvar-categoria', [CardapioController::class, 'salvarCategoria']);
    Route::get('cardapio/tipo-cardapio', [CardapioController::class, 'tipoCardapio']);
    Route::get('cardapio/tipo-cardapio/thumb/{id}', [CardapioController::class, 'verThumbTipoCardapio']);

    Route::post('cardapio/salvar-tipo-cardapio', [CardapioController::class, 'salvarTipoCardapio']);
    Route::post('cardapio/ativar-item', [CardapioController::class, 'ativarItem']);
    Route::post('cardapio/inativar-item', [CardapioController::class, 'inativarItem']);
    Route::get('cardapio/delete/{id}', [CardapioController::class, 'deletarCardapio']);
    Route::post('cardapio/salvar-produto', [CardapioController::class, 'salvarProduto']);
    Route::post('cardapio/ativar-cardapio', [CardapioController::class, 'ativarCardapio']);
    Route::post('cardapio/inativar-cardapio', [CardapioController::class, 'inativarCardapio']);
    Route::get('cardapio/item/delete/{item}', [CardapioController::class, 'deletarItem']);

    //Cardápio PDV
    Route::get('pedido/cardapios', [PedidoController::class, 'cardapios']);
    Route::get('pedido/cardapio/1', [PedidoController::class, 'cardapio']);
    #Route::get('pedido/cardapio/{id_tipo_cardapio}', [PedidoController::class, 'cardapio']);
    Route::get('pedido/cardapio/show/{id_tipo_cardapio}', [PedidoController::class, 'getCardapioDoPDV']);
    Route::get('pedido/cardapio/item/{id}', [PedidoController::class, 'pedidoItem']);
    Route::post('pedido/cardapio/add-pedido-cliente', [PedidoController::class, 'addPedidoCliente']);
    Route::post('pedido/cardapio/remove-item-pedido-cliente', [PedidoController::class, 'removeItemPedidoCliente']);
    Route::get('pedido/confirmar-pedido', [PedidoController::class, 'confirmarPedido']);
    Route::get('pedido/finalizar/leitor', [PedidoController::class, 'leitor']);
    Route::get('pedido/finalizar/{codigo}', [PedidoController::class, 'finalizarPedido']);

    Route::get('pedido/visualizacao-gerente', [PedidoController::class, 'visualizacaoGerente']);
    Route::get('pedido/cancelar', [PedidoController::class, 'cancelar']);


    //Pedido
    Route::get('pedido/meus-pedidos', [PedidoController::class, 'meusPedidos']);
    Route::get('pedido/historico-pedidos/{mesa}', [PedidoController::class, 'historicoPedidos']);
    Route::get('pedido/historico-pedido/{id_pedido}/{tipo}', [PedidoController::class, 'historicoPedido']);
    Route::get('pedido/historico-pedido-gerente/{id_pedido}', [PedidoController::class, 'historicoPedidoGerente']);
    Route::get('pedido/confirmar-cancelamento/{item}/{id_tipo_cardapio}', [PedidoController::class, 'confirmarCancelamento']);
    Route::post('pedido/cancelar/{item}/{id_tipo_cardapio}', [PedidoController::class, 'cancelarItem']);

    Route::get('pedido/confirmar-cancelamento-gerente/{item}/{id_tipo_cardapio}', [PedidoController::class, 'confirmarCancelamentoGerente']);
    Route::get('pedido/confirmar-cancelamento-gerente2/{item}/{codigo}', [PedidoController::class, 'confirmarCancelamentoGerente2']);
    Route::post('pedido/cancelar-gerente/{item}/{id_tipo_cardapio}', [PedidoController::class, 'cancelarItemGerente']);
    Route::post('pedido/cancelar-gerente2/{item}/{codigo}', [PedidoController::class, 'cancelarItemGerente2']);

    Route::get('pedido/confirmar-entrega/{id_pedido}/{tipo}', [PedidoController::class, 'confirmarEntrega']);
    Route::post('pedido/salvar-entrega/{id_pedido}/{tipo}', [PedidoController::class, 'salvarEntrega']);

    Route::get('pedido/confirmar-entrega-gerente/{id_pedido}', [PedidoController::class, 'confirmarEntregaGerente']);
    Route::post('pedido/salvar-entrega-gerente/{id_pedido}', [PedidoController::class, 'salvarEntregaGerente']);
    Route::get('pedido/salvar-entrega/via-qrcode/{id_pedido}', [PedidoController::class, 'salvarEntregaViaQrCode']);
    


    //Cozinha
    Route::get('cozinha/monitor', [CozinhaController::class, 'monitor']);
    Route::get('cozinha/confirma/{id_pedido}', [CozinhaController::class, 'confirma']);
    Route::post('cozinha/pedido-pronto/{id_pedido}', [CozinhaController::class, 'pedidoPronto']);

    //Estoque
    Route::get('estoque', [EstoqueController::class, 'index']);
    Route::get('estoque/get-estoque-item/{id}', [EstoqueController::class, 'getEstoqueItem']);
    Route::post('estoque/store', [EstoqueController::class, 'store']);
    Route::get('estoque/relatorio', [EstoqueController::class, 'impressao']);
    Route::get('estoque/resumo', [EstoqueController::class, 'resumoEstoque']);
    Route::get('estoque/detalhes-item', [EstoqueController::class, 'detalhesItem']);
    Route::get('estoque/detalhamento-item', [EstoqueController::class, 'detalhamentoItem']);

    //Relatórios
    Route::get('relatorios', [RelatoriosController::class, 'index']);
    Route::get('relatorio/resumo/pdv', [RelatoriosController::class, 'resumoPdv']);
    Route::get('relatorio/detalhado/pdv', [RelatoriosController::class, 'detalhadoPdv']);
    Route::get('relatorio/taxa-servico', [RelatoriosController::class, 'taxaServico']);
    Route::get('relatorio/fechamento-caixa', [RelatoriosController::class, 'fechamentoCaixa']);
    Route::get('relatorio/consultar-pedidos', [RelatoriosController::class, 'consultarPedidos']);
    Route::get('relatorio/fechamento-conta/{codigo}', [RelatoriosController::class, 'fechamentoConta']);
    Route::get('relatorio/devolucao-cartoes', [RelatoriosController::class, 'devolucaoCartoes']);
    Route::get('relatorio/cancelamento', [RelatoriosController::class, 'cancelamento']);
    Route::get('relatorio/vendas', [RelatoriosController::class, 'vendas']);


    //administração de usuários locais (feita por usuário comum)
    Route::get('admin/usuario', [UsuarioLocalController::class, 'index']);
    Route::get('admin/usuario/grid', [UsuarioLocalController::class, 'grid']);
    Route::get('admin/usuario/criar', [UsuarioLocalController::class, 'criar']);
    Route::post('admin/usuario/store', [UsuarioLocalController::class, 'store']);
    Route::get('admin/usuario/editar/{usuario}', [UsuarioLocalController::class, 'editar']);
    Route::post('admin/usuario/update', [UsuarioLocalController::class, 'update']);
    Route::post('admin/usuario/excluir/{usuario}', [UsuarioLocalController::class, 'excluir']);
    Route::post('admin/usuario/info', [UsuarioLocalController::class, 'info']);
    Route::post('admin/usuario/reativar/{usuario}', [UsuarioLocalController::class, 'reativar']);

    //administração de perfis (feita por usuário comum)
    Route::get('admin/perfil', [PerfilController::class, 'index']);
    Route::get('admin/perfil/grid', [PerfilController::class, 'grid']);
    Route::get('admin/perfil/novo', [PerfilController::class, 'novo']);
    Route::post('admin/perfil/store', [PerfilController::class, 'store']);
    Route::get('admin/perfil/editar/{perfil}', [PerfilController::class, 'editar']);
    Route::post('admin/perfil/update', [PerfilController::class, 'update']);
    Route::post('admin/perfil/excluir/{perfil}', [PerfilController::class, 'destroy']);

   
    Route::get('portaria', [PortariaController::class, 'index']);
    Route::post('portaria/validar-entrada', [PortariaController::class, 'validarEntrada']);


    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dashboard/pedidos-por-hora/{data}', [DashboardController::class, 'pedidosPorhora']);
    Route::get('dashboard/recargas-por-hora/{data}', [DashboardController::class, 'recargasPorHora']);
    Route::get('dashboard/recargas-por-mes/{ano}', [DashboardController::class, 'recargasPorMes']);



    Route::view('/senha', 'layouts.vue');
    Route::view('/configuracoes', 'layouts.vue')->name('configuracoes.usuario');

    Route::view('seguranca/acoes', 'layouts.vue');
    Route::view('seguranca/acoes/create', 'layouts.vue');
    Route::view('seguranca/acoes/{id}/edit', 'layouts.vue');

    Route::controller(\App\Http\Controllers\PerfilController::class)->group(function () {
        Route::get('/admin/perfil', 'index');
        Route::get('/admin/perfil/create', 'create');
        Route::get('/admin/perfil/{id}/edit', 'edit');
    });

    Route::view('seguranca/perfis', 'layouts.vue');


    Route::get('cliente/pedido/{pedido_id}/entregue', [ClienteController::class, 'entregarPedido']); //Público - QR Code
});

<?php

namespace App\Models\Regras;

use App\Models\Entity\Cardapio;
use App\Models\Entity\CartaoCliente;
use App\Models\Entity\EntradaCredito;
use App\Models\Entity\Estoque;
use App\Models\Entity\EstoqueSaida;
use App\Models\Entity\Pedido;
use App\Models\Entity\PedidoItem;
use App\Models\Entity\SaidaCredito;
use App\Models\Facade\EstoqueDB;
use Exception;
use Illuminate\Support\Facades\Auth;

class PedidoRegras
{
    public static function salvarPedido(\stdClass $p)
    {
        ##############################################################################        
        # Salva o Pedido
        ##############################################################################

        $mesa = isset(current($p->pedidoCliente)->mesa) ? current($p->pedidoCliente)->mesa : null;
        
        $dataPedido = [
            'fk_cartao' => $p->cartao->id,
            'fk_cartao_cliente' => $p->cartaoCliente->id,
            'mesa' => $mesa,
            'taxa_servico' => $p->taxaServico,
            'valor_total' => $p->valorTotalPedido,
            'dt_pedido' => date('Y-m-d H:i:s'),

            //Se tiver usuário logado, salva o pedido como entregue, senão salva como pendente (pedido feito pelo cliente)
            'status' => isset(Auth::user()->id) ? 3 : 1,

            //se tiver usuário logado, salva o id do usuário, senão verifica se tem cliente na sessão (pedido feito pelo cliente), se sim, salva o id 2 (cliente), senão salva null (pedido sem identificação)
            'fk_usuario' => isset(Auth::user()->id) ? Auth::user()->id : (session('cliente') ? 999 : null) 

        ];

        //Salva Pedido
        $pedido = Pedido::create($dataPedido);

        
        foreach($p->pedidoCliente as $item){

            $estoqueItem = Estoque::where('fk_item_cardapio', $item->id_cardapio)->first();

            //caso o produto esteja ativo, valida o saldo em estoque
            if($estoqueItem) {

                $saldoAtualDoProduto = EstoqueDB::saldoEstoqueProdutoCardapio($item->id_cardapio);

                if($saldoAtualDoProduto < $item->quantidade) {
                    $cardapio = Cardapio::find($item->id_cardapio);
                    throw new Exception('* '.$cardapio->nome_item.' insuficiente. Quantidade no estoque: <b>'.$saldoAtualDoProduto.'</b>');
                }
            }

            ##############################################################################        
            # Salva os Ítens do pedido
            ##############################################################################        

            $itemPedido = [
                'fk_pedido' => $pedido->id,
                'fk_item_cardapio' => $item->id_cardapio,
                'valor' => $item->valor,
                'quantidade' => $item->quantidade,
                'observacao' => isset($item->observacao) ? $item->observacao : null,
                'status' => isset(Auth::user()->id) ? 3 : 1,
            ];

            $pedidoItem = PedidoItem::create($itemPedido);



            ################################################################################
            # Verifica se o ítem é controlado pelo ESTOQUE
            ################################################################################

            $estoque = Estoque::where('fk_item_cardapio', $item->id_cardapio)->first();

            if($estoque) {

                ################################################################################
                # Registra a Saída no Estoque
                ################################################################################
                
                EstoqueSaida::create([
                    'fk_tipo_cardapio' => $pedidoItem->cardapio->fk_tipo_cardapio,
                    'fk_item_cardapio' => $item->id_cardapio,
                    'quantidade' => $item->quantidade,
                    'fk_pedido_item' => $pedidoItem->id,
                    'observacao' => 'Venda nº '.$pedido->id,
                    'fk_usuario' => isset(Auth::user()->id) ? Auth::user()->id : (session('cliente') ? 999 : null),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                
                ################################################################################
                # Atualiza a Quantidade no Estoque
                ################################################################################
                
                $estoque->qtd_atual = ($estoque->qtd_atual - $item->quantidade);
                $estoque->dt_ultima_atualizacao = date('Y-m-d H:i:s');
                $estoque->fk_usuario_alt = isset(Auth::user()->id) ? Auth::user()->id : (session('cliente') ? 999 : null);
                $estoque->save();


                ################################################################################
                # Inativa o ítem do CARDÁPIO
                ################################################################################

                if($estoque->qtd_atual < 1) {
                    $itemCardapio = Cardapio::find($item->id_cardapio);
                    $itemCardapio->status = 0;
                    $itemCardapio->save();
                }

            }//END IF Estoque

        }//END Foreach


        ################################################################################
        # Alterar o Status do Pedido se for o caso
        ################################################################################
        
        //caso todos os itens do pedido foram entregues na hora, então o status do pedido tbm será entregue
        $itensDoPedido = PedidoItem::where('fk_pedido', $pedido->id)->where('status', 1)->get();
        if($itensDoPedido->count() == 0){
            $pedido->status = 3;
            $pedido->dt_entrega = date('Y-m-d- H:i:s');
            $pedido->save(); 
        }


        ##############################################################################
        # Registra a Saída do Crédito
        ##############################################################################

        SaidaCredito::create([
            'fk_pedido' => $pedido->id,
            'fk_cartao_cliente' => $p->cartaoCliente->id,
            'valor' => $p->valorTotalPedido,
            'observacao' => 'Ref. ao pedido nº '.$pedido->id,
            'data' => date('Y-m-d H:i:s')
        ]);


        ##############################################################################
        # Atualiza saldo do Cartão do Cliente
        ##############################################################################

        $rowCartaoCliente = CartaoCliente::find($p->cartaoCliente->id);
        $rowCartaoCliente->valor_atual = ($rowCartaoCliente->valor_atual - $p->valorTotalPedido);
        $rowCartaoCliente->save();

        return $pedido;
    }

    public static function cancelarPedidoItem($item)
    {
        ##############################################################################
        # Alterar o status do ítem do pedido para CANCELADO
        ##############################################################################
        
        $pedidoItem = PedidoItem::find($item);
        $valorItem = $pedidoItem->valor;

        $pedidoItem->status = 4; //cancelado
        $pedidoItem->valor = 0;
        $pedidoItem->fk_usuario_cancelamento = Auth::user()->id;
        $pedidoItem->dt_cancelamento = date('Y-m-d H:i');
        $pedidoItem->save();



        ##############################################################################
        # INI PEDIDO
                
        $pedido = Pedido::find($pedidoItem->fk_pedido);
        $comissaoAtual = $pedido->taxa_servico;
        $todosItensPedido = PedidoItem::where('fk_pedido', $pedidoItem->fk_pedido)->where('status', '!=', 4)->get();
        $comissaoDoItem = 0;


        ##############################################################################
        # Se TODOS os ítens foram Cancelados, Zera Taxa de Servico e o Valor Total
        ##############################################################################

        if($todosItensPedido->count() == 0){
            $pedido->status = 4;
            $pedido->taxa_servico = 0;
            $pedido->valor_total = 0;
            #$pedido->fk_usuario = null; //sairá do relatório do promotor este pedido
        }
        
        ##############################################################################
        # Subtrai do Valor Total do PEDIDO, o Valor do Ítem e da Comissão que foram Cancelados
        ##############################################################################
        
        else {        
            if($pedido->taxa_servico > 0) {
                $comissaoDoItem = ($valorItem * 10/100); //10% do item
                $pedido->taxa_servico = $pedido->taxa_servico - $comissaoDoItem;
            }
            
            $pedido->valor_total = ($pedido->valor_total - $valorItem - $comissaoDoItem);
        }

        $pedido->save();

        # END PEDIDO
        ##############################################################################
        

        ##############################################################################
        # Registra o ESTORNO ao Cliente
        ##############################################################################
        
        $entrada = new EntradaCredito();
        $entrada->fk_cartao_cliente = $pedido->fk_cartao_cliente;
        $entrada->valor = $valorItem;
        $entrada->fk_tipo_pagamento = 4; //Cancelamento
        $entrada->data = date('Y-m-d H:i:s');
        $entrada->fk_usuario = Auth::user()->id;
        $entrada->observacao = 'Cancelamento do pedido: '.$pedido->id.' | ítem: '.$pedidoItem->cardapio->nome_item;
        $entrada->save();


        ##############################################################################
        # Registra o ESTORNO da Comissão
        ##############################################################################

        if($pedido->taxa_servico > 0) {
            $entrada = new EntradaCredito();
            $entrada->fk_cartao_cliente = $pedido->fk_cartao_cliente;
            $entrada->valor = ($comissaoAtual > 0) ? ($valorItem * 10/100) : 0;
            $entrada->fk_tipo_pagamento = 4; //Cancelamento
            $entrada->data = date('Y-m-d H:i:s');
            $entrada->fk_usuario = Auth::user()->id;
            $entrada->observacao = 'Cancelamento da comissão';
            $entrada->save();
        }


        ##############################################################################
        # Atualiza o novo valor no Cartão do Cliente
        ##############################################################################
        
        $cartaCliente = CartaoCliente::find($pedido->fk_cartao_cliente);
        $valorComissao = ($comissaoAtual > 0 ? ($valorItem * 10/100) : 0);
        $cartaCliente->valor_atual = ($cartaCliente->valor_atual + $valorItem + $valorComissao);
        $cartaCliente->save();



        ################################################################################
        # Verifica se o Ítem está no Estoque
        ################################################################################
        $estoque = Estoque::where('fk_item_cardapio', $pedidoItem->fk_item_cardapio)->first();

        if($estoque) {

            ################################################################################		
            # Remove o registro de Saída do Estoque
            ################################################################################		
            EstoqueSaida::where('fk_item_cardapio', $pedidoItem->fk_item_cardapio)
                        ->where('fk_pedido_item', $item)
                        ->delete();

            ################################################################################
            # Registra a Devolução ao Estoque 
            ################################################################################
            /*
            EstoqueEntrada::create([
                'fk_tipo_cardapio' => $estoque->fk_tipo_cardapio,
                'fk_item_cardapio' => $estoque->fk_item_cardapio,
                'quantidade' => $pedidoItem->quantidade,
                'valor_unitario' => null,
                'valor_total' => null,
                'observacao' => 'Cancelamento da Venda nº '.$pedido->id,
                'fk_usuario_cad' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            */
            

            ################################################################################
            # Atualiza a Quantidade Atual do Ítem no Estoque
            ################################################################################
            
            $estoque->qtd_atual = ($estoque->qtd_atual + $pedidoItem->quantidade);
            $estoque->dt_ultima_atualizacao = date('Y-m-d H:i:s');
            $estoque->fk_usuario_alt = Auth::user()->id;
            $estoque->save();
        }


        return $pedido;
    }
}
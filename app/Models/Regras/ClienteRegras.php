<?php

namespace App\Models\Regras;

use App\Models\Entity\Cartao;
use App\Models\Entity\CartaoCliente;
use App\Models\Entity\Cliente;
use App\Models\Entity\EntradaCredito;
use App\Models\Entity\EstoqueEntrada;
use App\Models\Facade\ClienteDB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;

class ClienteRegras
{
    private static $error = null;

    public static function salvar($p)
    {
        $p->cartao = self::validaCartao($p->codigo);
        
        if(!$p->cartao) throw new Exception(self::$error);

        $cliente = self::saveCliente($p);
        $p->cartaoCliente = self::saveNovoCartaoCliente($p, $cliente);
        Cliente::where('id', $cliente->id)->update(['fk_cartao_cliente' => $p->cartaoCliente->id]);
        self::saveEntradaCreditoRefCaucao($p);
        return $cliente;
    }

    public static function alterar($p)
    {
        $p->cartao = self::validaCartao($p->codigo, $p->id);

        if(!$p->cartao) throw new Exception(self::$error);

        $cliente = self::saveCliente($p);
        
        //verifica se cartão do cliente no BD = ao submetido do Formulário
        if($cliente->fk_cartao != $p->cartao->id) {
            self::saveDevolucao($cliente);
            $p->cartaoCliente = self::saveNovoCartaoCliente($p, $cliente);
            self::saveEntradaCreditoRefCaucao($p);
        }else {
            self::editaCartaoCliente($p, $cliente);
        }
        return $cliente;
    }

    
    public static function saveCliente($p)
    {
        //INSERT
        if(!$p->id) {
            $cliente = new Cliente();
            $cliente->created_at = date('Y-m-d H:i:s');
            $cliente->fk_usuario = Auth::user()->id;
        } 
        
        //UPDATE
        else {
            $cliente = Cliente::find($p->id);            
            $cliente->updated_at = date('Y-m-d H:i:s');
            $cliente->fk_usuario_alt = Auth::user()->id;
        }

        $cliente->fk_cartao = $p->cartao->id;
        $cliente->fk_escola = $p->escola;
        $cliente->nome = $p->nome;
        $cliente->cpf = $p->cpf ? preg_replace('/[^0-9]/', '', $p->cpf) : null;
        $cliente->telefone = $p->telefone ?? null;
        $cliente->fk_tipo_cliente = $p->tipo;
        $cliente->dia_vencimento = $p->diaVencimento;
        $cliente->observacao = $p->observacao;
        $cliente->save();

        

        return $cliente;
    }

    public static function saveNovoCartaoCliente($p, $cliente)
    {
        $cartaoCliente = CartaoCliente::create([
            'fk_cartao' => $p->cartao->id,
            'fk_cliente_titular' => $cliente->id,
            'nome' => $p->nome,
            'cpf' => $p->cpf ? preg_replace('/[^0-9]/', '', $p->cpf) : null,
            'telefone' => $p->telefone ?? null,
            'fk_tipo_cliente' => $p->tipo,
            'fk_tipo_pagamento' => $p->tipoPagamento,
            'valor_atual' => 0,
            'valor_cartao' => formatarMoedaDB($p->valorCartao),
            'observacao' => $p->observacao ?? null,
            'devolvido' => 'N',
            'status' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'fk_usuario' => Auth::user()->id,
        ]);

    
        Cartao::where('id', $p->cartao->id)->update(['fk_situacao' => 2]);

        return $cartaoCliente;
    }

    public static function editaCartaoCliente($p, $cliente)
    {
        $cartaoCliente = CartaoCliente::find($cliente->fk_cartao_cliente);
        $cartaoCliente->fk_cartao = $p->cartao->id;
        $cartaoCliente->fk_cliente_titular = $cliente->id;
        $cartaoCliente->nome = $p->nome;
        $cartaoCliente->cpf = $p->cpf ? preg_replace('/[^0-9]/', '', $p->cpf) : null;
        $cartaoCliente->telefone = $p->telefone ?? null;
        $cartaoCliente->fk_tipo_cliente = $p->tipo;
        $cartaoCliente->observacao = $p->observacao ?? null;
        $cartaoCliente->updated_at = date('Y-m-d H:i:s');
        //$cartaoCliente->fk_usuario_alt = Auth::user()->id;
        //$cartaoCliente->devolvido = 'N';
        //$cartaoCliente->fk_tipo_pagamento = $p->tipoPagamento;
        //$cartaoCliente->valor_atual = 0;
        //$cartaoCliente->valor_cartao = formatarMoeda($p->valorCartao);
        //$cartaoCliente->status = 2;
        $cartaoCliente->save();
    }

    public static function saveEntradaCreditoRefCaucao($p)
    {
        EntradaCredito::create([
            'fk_cartao_cliente' => $p->cartaoCliente->id,
            'valor' => formatarMoedaDB($p->valorCartao),
            'fk_tipo_pagamento' => $p->tipoPagamento,
            'observacao' => 'Crédito Cartão aluno: '.$p->nome,
            'data' => date('Y-m-d H:i:s'),
            'fk_usuario' => Auth::user()->id
        ]);
    }

    public static function saveDevolucao($cliente)
    {
        CartaoCliente::where('id', $cliente->fk_cartao_cliente)->update(['status' => 1, 'devolvido' => 'S']);
        
        Cartao::where('id', $cliente->fk_cartao)->update(['fk_situacao' => 1]);
    }

    public static function validaCartao($codigo, $id=null)
    {
        $cartao = Cartao::where('codigo', $codigo)->first();
        
        if(!$cartao){
            self::$error = "Cartão inválido.";
            return false;
        }

        if(!$id) { //validação no cadastro
            if($cartao->fk_situacao == 2){
                self::$error = "Este cartão já está em uso. Por favor, passe outro cartão.";
                return false;
            }
        }
        else { //validação somente na alteração
            $cliente = ClienteDB::find($id);

            if($cliente->codigo != $codigo) { //verifica se o cartão do cliente no BD != do informado no form
                if($cartao->fk_situacao == 2){
                    self::$error = "Este cartão já está em uso. Por favor, passe outro cartão.";
                    return false;
                }
            }
        }
            
        return $cartao;
    }

    public static function processarRecarga($cartaoCliente, $productName, $price, $currency = 'brl')
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $unitAmount = (int) formatarMoedaDB($price); // preço em centavos (ex: 500 = $5.00)
        

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $productName,
                    ],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'payment_method_types' => ['card', 'boleto'],
            'mode' => 'payment',
            'success_url' => url('cliente/recarga/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('cliente/recarga/cancel'),
        ]);


        //Lógica para processar a recarga, como criar uma entrada de crédito e atualizar o saldo do cartão
        // EntradaCredito::create([
        //     'fk_cartao_cliente' => $cartaoCliente,
        //     'valor' => $price,
        //     'fk_tipo_pagamento' => 1,
        //     'observacao' => 'Recarga de crédito',
        //     'data' => date('Y-m-d H:i:s'),
        //     'fk_usuario' => Auth::user()->id
        // ]);

        // //Atualiza o saldo do cartão do cliente
        // $cartaoCliente->valor_atual += $price;
        // $cartaoCliente->save();


        return $checkout_session;
    }

    public static function atualizarSaldoAposRecarga($session_id)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($session_id);

        try {

            if ($session->payment_status === 'paid') {
                // Atualizar o saldo do cartão do cliente
                $cartaoCliente = CartaoCliente::find(session('cliente')->id);
                if ($cartaoCliente) {
                    $cartaoCliente->valor_atual += $session->amount_total / 100;
                    $cartaoCliente->desabilitarLog();
                    $cartaoCliente->save();
                }


                $entradaCredito = new EntradaCredito();
                $entradaCredito->fk_cartao_cliente = session('cliente')->id;
                $entradaCredito->valor = $session->amount_total / 100;
                $entradaCredito->fk_tipo_pagamento = 1;
                $entradaCredito->observacao = 'Recarga de crédito pelo aluno';
                $entradaCredito->data = date('Y-m-d H:i:s');
                $entradaCredito->fk_usuario = 999; // ID do usuário fictício para recarga pelo aluno
                $entradaCredito->desabilitarLog();
                $entradaCredito->save();

            } else {
                throw new Exception('O pagamento não foi concluído. Por favor, tente novamente. ');
            }
            
        } catch (Exception $e) {
            // Log do erro ou tratamento adicional
            throw new Exception('Erro ao atualizar saldo do cartão: ' . $e->getMessage());
        }   
    }

}
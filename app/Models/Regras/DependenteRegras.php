<?php

namespace App\Models\Regras;

use App\Models\Entity\Cartao;
use App\Models\Entity\CartaoCliente;
use App\Models\Entity\Cliente;
use App\Models\Entity\Dependente;
use App\Models\Entity\EntradaCredito;
use App\Models\Facade\DependenteDB;
use Exception;
use Illuminate\Support\Facades\Auth;

class DependenteRegras
{
    private static $error = null;
    
    public static function salvar($p)
    {
        $p->cartao = self::validaCartao($p->codigo);

        if(!$p->cartao) throw new Exception(self::$error);

        $dependente = self::saveDependente($p);
        $p->cartaoCliente = self::saveNovoCartaoDependente($p, $dependente);
        Dependente::where('id', $dependente->id)->update(['fk_cartao_cliente' => $p->cartaoCliente->id]);
        self::saveEntradaCreditoRefCaucao($p);
    }

    public static function alterar($p)
    {
        $p->cartao = self::validaCartao($p->codigo, $p->id);

        if(!$p->cartao) throw new Exception(self::$error);
        
        $dependente = self::saveDependente($p);
        
        //verifica se cartão do dependente no BD != ao submetido do Formulário
        if($dependente->fk_cartao != $p->cartao->id) {
            self::saveDevolucao($dependente);
            $p->cartaoCliente = self::saveNovoCartaoDependente($p, $dependente);
            self::saveEntradaCreditoRefCaucao($p);
        }else {
            self::editaCartaoDependente($p, $dependente);
        }
    }

    
    public static function saveDependente($p)
    {
        //INSERT
        if(!$p->id) {
            $dependente                 = new Dependente();
            $dependente->status         = 1;
            $dependente->created_at     = date('Y-m-d H:i:s');
            $dependente->fk_usuario     = Auth::user()->id;
        } 
        
        //UPDATE
        else {
            $dependente                 = Dependente::find($p->id);            
            $dependente->updated_at     = date('Y-m-d H:i:s');
            $dependente->fk_usuario_alt = Auth::user()->id;
        }

        $dependente->fk_cliente         = $p->id_cliente;
        $dependente->nome               = $p->nomeDependente;
        $dependente->cpf                = preg_replace('/[^0-9]/', '', $p->cpfDependente);
        $dependente->telefone           = $p->telefoneDependente;
        $dependente->fk_grau_parentesco = $p->grauParentesco;
        $dependente->save();

        return $dependente;
    }

    public static function ativar($id)
    {
        Dependente::where('id', $id)->update(['status' => 1]);
    }

    public static function inativar($id)
    {
        Dependente::where('id', $id)->update(['status' => 0]);
    }

    public static function saveNovoCartaoDependente($p, $dependente)
    {
        $titular = Cliente::find($p->id_cliente);

        $cartaoCliente = CartaoCliente::create([
            'fk_cartao' => $p->cartao->id,
            'fk_cliente_titular' => $p->id_cliente,
            'fk_dependente' => $dependente->id,
            'nome' => $p->nomeDependente,
            'cpf' => preg_replace('/[^0-9]/', '', $p->cpfDependente),
            'telefone' => $p->telefoneDependente ?? null,
            'fk_tipo_cliente' => $p->tipo,
            'fk_tipo_pagamento' => $p->tipoPagamento,
            'valor_atual' => 0,
            'valor_cartao' => formatarMoedaDB($p->valorCartao),
            'observacao' => 'Dependente do titular '.$titular->nome,
            #'observacao' => $p->observacao ?? null,
            'devolvido' => 'N',
            'status' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'fk_usuario' => Auth::user()->id,
        ]);

    
        Cartao::where('id', $p->cartao->id)->update(['fk_situacao' => 2]);

        return $cartaoCliente;
    }

    public static function editaCartaoDependente($p, $dependente)
    {
        $titular = Cliente::find($p->id_cliente);

        $cartaoCliente = CartaoCliente::find($dependente->fk_cartao_cliente);
        $cartaoCliente->fk_cartao = $p->cartao->id;
        $cartaoCliente->fk_cliente_titular = $p->id_cliente;
        $cartaoCliente->nome = $p->nomeDependente;
        $cartaoCliente->cpf = $p->cpfDependente ? preg_replace('/[^0-9]/', '', $p->cpfDependente) : null;
        $cartaoCliente->telefone = $p->telefoneDependente ?? null;
        $cartaoCliente->fk_tipo_cliente = $p->tipo;
        $cartaoCliente->observacao = 'Dependente do titular '.$titular->nome;
        #$cartaoCliente->observacao = $p->observacao ?? null;
        $cartaoCliente->updated_at = date('Y-m-d H:i:s');
        $cartaoCliente->save();
    }

    public static function saveEntradaCreditoRefCaucao($p)
    {
        EntradaCredito::create([
            'fk_cartao_cliente' => $p->cartaoCliente->id,
            'valor' => formatarMoedaDB($p->valorCartao),
            'fk_tipo_pagamento' => $p->tipoPagamento,
            'observacao' => 'Crédito Cartão Dependente: '.$p->nomeDependente,
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
            self::$error = "Falha na leitura do cartão.";
            return false;
        }

        if(!$id) { //validação no cadastro
            if($cartao->fk_situacao == 2){
                self::$error = "Este cartão já está em uso. Por favor, passe outro cartão.";
                return false;
            }
        }
        else { //validação somente na alteração
            $dependente = DependenteDB::find($id);

            if($dependente->codigo != $codigo) { //verifica se o cartão do dependente no BD != do informado no form
                if($cartao->fk_situacao == 2){
                    self::$error = "Este cartão já está em uso. Por favor, passe outro cartão.";
                    return false;
                }
            }
        }
            
        return $cartao;
    }

}
<?php

namespace App\Http\Controllers;

use App\Models\Facade\CartaoClienteDB;
use App\Models\Facade\FinanceiroDB;
use App\Models\Regras\FinanceiroRegras;
use DateTime;
use Illuminate\Http\Request;

class PortariaController extends Controller
{
    public function index()
    {
        return view('portaria.index');
    }

    public function validarEntrada()
    {
        $codigo = request('codigo');
        $cpf    = request('cpf');

        if($codigo || $cpf) {

            $diasEmAtraso = 0;

            $cartaoCliente = CartaoClienteDB::getCartaoClienteByCodigo($codigo, $cpf);

            if(!$cartaoCliente){
                return response()->json(['error' => "Cliente não encontrado"], 500);
            }

            $existePagamento = FinanceiroDB::getUltimaCobrancaPaga($cartaoCliente->fk_cliente_titular);

            if(!$existePagamento) {
                return response()->json(
                    [
                        'error' => "Não há registros de pagamento deste cliente.<br />
                        <b><a href='".url('financeiro/create/'.$cartaoCliente->fk_cliente_titular)."'>Clique aqui</a></b> para registrar o pagamento."
                    ], 500);
            }

            $emAtraso = FinanceiroRegras::verificaAtraso($cartaoCliente->fk_cliente_titular);
            
            if($emAtraso){
                
                $diasEmAtraso = FinanceiroRegras::getDiasEmAtraso();

                $cartaoCliente->proximaDataAPagar = FinanceiroRegras::getProximaDataAPagarFormat();

                if($diasEmAtraso > config('policia.limite_dias_em_atraso')){
                    return response()->json(
                        [
                            'error' => "Mensalidade em atraso a <b>$diasEmAtraso dias</b>. Vencimento em <b>".$cartaoCliente->proximaDataAPagar."</b>.
                            <b><a href='".url('financeiro/create/'.$cartaoCliente->fk_cliente_titular)."'>Clique aqui</a></b> para registrar o pagamento."
                        ], 500);
                }

                return response()->json(
                    [
                        'info' => "Mensalidade em atraso a <b>$diasEmAtraso dias</b>. Vencimento em <b>".$cartaoCliente->proximaDataAPagar."</b>.
                        <b><a href='".url('financeiro/create/'.$cartaoCliente->fk_cliente_titular)."'>Clique aqui</a></b> para registrar o pagamento."
                    ]);
            }
        }

        return response()->json(['success' => 'Entrada Liberada']);
    }

    

}

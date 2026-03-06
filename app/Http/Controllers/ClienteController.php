<?php

namespace App\Http\Controllers;

use App\Models\Entity\Cardapio;
use App\Models\Entity\CardapioFoto;
use App\Models\Entity\CardapioTipo;
use App\Models\Entity\Cartao;
use App\Models\Entity\CartaoCliente;
use App\Models\Entity\Cliente;
use App\Models\Entity\GrauParentesco;
use App\Models\Facade\CardapioDB;
use App\Models\Facade\CartaoClienteDB;
use App\Models\Facade\ClienteDB;
use App\Models\Facade\DependenteDB;
use App\Models\Facade\EscolaDB;
use App\Models\Facade\FormasPagamentoDB;
use App\Models\Regras\ClienteRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function list(Request $request)
    {
        $texto = $request->texto != "{texto" ? $request->texto : '';
        $clientes = ClienteDB::grid($texto);
        return view('cliente.clientes', compact('clientes', 'texto'));
    }

    public function create()
    {
        $escolas = EscolaDB::ativas();
        $tiposCliente = ClienteDB::tiposDeCliente();
        $formaPagamento = FormasPagamentoDB::listar();
        return view('cliente.create', compact('escolas', 'tiposCliente', 'formaPagamento'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'escola' => 'required',
            'nome' => 'required',
            'cpf' => 'required',
            'telefone' => 'required',
            'codigo' => 'required',
            'diaVencimento' => 'required'
        ]);


        try {
            if(!$request->id){
                $cliente = ClienteRegras::salvar($request);
            }else{
                $cliente = ClienteRegras::alterar($request);
            }

            return redirect('cliente/edit/'.$cliente->id)->with('sucesso', 'Dados do Cliente salvos com sucesso.');
        }
        catch (Exception $ex){
            return redirect($_SERVER['HTTP_REFERER'])->with('error', $ex->getMessage())->withInput();
        }
    }

    public function index()
    {
        return view('cliente.index');
    }

    public function editar($id)
    {
        $cliente        = Cliente::find($id);
        $escolas        = EscolaDB::ativas();
        $cartao         = Cartao::find($cliente->fk_cartao);
        $tiposCliente   = ClienteDB::tiposDeCliente();
        $formaPagamento = FormasPagamentoDB::listar();
        $grauParentesco = GrauParentesco::all();
        $dependentes    = DependenteDB::todos($id);

        return view('cliente.edit', compact('cliente', 'grauParentesco', 'cartao', 'escolas', 'tiposCliente', 'formaPagamento', 'dependentes'));
    }

    public function home()
    {
        $cartaoCliente = session('cliente');
        return view('cliente.home', compact('cartaoCliente'));
    }

    public function saldo()
    {
        $cartaoCliente = session('cliente');
        return view('cliente.saldo', compact('cartaoCliente'));
    }

    public function pedidos()
    {
        #$cpf = preg_replace('/[^0-9]/', '', request('cpf'));
        $id_cartao_cliente = session('cliente')->id;

        $dtInicio  = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));
        $dtTermino = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));
        $horaInicio  = (request('horaInicio') ? request('horaInicio') : date('00:00'));
        $horaTermino = (request('horaTermino') ? request('horaTermino') : date('H:i'));
        

        $pedidos = DB::table('pedido as p')
                    ->join('pedido_item as pi', 'pi.fk_pedido', '=', 'p.id')
                    ->join('cardapio as c', 'c.id', '=', 'pi.fk_item_cardapio')
                    ->join('cardapio_tipo as t', 't.id', '=', 'c.fk_tipo_cardapio')
                    ->join('cartao_cliente as cc', 'p.fk_cartao_cliente', '=', 'cc.id')
                    ->select([
                        'p.id',
                        't.nome as tipo_cardapio',
                        'cc.nome',
                        'c.nome_item',
                        'pi.observacao',
                        'c.valor as valor_item',
                        'pi.quantidade',
                        'pi.valor as valor_total_item',
                        'pi.status',
                        'p.valor_total',
                        'p.taxa_servico',
                        'p.dt_pedido',
                        'p.dt_pronto',
                        'p.dt_entrega'
                    ])
                    ->where('p.dt_pedido', '>=', "$dtInicio $horaInicio")
                    ->where('p.dt_pedido', '<=', "$dtTermino $horaTermino")
                    ->where('cc.id', $id_cartao_cliente)
                    ->orderBy('p.dt_pedido', 'desc')
                    ->get();


        $itensPedidoCliente = [];
        $pedidoCliente = [];

        if($pedidos->count() > 0) {
            foreach($pedidos as $pedido) {
                $itensPedidoCliente[$pedido->id][] = $pedido;

                $pedidoCliente[$pedido->id] = [
                    'id' => $pedido->id,
                    'tipo_cardapio' => $pedido->tipo_cardapio,
                    'nome' => $pedido->nome,
                    'valor_total' => $pedido->valor_total,
                    'taxa_servico' => $pedido->taxa_servico,
                    'status' => $pedido->status,
                    'dt_pedido' => date('d/m/Y', strtotime($pedido->dt_pedido)),
                    'hora_pedido' => date('H:i', strtotime($pedido->dt_pedido)),
                    'hora_pronto' => ($pedido->dt_pronto ? date('H:i', strtotime($pedido->dt_pronto)) : null),
                    'hora_entrega' => ($pedido->dt_entrega ? date('H:i', strtotime($pedido->dt_entrega)) : null),
                ];
            }
        }

        return view('cliente.pedidos', compact('pedidoCliente', 'itensPedidoCliente', 'dtInicio', 'dtTermino', 'horaInicio', 'horaTermino'));
    }

    public function cardapios()
    {
        $tipo_cardapios = CardapioTipo::where('status', 1)->orderBy('nome')->get();        
        return view('cliente.tipos-cardapio', compact('tipo_cardapios'));
    }

    public function cardapio($id_tipo_cardapio)
    {
        $myCardapio = CardapioDB::pesquisar($id_tipo_cardapio);
        return view('cliente.cardapio', compact('myCardapio'));
    }

    public function pedidoItem($id)
    {
        $cardapio = Cardapio::where('id', $id)->first();
        $fotoCardapio = CardapioFoto::where('fk_cardapio', $id)->select(['id'])->first(); 
        $mesa = null;

        if(request()->session()->exists('pedido')){
            $mesa = request()->session()->get('pedido')[0]->mesa;
        }

        return view('cliente.pedido-item', compact('cardapio', 'fotoCardapio', 'mesa'));
    }

    public function verFoto($id)
    {
        $foto = CardapioFoto::where('fk_cardapio', $id)->first();
        header('Content-Type:'.$foto->type);
        exit($foto->foto);
    }

    public function verThumb($id)
    {
        $foto = CardapioFoto::where('fk_cardapio', $id)->first();
        header('Content-Type:'.$foto->type);
        exit($foto->thumbnail);
    }

    public function login($codigo)
    {
        //encontra o cartão independente do status
        $cartao = Cartao::where('codigo', $codigo)->first();

        if($cartao->fk_situacao !== 2) {
            return redirect('cliente')->withInput()
                ->with('error', 'Não foi possível localizar o aluno. Este cartão se encontra <b>'.$cartao->situacao->nome.'</b> e não está habilitado para uso.');
        }

        $cartaoCliente = CartaoCliente::where('fk_cartao', $cartao->id)->where('status', 2)->first();

        if(!isset($cartaoCliente->status) || $cartaoCliente->status !== 2) {
            return redirect('cliente')->withInput()
                ->with('error', 'Não foi possível localizar o aluno. Este cartão não está em uso.');
        }


        if ($cartaoCliente && !request()->session()->exists('cliente')) {
            request()->session()->put('cliente', $cartaoCliente);
        }

        //definir idioma pt-br por padrão
        session(['locale' => 'PT']);

        return redirect('cliente/home');
    }

    public function extrato()
    {
        $dtInicio  = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));
        $dtTermino = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));
        $horaInicio  = (request('horaInicio') ? request('horaInicio') : date('00:00'));
        $horaTermino = (request('horaTermino') ? request('horaTermino') : date('H:i'));
        $cartaoCliente = session('cliente');

        $extrato = CartaoClienteDB::extratoCartaoCliente($cartaoCliente->id, $dtInicio, $dtTermino, $horaInicio, $horaTermino);

        return view('cliente.extrato', compact('extrato', 'dtInicio', 'dtTermino', 'horaInicio', 'horaTermino'));
    }

    public function recarga()
    {
        return view('cliente.recarga');
    }

    public function recargaStore(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:1',
            'product_name' => 'required|string'
        ]);

        try {
            $checkout_session = ClienteRegras::processarRecarga(session('cliente'), $request->product_name, $request->price);

            return redirect($checkout_session->url);
        } catch (Exception $ex) {
            return redirect('cliente/recarga')->with('error', $ex->getMessage())->withInput();
        }
    }

    public function logout()
    {
        request()->session()->forget('cliente');
        return redirect('cliente');
    }


    //apis
    public function listarCliente()
    {
        return response()->json(ClienteDB::todos());
    }

}

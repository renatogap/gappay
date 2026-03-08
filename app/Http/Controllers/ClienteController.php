<?php

namespace App\Http\Controllers;

use App\Models\Entity\Cardapio;
use App\Models\Entity\CardapioFoto;
use App\Models\Entity\CardapioTipo;
use App\Models\Entity\Cartao;
use App\Models\Entity\CartaoCliente;
use App\Models\Entity\Cliente;
use App\Models\Entity\Estoque;
use App\Models\Entity\GrauParentesco;
use App\Models\Entity\Pedido;
use App\Models\Entity\SituacaoCartao;
use App\Models\Facade\CardapioDB;
use App\Models\Facade\CartaoClienteDB;
use App\Models\Facade\ClienteDB;
use App\Models\Facade\DependenteDB;
use App\Models\Facade\EscolaDB;
use App\Models\Facade\EstoqueDB;
use App\Models\Facade\FormasPagamentoDB;
use App\Models\Regras\ClienteRegras;
use App\Models\Regras\PedidoRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use QRcode;

class ClienteController extends Controller
{
    public function index()
    {
        return view('cliente.index');
    }

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
        
        $pedidosPendentes = Pedido::where('fk_cartao_cliente', $cartaoCliente->id)
            ->where('status', 1) // status que representa pedido não finalizado
            ->count();

        return view('cliente.home', compact('cartaoCliente', 'pedidosPendentes'));
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
        //$myCardapio = CardapioDB::pesquisar($id_tipo_cardapio);
        $id_tipo_cardapio = 1;
        return view('cliente.cardapio', compact('id_tipo_cardapio'));
    }

    public function getCardapioDoPDV($id_tipo_cardapio)
    {
        $cardapio = CardapioDB::pesquisar($id_tipo_cardapio);
        return response()->json($cardapio, 200);
    }

    public function addPedidoCliente(Request $request)
    {
        $sessao = [];

        if ($request->session()->exists('pedido')) {
            $sessao = $request->session()->get('pedido');
        }

        $params = (object) $request->all();

        #Esse script foi inserido porque algumas vezes o javascript falhava na tela do usuário
        #Não calculando corretamente o valor total do pedido (valor * qtd)
        #Logo, esse calculo deve ser feito também aqui na regra de negócio
        #Validando os valores, e caso não bata, o php substitui o valor gerado errado
        if ($params->unidade == 1) {
            $valorTotalItem = ($params->quantidade * $params->valorCardapio);
        } else {
            //calculo caso a unidade seja em gramas, onde o valor do cardápio é por kg
            $valorTotalItem = (($params->quantidade * $params->valorCardapio) / $params->unidade);
        }

        if (!isset($params->valor)) {
            $params->valor = 0;
        }

        if ($valorTotalItem != $params->valor) {
            $params->valor = $valorTotalItem;
        }

        $sessao[$params->id_cardapio] = $params;

        session(["pedido" => $sessao]);

        return response()->json(['success' => true, 'message' => 'Item adicionado ao pedido com sucesso!'], 200);
    }

    public function removeItemPedidoCliente(Request $request)
    {
        $pedido = request()->session()->get('pedido');

        if (isset($pedido[$request->itemId])) {

            if ($request->quantidade <= 0) {
                unset($pedido[$request->itemId]);
            } else {
                $pedido[$request->itemId]->quantidade = $request->quantidade;
            }

            request()->session()->put('pedido', $pedido);
        }

        return response()->json(['success' => true, 'message' => "Item {$request->itemId} removido do pedido!"], 200);
    }

    public function confirmarPedido()
    {
        $pedido = request()->session()->get('pedido');

        if (isset($pedido[request('remove')])) {

            unset($pedido[request('remove')]);


            if (count($pedido) > 0) {
                session(["pedido" => $pedido]);
                redirect('cliente/confirmar-pedido');
            } else {
                request()->session()->forget('pedido');
                return redirect('cliente/cardapio/1');
            }
        }

        return view('cliente.confirmar-pedido', compact('pedido'));
    }

    public function finalizarPedido()
    {
        $cartao_id = session('cliente')->fk_cartao;

        $params = new \StdClass();

        //verifica se o pedido ainda está na sessão
        if (!request()->session()->exists('pedido')) {
            return redirect('cliente/cardapio/1')->with('error', 'Não há pedidos registrados no momento.');
        }

        $params->cartao = Cartao::where('id', $cartao_id)->first();

        //pega o pedido da sessão e armazena na variável
        $params->pedidoCliente = request()->session()->get('pedido');


        //verifica falha de leitura do cartão
        if (!$params->cartao) {
            return redirect('cliente/confirmar-pedido')->with('error', 'Não foi possível ler o QR Code do cartão, tente novamente.');
        }

        $situacao = SituacaoCartao::find($params->cartao->fk_situacao);

        //verifica se o cartão está ativo
        if ($params->cartao->fk_situacao !== 2) {
            return redirect('cliente/confirmar-pedido')->with('error', 'Não foi possível finalizar o pedido. Este cartão se encontra <b>' . $situacao->nome . '</b> e não está habilitado para uso.');
        }

        //pega o cartão ativo para o cliente
        $params->cartaoCliente = CartaoCliente::where('fk_cartao', $cartao_id)->where('status', 2)->first();

        //verifica se o cartão do cliente foi encontrado
        if (!$params->cartaoCliente) {
            return redirect('cliente/confirmar-pedido')->with('error', 'Não foi possível localizar o cartão do cliente. Tente novamente.');
        }

        //verifica se o cartão do cliente está ativo
        if ($params->cartaoCliente->status != 2) {
            return redirect('cliente/confirmar-pedido')->with('error', 'Não foi possível finalizar o pedido. Este cartão não está habilitado para uso.');
        }


        //Validação do ESTOQUE
        $msgErro = [];

        
        foreach ($params->pedidoCliente as $item) {

            //primeiro verifica se o produto esta ativo no estoque
            $estoqueItem = Estoque::where('fk_item_cardapio', $item->id_cardapio)->first();

            
            //caso o produto esteja ativo, valida o saldo em estoque
            if ($estoqueItem) {

                $saldoAtualDoProduto = EstoqueDB::saldoEstoqueProdutoCardapio($item->id_cardapio);

                if ($saldoAtualDoProduto < $item->quantidade) {
                    $cardapio = Cardapio::find($item->id_cardapio);
                    $msgErro[] = '* ' . $cardapio->nome_item . ' insuficiente. Quantidade no estoque: <b>' . $saldoAtualDoProduto . '</b>';
                }
            }
        }

        if (count($msgErro) > 0) {
            return redirect('cliente/confirmar-pedido')->with('error', implode('<br>', $msgErro));
        }
        //End validação Estoque


        $params->valorTotalPedido = array_sum(array_column($params->pedidoCliente, 'valor'));

        $params->taxaServico = 0;

        //verifica se existe saldo no cartão para finalizar o pedido
        if ($params->cartaoCliente->valor_atual < $params->valorTotalPedido) {
            return redirect('cliente/confirmar-pedido')
                ->with('error', 'Crédito insuficiente no cartão. O saldo atual é de: <b>R$ ' . $params->cartaoCliente->valor_atual . '</b>');
        }


        DB::beginTransaction();

        try {

            //Regras
            PedidoRegras::salvarPedido($params);

            request()->session()->forget('pedido');

            DB::commit();
            return view('cliente.pedido-finalizado', compact('params'));
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect('cliente/confirmar-pedido')->with('error', '<b>Atenção, algo aconteceu!</b><br>' . $ex->getMessage());
        }
    }

    public function meusPedidos()
    {
        $cartaoCliente = session('cliente');

        $pedidos = Pedido::where('fk_cartao_cliente', $cartaoCliente->id)
            ->where('status', '=', 1)
            ->orderBy('dt_pedido', 'desc')
            ->get();

        return view('cliente.meus-pedidos', compact('pedidos'));
    }

    public function meuPedido($pedido_id)
    {
        $pedidos = DB::table('pedido as p')
            ->join('pedido_item as pi', 'p.id', '=', 'pi.fk_pedido')
            ->join('cardapio as c', 'c.id', '=', 'pi.fk_item_cardapio')
            ->join('cardapio_tipo as t', 't.id', '=', 'c.fk_tipo_cardapio')
            ->join('cardapio_categoria as cc', 'cc.id', '=', 'c.fk_categoria')
            ->join('situacao_pedido as s', 's.id', '=', 'p.status')
            ->join('cartao_cliente as ccl', 'p.fk_cartao_cliente', '=', 'ccl.id')
            ->select([
                'p.id',
                't.nome as tipo_cardapio',
                'p.mesa',
                'p.dt_pedido',
                'p.taxa_servico',
                'p.valor_total',
                'p.status as status_pedido',
                's.nome as situacao',
                'c.fk_tipo_cardapio',
                'c.nome_item',
                'c.valor as valor_unit',
                'c.unid',
                'cc.nome as categoria',
                'pi.id as id_item_pedido',
                'pi.quantidade',
                'pi.valor as valor_total_item',
                'pi.observacao',
                'pi.status',
                'pi.dt_pronto',
                'p.fk_usuario',
                'p.fk_cartao_cliente',
                'ccl.nome as nome_cliente'
            ])
            ->where('p.id', $pedido_id)
            ->where('p.status', '=', 1) //SOLICITADO
            ->get();

        // Gerar QR Code
        include_once 'lib/phpqrcode/qrlib.php';

        $qrCodeUrl = url('cliente/pedido/' . $pedido_id . '/entregue');
        $qrCodePath = storage_path() . '/qrcode/qrcode.png';

        //Gera a imagem qrcode baseado na url e salva na pasta qrcode do storage
        QRcode::png($qrCodeUrl, $qrCodePath, QR_ECLEVEL_H, 5, 1);

        // Converter para base64 para embedding direto
        $qrCodeData = base64_encode(file_get_contents($qrCodePath));
        $qrCode = '<img src="data:image/png;base64,' . $qrCodeData . '" class="ticket-qrcode-img" alt="QR Code">';

        return view('cliente.meu-pedido', compact('pedidos', 'qrCode'));
    }

    public function entregarPedido($pedido_id)
    {
        try {
            $pedido = Pedido::find($pedido_id);

            if (!$pedido) {
                return response()->json([
                    'sucesso' => false,
                    'mensagem' => 'Pedido não encontrado'
                ], 404);
            }

            // Atualizar status para 3 (entregue) e registrar data/hora de entrega
            $pedido->status = 3;
            $pedido->dt_entrega = now();
            $pedido->save();

            return response()->json([
                'sucesso' => true,
                'mensagem' => 'Pedido entregue com sucesso!',
                'redirect' => url('cliente/meus-pedidos')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao entrega o pedido: ' . $e->getMessage()
            ], 500);
        }
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

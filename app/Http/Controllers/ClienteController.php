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
use App\Models\Entity\Responsavel;
use App\Models\Entity\Aluno;
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
use App\Mail\ResponsavelTokenMail;
use App\Models\Entity\EntradaCredito;
use App\Models\Entity\SaidaCredito;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use QRcode;

class ClienteController extends Controller
{
    //Antiga tela de login - redireciona para o login do responsavel
    //Futuramente pode ser removida
    public function index()
    {
        return redirect()->route("tela.login.responsavel");
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
            if (!$request->id) {
                $cliente = ClienteRegras::salvar($request);
            } else {
                $cliente = ClienteRegras::alterar($request);
            }

            return redirect('cliente/edit/' . $cliente->id)->with('sucesso', 'Dados do Cliente salvos com sucesso.');
        } catch (Exception $ex) {
            return redirect($_SERVER['HTTP_REFERER'])->with('error', $ex->getMessage())->withInput();
        }
    }

    public function editar($id)
    {
        $cliente = Cliente::find($id);
        $escolas = EscolaDB::ativas();
        $cartao = Cartao::find($cliente->fk_cartao);
        $tiposCliente = ClienteDB::tiposDeCliente();
        $formaPagamento = FormasPagamentoDB::listar();
        $grauParentesco = GrauParentesco::all();
        $dependentes = DependenteDB::todos($id);

        return view('cliente.edit', compact('cliente', 'grauParentesco', 'cartao', 'escolas', 'tiposCliente', 'formaPagamento', 'dependentes'));
    }

    public function home()
    {
        $cartaoCliente = session('cliente');
        $responsavel = session('responsavel'); // Responsável do cartão cliente (aluno)

        $alunos = CartaoCliente::where('responsavel_id', $responsavel->id)
            ->where('status', 2) // Cartão em uso ativo
            ->get(); // Todos os alunos vinculados ao responsável

        if ($alunos->isEmpty()) {
            session(['responsavel' => $responsavel]);
            return redirect()->route('tela.cadastro.aluno');
        }

        //Saldo do Cartão Cliente (aluno)
        session('cliente')->valor_atual = (CartaoCliente::where('id', $cartaoCliente->id)->first()->valor_atual);

        $pedidosPendentes = Pedido::where('fk_cartao_cliente', $cartaoCliente->id)
            ->where('status', 1) // status que representa pedido não finalizado
            ->count();

        return view('cliente.home', compact('cartaoCliente', 'pedidosPendentes', 'responsavel', 'alunos'));
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

        $dtInicio = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));
        $dtTermino = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));
        $horaInicio = (request('horaInicio') ? request('horaInicio') : date('00:00'));
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

        if ($pedidos->count() > 0) {
            foreach ($pedidos as $pedido) {
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
            $pedido = PedidoRegras::salvarPedido($params);

            request()->session()->forget('pedido');

            DB::commit();
            return redirect('cliente/meu-pedido/' . $pedido->id)->with('sucesso', 'Pedido finalizado com sucesso!');
            //return view('cliente.pedido-finalizado', compact('params'));
        } catch (Exception $ex) {
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

        if ($pedidos->count() == 0) {
            return redirect('cliente/meus-pedidos')->with('error', 'Pedido não encontrado ou já foi finalizado.');
        }

        // Gerar QR Code
        include_once 'lib/phpqrcode/qrlib.php';

        $qrCodeUrl = url('pedido/salvar-entrega/via-qrcode/' . $pedido_id);
        $qrCodePath = storage_path() . '/qrcode/qrcode.png';

        //Gera a imagem qrcode baseado na url e salva na pasta qrcode do storage
        QRcode::png($qrCodeUrl, $qrCodePath, QR_ECLEVEL_H, 5, 1);

        // Converter para base64 para embedding direto
        $qrCodeData = base64_encode(file_get_contents($qrCodePath));
        $qrCode = '<img src="data:image/png;base64,' . $qrCodeData . '" class="ticket-qrcode-img" alt="QR Code">';

        return view('cliente.meu-pedido', compact('pedidos', 'qrCode'));
    }

    // public function entregarPedido($pedido_id)
    // {
    //     if(!Auth::check()) {
    //         return redirect()->route('tela.login')->with('error', 'Faça primeiro login no app, depois volte e leia o QR Code novamente.');
    //     }

    //     try {
    //         $pedido = Pedido::where('id', $pedido_id)->where('status', 1) //Solicitado
    //             ->first();

    //         if (!$pedido) {
    //             redirect('pedido/visualizacao-gerente')->with('error', 'Este pedido já foi entregue.');
    //         }

    //         // Atualizar status para 3 (entregue) e registrar data/hora de entrega
    //         $pedido->status = 3;
    //         $pedido->dt_entrega = now();
    //         $pedido->save();

    //         return response()->json([
    //             'sucesso' => true,
    //             'mensagem' => 'Pedido entregue com sucesso!',
    //             'redirect' => url('cliente/meus-pedidos')
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'sucesso' => false,
    //             'mensagem' => 'Erro ao entrega o pedido: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function pedidoItem($id)
    {
        $cardapio = Cardapio::where('id', $id)->first();
        $fotoCardapio = CardapioFoto::where('fk_cardapio', $id)->select(['id'])->first();
        $mesa = null;

        if (request()->session()->exists('pedido')) {
            $mesa = request()->session()->get('pedido')[0]->mesa;
        }

        return view('cliente.pedido-item', compact('cardapio', 'fotoCardapio', 'mesa'));
    }

    public function verFoto($id)
    {
        $foto = CardapioFoto::where('fk_cardapio', $id)->first();
        header('Content-Type:' . $foto->type);
        exit($foto->foto);
    }

    public function verThumb($id)
    {
        $foto = CardapioFoto::where('fk_cardapio', $id)->first();
        header('Content-Type:' . $foto->type);
        exit($foto->thumbnail);
    }

    public function login($codigo)
    {
        //encontra o cartão independente do status
        $cartao = Cartao::where('codigo', $codigo)->first();

        if ($cartao->fk_situacao !== 2) {
            return redirect('cliente')->withInput()
                ->with('error', 'Não foi possível localizar o aluno. Este cartão se encontra <b>' . $cartao->situacao->nome . '</b> e não está habilitado para uso.');
        }

        $cartaoCliente = CartaoCliente::where('fk_cartao', $cartao->id)->where('status', 2)->first();

        if (!isset($cartaoCliente->status) || $cartaoCliente->status !== 2) {
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
        $dtInicio = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));
        $dtTermino = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));
        $horaInicio = (request('horaInicio') ? request('horaInicio') : date('00:00'));
        $horaTermino = (request('horaTermino') ? request('horaTermino') : date('H:i', strtotime('+1 minute')));
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
            'product_name' => 'required|string',
            'price_recarga' => 'required|numeric|min:1'
        ]);

        try {
            $checkout_session = ClienteRegras::processarRecarga(session('cliente'), $request->product_name, $request->price, $request->price_recarga);

            // Armazena na sessão os dados da recarga para recuperar no retorno de forma segura
            session([
                'stripe_checkout_session_id' => $checkout_session->id,
                'stripe_price_recarga' => $request->price_recarga
            ]);

            return redirect($checkout_session->url);
        } catch (Exception $ex) {
            return redirect('cliente/recarga')->with('error', $ex->getMessage())->withInput();
        }
    }

    public function recargaSuccess(Request $request)
    {
        $session_id = session('stripe_checkout_session_id');
        $price_recarga = session('stripe_price_recarga');

        if (!$session_id) {
            return redirect('cliente/recarga')->with('error', 'Sessão de pagamento não encontrada ou já processada.');
        }

        try {

            ClienteRegras::atualizarSaldoAposRecarga($session_id, $price_recarga);

            // Limpa as variáveis de sessão para evitar reprocessamento em caso de refresh
            session()->forget(['stripe_checkout_session_id', 'stripe_price_recarga']);

            return redirect('cliente/extrato')->with('sucesso', 'Recarga realizada com sucesso! O valor já está disponível no seu cartão.');
        } catch (Exception $ex) {
            return redirect('cliente/recarga')->with('error', 'Erro ao processar a recarga: ' . $ex->getMessage());
        }
    }

    public function recargaCancel()
    {
        return redirect('cliente/recarga')->with('error', 'Recarga cancelada. Nenhuma cobrança foi realizada.');
    }

    public function logout()
    {
        request()->session()->forget('cliente');
        request()->session()->forget('responsavel');
        return redirect('cliente/login');
    }


    //apis
    public function listarCliente()
    {
        return response()->json(ClienteDB::todos());
    }

    public function loginResponsavelTela()
    {
        return view('cliente.login-responsavel');
    }

    public function loginResponsavel(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string'
        ]);

        $responsavel = Responsavel::where('email', $request->email)->first();

        if (!$responsavel || !Hash::check($request->senha, $responsavel->senha)) {
            return redirect()->back()->with('error', 'E-mail ou senha inválidos.')->withInput();
        }

        if (!$responsavel->validado) {
            return redirect()->back()->with('error', 'E-mail ainda não validado. Verifique sua caixa de entrada para validar o e-mail antes de fazer login.')->withInput();
        }

        $alunos = CartaoCliente::where('responsavel_id', $responsavel->id)
            ->where('status', 2) // Cartão em uso
            ->get();

        if ($alunos->isEmpty()) {
            session(['responsavel' => $responsavel]);
            return redirect()->route('tela.cadastro.aluno');
        }

        session([
            'responsavel' => $responsavel,
            'cliente' => $alunos->first(),
        ]);

        return redirect('cliente/home');
    }

    public function cadastro(Request $request)
    {
        $step = intval($request->query('step', 1));
        $step = in_array($step, [1, 2, 3]) ? $step : 1;

        return view('cliente.cadastro-cliente', compact('step'));
    }

    public function cadastroStore(Request $request)
    {
        $step = intval($request->input('step', 1));

        if ($step === 1) {
            $request->validate([
                'email' => 'required|email',
            ]);

            try {
                $responsavel = Responsavel::where('email', $request->email)->first();

                if (!$responsavel) {
                    $responsavel = new Responsavel();
                    $responsavel->email = $request->email;
                    $responsavel->token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    $responsavel->save();
                }

                if ($responsavel->validado && CartaoCliente::where('responsavel_id', $responsavel->id)->where('status', 2)->exists()) {
                    return redirect()->route('tela.login.responsavel')->with('success', 'E-mail já cadastrado. Faça login para acessar sua conta.');
                }

                if ($responsavel->validado) {
                    return redirect()->route('tela.cadastro', [
                        'step' => 3,
                        'email' => $responsavel->email,
                        'responsavel_id' => $responsavel->id,
                    ])->with('success', 'E-mail validado. Agora complete os dados pessoais do responsável.');
                }

                Mail::to($responsavel->email)->send(new ResponsavelTokenMail($responsavel));
            } catch (Exception $ex) {
                return redirect()->back()->with('error', 'Erro ao enviar o token por e-mail: ' . $ex->getMessage())->withInput();
            }

            return redirect()->route('tela.cadastro', ['step' => 2, 'email' => $responsavel->email, 'responsavel_id' => $responsavel->id])
                ->with('success', 'Token enviado por e-mail. Verifique a caixa de entrada.');
        }

        if ($step === 2) {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
            ]);

            $responsavel = Responsavel::where('email', $request->email)
                ->where('token', $request->token)
                ->update([
                    'token' => null,
                    'validado' => true
                ]);

            $responsavel = Responsavel::where('email', $request->email)->first();

            if (!$responsavel) {
                return redirect()->back()->with('error', 'E-mail ou token inválido.')->withInput();
            }

            $responsavel->validado = true;
            $responsavel->save();

            return redirect()->route('tela.cadastro', ['step' => 3, 'email' => $responsavel->email, 'responsavel_id' => $responsavel->id])
                ->with('success', 'E-mail validado. Agora complete os dados pessoais do responsável.');
        }

        if ($step === 3) {
            $request->validate([
                'responsavel_id' => 'required|integer|exists:responsavel,id',
                'nome' => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                    'regex:/^[^\d]+$/',
                    'regex:/^\S{3,}(\s+\S+)*\s+\S{3,}$/',
                ],
                'telefone' => 'required|string|max:20',
                'senha' => 'required|string|min:6|same:confirmar_senha',
                'termos' => 'accepted',
            ], [
                'nome.required' => 'O nome é obrigatório.',
                'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
                'nome.regex' => 'Informe o nome completo do responsável.',
                'senha.same' => 'As senhas não estão iguais.',
                'termos.accepted' => 'Você deve concordar com os termos de uso.',
            ]);

            DB::beginTransaction();
            try {
                $responsavel = Responsavel::find($request->responsavel_id);

                if (!$responsavel || !$responsavel->validado) {
                    return redirect()->back()->with('error', 'Responsável não encontrado ou ainda não validado.')->withInput();
                }

                $responsavel->nome = $request->nome;
                $responsavel->telefone = preg_replace('/\D/', '', $request->telefone);
                $responsavel->senha = Hash::make($request->senha);
                $responsavel->concordo = $request->termos;
                $responsavel->save();

                DB::commit();

                session(['responsavel' => $responsavel]);

                return redirect()->route('tela.cadastro.aluno');
            } catch (Exception $ex) {
                DB::rollback();
                return redirect()->back()->with('error', 'Erro ao finalizar cadastro: ' . $ex->getMessage())->withInput();
            }
        }

        return redirect()->route('tela.cadastro')->with('error', 'Passo de cadastro inválido.');
    }

    public function gridAlunosResponsavel()
    {
        $responsavel = session('responsavel');

        $alunos = CartaoCliente::where('responsavel_id', $responsavel->id)
            ->where('status', 2) // Cartão em uso (ativo)
            ->get();

        $alunos->each(function ($aluno) {
            $partes = explode(' - ', $aluno->nome, 2);
            $aluno->nome = trim($partes[0]);
            $aluno->serie = isset($partes[1]) ? trim($partes[1]) : '';
        });

        return view('cliente.grid-alunos-responsavel', compact('alunos', 'responsavel'));
    }

    public function cadastroAluno()
    {
        $responsavel = session('responsavel');

        return view('cliente.cadastro-aluno', compact('responsavel'));
    }

    public function cadastroAlunoStore(Request $request)
    {
        $request->validate([
            'responsavel_id' => 'required|integer|exists:responsavel,id',
            'alunos' => 'required|array',
            'alunos.*.nome' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[^\d]+$/',
                'regex:/^\S{3,}(\s+\S+)*\s+\S{3,}$/',
            ],
            'alunos.*.serie' => 'required|string|max:50',
        ], [
            'alunos.*.nome.required' => 'O nome do aluno é obrigatório.',
            'alunos.*.nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'alunos.*.nome.regex' => 'Informe o nome completo do aluno (nome e sobrenome, cada um com pelo menos 3 letras).',
            'alunos.*.serie.required' => 'A série do aluno é obrigatória.',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->alunos as $alunoData) {
                //Cadastrar cartão para o aluno
                $codigo = rand(1, 999999) . date('dmyHis');
                $codigo = str_pad($codigo, 15, "0", STR_PAD_RIGHT);

                if (!Cartao::where('codigo', $codigo)->first()) {
                    $cartao = Cartao::create([
                        'codigo' => $codigo,
                        'hash' => md5($codigo),
                        'data' => date('Y-m-d'),
                        'fk_situacao' => 2, //Ativo
                        'cartao_gerado' => 1
                    ]);
                }

                // Cadastrar Aluno
                $aluno = CartaoCliente::create([
                    'fk_cartao' => $cartao->id,
                    'responsavel_id' => $request->responsavel_id,
                    'nome' => $alunoData['nome'] . ' - ' . $alunoData['serie'],
                    'cpf' => null,
                    'telefone' => null,
                    'fk_tipo_cliente' => 1,
                    'valor_atual' => 0,
                    'valor_cartao' => 0,
                    'fk_tipo_pagamento' => null,
                    'observacao' => 'Cadastro de aluno',
                    'devolvido' => 'N',
                    'status' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'fk_usuario' => 1
                ]);

            }

            DB::commit();

            $aluno = CartaoCliente::where('responsavel_id', $request->responsavel_id)->latest()->first();

            session(['cliente' => $aluno]);

            return redirect('cliente/alunos')->with('success', 'Aluno(s) cadastrado(s) com sucesso!');
        } catch (Exception $ex) {
            DB::rollback();
            // dd($ex->getMessage(), $ex->getLine(), $ex->getFile());
            return redirect()->back()->with('error', 'Erro ao cadastrar aluno(s): ' . $ex->getMessage())->withInput();
        }
    }

    public function editAluno($id)
    {
        $responsavel = session('responsavel');
        $aluno = CartaoCliente::where('id', $id)->where('responsavel_id', $responsavel->id)->first();

        if (!$aluno) {
            return redirect()->back()->with('error', 'Aluno não encontrado.');
        }

        $partes = explode(' - ', $aluno->nome, 2); // POG para separa o nome do aluno da série.
        $aluno->nome = trim($partes[0]);
        $aluno->serie = isset($partes[1]) ? trim($partes[1]) : '';

        return view('cliente.editar-aluno', compact('aluno', 'responsavel'));
    }

    public function updateAluno(Request $request, string $id)
    {
        $request->validate([
            'id' => 'required|integer',
            'nome' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[^\d]+$/',
                'regex:/^\S{3,}(\s+\S+)*\s+\S{3,}$/',
            ],
            'serie' => 'required|string|max:50',
        ], [
            'nome.required' => 'O nome do aluno é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.regex' => 'Informe o nome completo do aluno (nome e sobrenome, cada um com pelo menos 3 letras).',
            'serie.required' => 'A série do aluno é obrigatória.',
        ]);

        $responsavel = session('responsavel');
        $aluno = CartaoCliente::where('id', $id)->where('responsavel_id', $responsavel->id)->first();

        if (!$aluno) {
            return redirect()->back()->with('error', 'Não foi possível alterar os dados do aluno.');
        }

        try {
            DB::beginTransaction();
            $aluno->nome = $request->nome . ' - ' . $request->serie;
            $aluno->save();
            DB::commit();

            session(['cliente' => $aluno]);

            return redirect()->back()->with('success', 'Aluno atualizado com sucesso!');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erro ao atualizar aluno: ' . $ex->getMessage())->withInput();
        }
    }

    public function deleteAluno(string $id)
    {
        $responsavel = session('responsavel');
        $aluno = CartaoCliente::where('id', $id)->where('responsavel_id', $responsavel->id)->first();

        if (!$aluno) {
            return redirect()->back()->with('error', 'Aluno não encontrado.');
        }

        try {
            DB::beginTransaction();
            $aluno->update(['status' => 3]); // Bloqueado
            DB::commit();

            return redirect()->back()->with('success', 'Aluno excluído com sucesso!');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erro ao excluir aluno: ' . $ex->getMessage());
        }
        // return redirect()->back()->with('error', 'Exclusão de aluno não permitida no momento. Entre em contato com a administração para solicitar a exclusão.');
    }

    public function dadosResponsavel()
    {
        $responsavel = session('responsavel');
        return view('cliente.dados-responsavel', compact('responsavel'));
    }

    public function updateDadosResponsavel(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
            'telefone' => 'required|string|max:20',
        ]);

        $responsavel = session('responsavel'); // Responsável da Sessão

        $responsavelModel = Responsavel::find($request->id); // Responsável editado

        if ($responsavel->id != $responsavelModel->id) { // Garantir que o usuário logado seja quem está editando
            return redirect()->back()->with('error', 'Não foi possível alterar os dados do responsável.');
        }

        try {
            DB::beginTransaction();
            $responsavelModel->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => preg_replace('/\D/', '', $request->telefone),
            ]);
            $responsavelModel->fresh();
            DB::commit();

            session(['responsavel' => $responsavelModel]);

            return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erro ao atualizar dados do responsável: ' . $ex->getMessage())->withInput();
        }
    }

    public function senhaRecuperarTela()
    {
        return view('cliente.senha-recuperar');
    }

    public function senhaRecuperarCliente(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $responsavel = Responsavel::where('email', $request->email)->first();

        try {
            DB::beginTransaction();
            if ($responsavel) {
                $responsavel->token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $responsavel->save();
                $responsavel->recuperarSenha = true;

                Mail::to($responsavel->email)->send(new ResponsavelTokenMail($responsavel));

                session(['email' => $responsavel->email]);
            }
            DB::commit();

            return redirect('cliente/senha/redefinir');
            // ->with('success', 'Você receberá um código em breve.');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erro ao enviar o token por e-mail: ' . $ex->getMessage())->withInput();
        }
    }

    public function senhaRedefinirTela()
    {
        $modo = 'alteracao';
        $email = null;

        if (session()->has('email')) {
            $modo = 'recuperacao';
            $email = session('email');
        } elseif (session()->has('responsavel')) {
            $modo = 'alteracao';
            $email = session('responsavel')->email;
        }

        return view('cliente.senha-redefinir', compact('modo', 'email', ));
    }

    public function senhaRedefinirCliente(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|min:6|same:confirmar_senha',
            'confirmar_senha' => 'required',
        ], [
            'senha.same' => 'As senhas não estão iguais.',
        ]);

        try {
            DB::beginTransaction();

            if (session()->has('email')) {
                $request->validate([
                    'token' => 'required'
                ]);

                $responsavel = Responsavel::where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

                if (!$responsavel) {
                    return redirect()->back()
                        ->with('error', 'Código inválido. Verifique e tente novamente.')
                        ->withInput();
                }

                $responsavel->senha = Hash::make($request->senha);
                $responsavel->token = null;
                $responsavel->save();

                session()->forget('email');

                DB::commit();

                return redirect('cliente/login')
                    ->with('success', 'Senha redefinida com sucesso. Faça seu login.');
            }

            if (session()->has('responsavel')) {
                $request->validate([
                    'senha_atual' => 'required'
                ]);
                $responsavel = Responsavel::where('email', $request->email)
                    ->first();

                if (!$responsavel || !Hash::check($request->senha_atual, $responsavel->senha)) {
                    return redirect()->back()->with('error', 'Senha atual inválida.')->withInput();
                }

                $responsavel->senha = Hash::make($request->senha);
                $responsavel->save();

                DB::commit();

                return redirect()->back()->with('success', 'Senha alterada com sucesso.');
            }

            DB::rollBack();

            return redirect('cliente/login')
                ->with('error', 'Sessão inválida.');

        } catch (Exception $ex) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao redefinir senha: ' . $ex->getMessage())
                ->withInput();
        }
    }

    public function trocarAluno(Request $request)
    {
        $request->validate(['aluno_id' => 'required|integer']);

        $responsavel = session('responsavel');

        $aluno = CartaoCliente::where('id', $request->aluno_id)->where('responsavel_id', $responsavel->id)->firstOrFail();

        session(['cliente' => $aluno]);

        return redirect('cliente/home')->with('success', 'Aluno alterado para ' . $aluno->nome);
    }

}

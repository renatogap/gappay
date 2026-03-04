<?php

namespace App\Http\Controllers;

use App\Models\Entity\Cardapio;
use App\Models\Entity\CardapioTipo;
use App\Models\Entity\Estoque;
use App\Models\Entity\EstoqueEntrada;
use App\Models\Entity\EstoqueSaida;
use App\Models\Entity\TipoUnidadeMedida;
use App\Models\Entity\UsuarioTipoCardapio;
use App\Models\Facade\EstoqueDB;
use App\Models\Regras\EstoqueRegras;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GapPay\Seguranca\Models\Entity\SegGrupo;

class EstoqueController extends Controller
{
    public function index()
    {
        $id_tipo_cardapio = request('id_tipo_cardapio');
        $itensCardapio = null;
        $itensEstoquePDV = null;

        $perfisUsuario = SegGrupo::where('usuario_id', Auth::user()->id)->get()->pluck('perfil_id')->toArray();

        if($id_tipo_cardapio){
            $tiposCardapios = [$id_tipo_cardapio];

            $itensCardapio = Cardapio::where('fk_tipo_cardapio', $id_tipo_cardapio)->where('status', '!=', 2)->orderBy('nome_item')->get();

            $itensEstoquePDV = DB::table('estoque as e')
                ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')
                ->join('tipo_unidade_medida as um', 'um.id', '=', 'e.fk_tipo_unidade_medida')
                ->select([
                    'e.id',
                    'e.tipo_movimento',
                    'e.fk_tipo_cardapio',
                    'e.fk_item_cardapio',
                    'e.estoque_minimo',
                    'e.estoque_maximo',
                    'e.fk_tipo_unidade_medida',
                    'e.qtd_dose_por_garrafa',
                    'e.dt_ultima_atualizacao',
                    // 'e.qtd_atual',
                    DB::raw("(select 
                                (
                                select sum(ee.quantidade)
                                from estoque_entrada ee 
                                where 
                                    ee.fk_item_cardapio = e.fk_item_cardapio
                                ) 
                                -
                                (
                                select sum(es.quantidade)
                                from estoque_saida es 
                                    
                                where
                                    es.fk_item_cardapio = e.fk_item_cardapio
                                ))
                                as qtd_atual"),

                    'c.nome_item', 'um.nome as unidade_medida',
                    'c.fk_produto as id_produto'
                ])
                ->where('e.fk_tipo_cardapio', $id_tipo_cardapio)
                ->where('c.status', '!=', 2)
                ->orderBy('c.nome_item')
                ->get();

            //$itensEstoquePDV = Estoque::where('fk_tipo_cardapio', $id_tipo_cardapio)->orderBy('id', 'DESC')->get();

            #$comboPDVsDestino = CardapioTipo::orderBy('nome')->where('id', '!=', $id_tipo_cardapio)->get();
            $comboPDVsDestino = DB::select("SELECT * 
                                            FROM cardapio_tipo as ct 
                                            WHERE ct.id != $id_tipo_cardapio 
                                              AND ct.id IN (SELECT e.fk_tipo_cardapio
                                                            FROM estoque as e 
                                                            WHERE e.fk_tipo_cardapio = ct.id
                                                        )
                                              AND ct.status != 2
                                            ORDER BY ct.nome");


        }else {
            if(in_array(4, $perfisUsuario)){
                $tiposCardapios = UsuarioTipoCardapio::where('fk_usuario', Auth::user()->id)->get()->pluck('fk_tipo_cardapio')->toArray();
                //$tiposCardapios = CardapioTipo::orderBy('id')->whereIn('id', $perfisUsuario)->get()->pluck('id')->toArray();
            }else {
                $tiposCardapios = CardapioTipo::orderBy('nome')->get()->pluck('id')->toArray();
            }
            
            $comboPDVsDestino = DB::select("SELECT * 
                                            FROM cardapio_tipo as ct 
                                            WHERE ct.id IN (SELECT e.fk_tipo_cardapio 
                                                            FROM estoque as e 
                                                            WHERE e.fk_tipo_cardapio = ct.id
                                                            )
                                                AND ct.status != 2
                                            ORDER BY ct.nome");
        }       
        
        $comboTipo = CardapioTipo::whereIn('id', $tiposCardapios)->where('estoque', 1)->where('status', '!=', 2)->orderBy('nome')->get();


        $tipoUnidadeMedida = TipoUnidadeMedida::all();
        
        return view('estoque.index', compact('comboTipo', 'comboPDVsDestino', 'id_tipo_cardapio', 'itensCardapio', 'itensEstoquePDV', 'tipoUnidadeMedida'));
    }

    public function gridEstoque()
    {
        $entrada = DB::table('estoque as e')
                    ->leftJoin('estoque_entrada as ee', 'ee.fk_item_cardapio', '=', 'e.fk_item_cardapio')
                    ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')
                    ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')
                    #->leftJoin('usuario as u', 'u.id', '=', 'ee.fk_usuario_cad')
                    #->where('ee.created_at', '>=', "$dtInicio $horaInicio")
                    #->where('ee.created_at', '<=', "$dtTermino $horaTermino")
                    ->select([
                        'e.id as id_estoque_entrada',
                        'ct.id as id_tipo_cardapio',
                        'ct.nome',
                        'c.id as id_item_cardapio',
                        'c.nome_item as produto',
                        'e.qtd_atual',
                        'e.qtd_dose_por_garrafa',
                        'ee.quantidade',
                        'ee.valor_unitario',
                        'ee.valor_total',
                        'ee.created_at as data',
                        'ee.observacao',
                        DB::raw("'Entrada' as tipo")
                    ])
                    ->orderBy('ee.id', 'DESC');

        $saida = DB::table('estoque as e')
                    ->leftJoin('estoque_saida as es', 'es.fk_item_cardapio', '=', 'e.fk_item_cardapio')
                    ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')
                    ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')
                    #->leftJoin('usuario as u', 'u.id', '=', 'es.fk_usuario')
                    #->leftJoin('pedido_item as pi', 'pi.id', '=', 'es.fk_pedido_item')
                    ->where(DB::raw('es.fk_pedido_item is null'))
                    #->where('es.created_at', '>=', "$dtInicio $horaInicio")
                    #->where('es.created_at', '<=', "$dtTermino $horaTermino")
                    ->select([
                        'e.id as id_estoque_saida',
                        'ct.id as id_tipo_cardapio',
                        'ct.nome',
                        'c.id as id_item_cardapio',
                        'c.nome_item as produto',
                        'e.qtd_atual',
                        'e.qtd_dose_por_garrafa',
                        'es.quantidade',
                        DB::raw("'-' as valor_unitario"),
                        DB::raw("'-' as valor_total"),
                        'es.created_at as data',
                        'es.observacao',
                        DB::raw("'Saida' as tipo")
                    ])
                    ->orderBy('es.id', 'DESC');            
        
        $dados = null;

        //dd(request('soEntradas'), request('soSaidas'), $entrada, $saida);
        
        $dados = $entrada->union($saida)->orderBy('data', 'DESC')->get();

        return $dados;
    }

    public function getEstoqueItem($id)
    {
        $quantidade = EstoqueDB::saldoEstoqueProdutoCardapio($id);
        $estoqueItem = Estoque::where('fk_item_cardapio', $id)->first();
        if($estoqueItem){
            $estoqueItem->qtd_atual = $quantidade;
        }

        return response()->json($estoqueItem);
    }

    public function store(Request $request)
    {
        $p = (object) $request->all();

        if($p->tipoUnidadeMedida == 2 && !$p->qtdDosePorGarrafa) {
            return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'Obrigatório o campo <b>Doses por Garrafa</b>')->withInput();
        }

        //validar 


        DB::beginTransaction();

        try {

            if($p->tipoMovimento == 'E') {

                //Entrada do Estoque
                EstoqueEntrada::create([
                    'fk_tipo_cardapio' => $p->tipo_cardapio,
                    'fk_item_cardapio' => $p->item_cardapio,
                    'quantidade' => $p->quantidade,
                    'valor_unitario' => $p->valor,
                    'valor_total' => ($p->qtdDosePorGarrafa ? ($p->valor * ($p->quantidade/$p->qtdDosePorGarrafa)) : $p->valor * $p->quantidade),
                    'observacao' =>  $p->observacao ? $p->observacao.". Usuário:".Auth::user()->nome :'Entrada no Estoque. Usuário: '.Auth::user()->nome,
                    'fk_usuario_cad' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            else if($p->tipoMovimento == 'S') {

                if($p->quantidade > $p->qtd_atual){
                    return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'Saldo insuficiente no estoque.')->withInput();
                }


                if(!$p->observacao) {
                    return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'Obrigatório o campo <b>Observação</b>')->withInput();
                }

                //Valida Quantidade no estoque
                //$estoque = Estoque::where('fk_tipo_cardapio', $p->tipo_cardapio)->where('fk_item_cardapio', $p->item_cardapio)->first();

                $quantidade = DB::table('estoque as e')
                                ->select([
                                    DB::raw("(select 
                                    (
                                        select COALESCE(sum(ee.quantidade), 0) as quantidade
                                        from estoque_entrada ee 
                                        where ee.fk_item_cardapio = $p->item_cardapio
                                    ) 
                                    -
                                    (
                                        select COALESCE(sum(es.quantidade), 0) as quantidade
                                        from estoque_saida es 
                                        where es.fk_item_cardapio = $p->item_cardapio
                                    ))
                                    as qtd_atual")
                                ])
                                ->first();


                if($quantidade->qtd_atual < $p->quantidade) {
                    return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'Estoque insuficiente para realizar essa operação.')->withInput();
                }

                EstoqueSaida::create([
                    'fk_tipo_cardapio' => $p->tipo_cardapio,
                    'fk_item_cardapio' => $p->item_cardapio,
                    'quantidade' => $p->quantidade,
                    'fk_pedido_item' => null,
                    'observacao' =>  $p->observacao ? $p->observacao.". Usuário:".Auth::user()->nome : 'Saída manual pelo usuário '.Auth::user()->nome,
                    'fk_usuario' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            else if($p->tipoMovimento == 'T') {
                $itemCardapioOrigem = Cardapio::find($p->item_cardapio);

                $itemCardapioDestino = Cardapio::where('fk_tipo_cardapio', $p->pdv_destino)->where('fk_produto', $itemCardapioOrigem->fk_produto)->first();

                if(!$itemCardapioDestino) {
                    return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'O Produto <b>'.$itemCardapioOrigem->fk_produto.' - '.$itemCardapioOrigem->nome_item.'</b> não existe no PDV de destino')->withInput();
                }

                //Valida Quantidade no estoque
                $estoque = Estoque::where('fk_tipo_cardapio', $itemCardapioOrigem->fk_tipo_cardapio)->where('fk_item_cardapio', $itemCardapioOrigem->id)->first();
                
                if(!$estoque) {
                    return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'Não foi dado Entrada no Estoque do produto <b>'.$itemCardapioOrigem->fk_produto.' - '.$itemCardapioOrigem->nome_item.'</b> do PDV de Orígem')->withInput();
                }

                $quantidade = DB::table('estoque as e')
                                ->select([
                                    DB::raw("(select 
                                    (
                                        select COALESCE(sum(ee.quantidade), 0) as quantidade
                                        from estoque_entrada ee 
                                        where ee.fk_item_cardapio = $p->item_cardapio
                                    ) 
                                    -
                                    (
                                        select COALESCE(sum(es.quantidade), 0) as quantidade
                                        from estoque_saida es 
                                        where es.fk_item_cardapio = $p->item_cardapio
                                    ))
                                    as qtd_atual")
                                ])
                                ->first();
                
                //if($estoque->qtd_atual < $p->quantidade) {
                if($quantidade->qtd_atual < $p->quantidade) {                    
                    return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'Estoque insuficiente para realizar a transferência.')->withInput();
                }

                $pdvOrigem = CardapioTipo::find($p->tipo_cardapio);
                $pdvDestino = CardapioTipo::find($p->pdv_destino);

                EstoqueSaida::create([
                    'fk_tipo_cardapio' => $p->tipo_cardapio,
                    'fk_item_cardapio' => $p->item_cardapio,
                    'quantidade' => $p->quantidade,
                    'fk_pedido_item' => null,
                    'observacao' => $p->observacao ? $p->observacao.". Usuário:".Auth::user()->nome : "Transferência de Saída para {$pdvDestino->nome}. Usuário:".Auth::user()->nome,
                    'fk_usuario' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);


                EstoqueEntrada::create([
                    'fk_tipo_cardapio' => $p->pdv_destino,
                    'fk_item_cardapio' => $itemCardapioDestino->id,
                    'quantidade' => $p->quantidade,
                    'valor_unitario' => $p->valor,
                    'valor_total' => ($p->qtdDosePorGarrafa ? ($p->valor * ($p->quantidade/$p->qtdDosePorGarrafa)) : $p->valor * $p->quantidade),
                    'observacao' => $p->observacao ? $p->observacao.". Usuário:".Auth::user()->nome : "Transferência de Entrada do {$pdvOrigem->nome}. Usuário: ".Auth::user()->nome,
                    'fk_usuario_cad' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);


                //Atualização do Estoque no PDV de Destino
                EstoqueRegras::atualizaEstoquePdvDestino($p, $itemCardapioDestino->id);
            }


            
            //Atualização do Estoque no PDV de Orígem
            EstoqueRegras::atualizaEstoquePdvOrigem($p);


            DB::commit();
            return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('sucesso', 'Estoque atualizado com sucesso.');
        } catch(\Exception $ex) {
            DB::rollback();
            return redirect('estoque?id_tipo_cardapio='.$p->tipo_cardapio)->with('error', 'Um erro ocorreu.<br>'. $ex->getMessage())->withInput();
        }
    }

    public function detalhesItem()
    {
        $id_tipo_cardapio = request('id_tipo_cardapio');
        $id_item_cardapio = request('id_item_cardapio');
        $dtInicio  = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));
        $dtTermino = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));
        $horaInicio  = (request('horaInicio') ? request('horaInicio') : date('00:01'));
        $horaTermino = (request('horaTermino') ? request('horaTermino') : date('H:i', strtotime('+ 1 minutes')));

        $dtI = new DateTime($dtInicio);
        $dtF = new DateTime($dtTermino);

        #$data = $dtI->format('Y-m-d');
        $data = $dtF->format('Y-m-d');

        
        $estoquePdvs = [];

        $diasemana = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');

        while($dtI <= $dtF) {
    
            $dataAnterior = date('Y-m-d', strtotime('-1 day', strtotime($data)));

            $diasemana_numero = date('w', strtotime($data));

            #$dia = date('d/m', strtotime($data)).' '.$diasemana[$diasemana_numero];
            $dia = date('d/m', strtotime($data));

            $estoquePdvs[] = DB::select("SELECT 
                    '".$dia."' as data,
                    '".$data."' as data_en,
                    '".$diasemana[$diasemana_numero]."' as semana,
                    (CASE WHEN s.entradas_anterior IS NOT NULL THEN s.entradas_anterior ELSE 0 END - CASE WHEN s.saidas_anterior IS NOT NULL THEN s.saidas_anterior ELSE 0 END) as estoque_inicial,
                    CASE WHEN s.entradas IS NOT NULL THEN s.entradas ELSE 0 END AS entradas,
                    CASE WHEN s.saidas IS NOT NULL THEN s.saidas ELSE 0 END AS saidas,
                    ((CASE WHEN s.entradas_anterior IS NOT NULL THEN s.entradas_anterior ELSE 0 END - CASE WHEN s.saidas_anterior IS NOT NULL THEN s.saidas_anterior ELSE 0 END) + CASE WHEN s.entradas IS NOT NULL THEN s.entradas ELSE 0 END - CASE WHEN s.saidas IS NOT NULL THEN s.saidas ELSE 0 END) as saldo_final
                FROM (
                    SELECT 
                        (SELECT SUM(quantidade)
                        FROM `estoque_entrada` 
                        WHERE `fk_tipo_cardapio` = {$id_tipo_cardapio}
                            AND `fk_item_cardapio` = {$id_item_cardapio}
                            AND created_at BETWEEN '$data $horaInicio' and '$data $horaTermino') AS entradas,
                        (SELECT SUM(quantidade)
                        FROM `estoque_saida` 
                        WHERE `fk_tipo_cardapio` = {$id_tipo_cardapio}
                            AND `fk_item_cardapio` = {$id_item_cardapio}
                            AND created_at BETWEEN '$data $horaInicio' and '$data $horaTermino') AS saidas,

                            (SELECT SUM(quantidade)
                        FROM `estoque_entrada` 
                        WHERE `fk_tipo_cardapio` = {$id_tipo_cardapio}
                            AND `fk_item_cardapio` = {$id_item_cardapio}
                            AND created_at BETWEEN '2020-11-01 00:00:00' and '$dataAnterior 23:59:00') AS entradas_anterior,
                        (SELECT SUM(quantidade)
                        FROM `estoque_saida` 
                        WHERE `fk_tipo_cardapio` = {$id_tipo_cardapio}
                            AND `fk_item_cardapio` = {$id_item_cardapio}
                            AND created_at BETWEEN '2020-11-01 00:00:00' and '$dataAnterior 23:59:00') AS saidas_anterior

                ) s")[0];

            #$data = $dtI->add(new DateInterval("P1D"))->format('Y-m-d');
            $data = $dtF->sub(new DateInterval("P1D"))->format('Y-m-d');
        }

        return response()->json($estoquePdvs);
    }

    public function impressao()
    {
        $dtInicio   = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));
        $dtTermino  = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));
        $horaInicio  = (request('horaInicio') ? request('horaInicio') : date('00:01'));
        $horaTermino = (request('horaTermino') ? request('horaTermino') : date('23:59'));

        
        $produtosEstoque = DB::table('cardapio as c')
            ->join('estoque as e', 'c.id', '=', 'e.fk_item_cardapio')
            ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')
            ->select([
                'ct.id as id_tipo_cardapio',
                'ct.nome',
                'c.id as id_item_cardapio',
                'c.nome_item as produto',
                'c.fk_produto',
                //'e.qtd_atual'
                DB::raw("(select 
                (
                    select COALESCE(sum(ee.quantidade), 0) as quantidade
                    from estoque_entrada ee 
                    where ee.fk_item_cardapio = c.id
                ) 
                -
                (
                    select COALESCE(sum(es.quantidade), 0) as quantidade
                    from estoque_saida es 
                    where es.fk_item_cardapio = c.id
                ))
                as qtd_atual")
            ])
            ->orderBy('c.nome_item')
            ->get();


        $estoquePdvs = [];

        foreach($produtosEstoque as $d) {
            $estoquePdvs[$d->nome.'_'.$d->id_tipo_cardapio][$d->produto.'__'.$d->qtd_atual.'__'.$d->id_item_cardapio.'__'.$d->fk_produto][] = $d;
        }
        

        return view('estoque.impressao', compact('dtInicio', 'dtTermino', 'horaInicio', 'horaTermino', 'estoquePdvs'));
        

    }

    public function detalhamentoItem()
    {
        $idItemCardapio   = request('id_item_cardapio');
        $data   = request('data');
        /*
        $dtInicio   = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));
        $dtTermino  = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));
        $horaInicio  = (request('horaInicio') ? request('horaInicio') : date('00:01'));
        $horaTermino = (request('horaTermino') ? request('horaTermino') : date('H:i', strtotime('+ 1 minutes')));
        */

        $entrada = DB::table('estoque as e')
                    ->join('estoque_entrada as ee', 'ee.fk_item_cardapio', '=', 'e.fk_item_cardapio')
                    ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')
                    ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')
                    ->leftJoin('usuario as u', 'u.id', '=', 'ee.fk_usuario_cad')
                    ->where('ee.fk_item_cardapio', $idItemCardapio)
                    ->where('ee.created_at', '>=', "$data 00:00:00")
                    ->where('ee.created_at', '<=', "$data 23:59:00")
                    ->select([
                        'ct.id as id_tipo_cardapio',
                        'ct.nome',
                        'c.id as id_item_cardapio',
                        'c.nome_item as produto',
                        'e.qtd_atual',
                        'e.qtd_dose_por_garrafa',
                        'ee.quantidade',
                        'ee.valor_unitario',
                        'ee.valor_total',
                        DB::raw("DATE_FORMAT(ee.created_at, '%d/%m/%y %H:%i') as data"),
                        'ee.observacao',
                        'u.nome as usuario',
                        DB::raw("'E' as tipo"),
                        DB::raw("'' as fk_pedido_item")
                    ])
                    ->orderBy('ee.id', 'DESC')
                    ->get();

        $saida = DB::table('estoque as e')
                    ->join('estoque_saida as es', 'es.fk_item_cardapio', '=', 'e.fk_item_cardapio')
                    ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')
                    ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')
                    ->leftJoin('usuario as u', 'u.id', '=', 'es.fk_usuario')
                    ->leftJoin('pedido_item as pi', 'pi.id', '=', 'es.fk_pedido_item')
                    ->where('es.fk_item_cardapio', $idItemCardapio)
                    ->where('es.created_at', '>=', "$data 00:00:00")
                    ->where('es.created_at', '<=', "$data 23:59:00")
                    ->select([
                        'ct.id as id_tipo_cardapio',
                        'ct.nome',
                        'c.id as id_item_cardapio',
                        'c.nome_item as produto',
                        'e.qtd_atual',
                        'e.qtd_dose_por_garrafa',
                        'es.quantidade',
                        DB::raw("0 as valor_unitario"),
                        DB::raw("0 as valor_total"),
                        #DB::raw("(es.quantidade * pi.valor) as valor_total"),
                        DB::raw("DATE_FORMAT(es.created_at, '%d/%m/%y %H:%i') as data"),
                        'es.observacao',
                        'u.nome as usuario',
                        DB::raw("'S' as tipo"),
                        DB::raw("es.fk_pedido_item")
                    ])
                    ->orderBy('es.id', 'DESC')
                    ->get();            
        
        $dados = [];

        //dd(request('soEntradas'), request('soSaidas'), $entrada, $saida);

        
        // if($entrada && $saida) {
        //     $dados = $entrada->union($saida)->orderBy('data', 'DESC')->get();
        // }
        // else if($entrada && !$saida) {
        //     $dados = $entrada->orderBy('data', 'DESC')->get();
        // }
        // else if(!$entrada && $saida) {
        //     $dados = $saida->orderBy('data', 'DESC')->get();
        //}

        //dd($dados);

        $cardapio = Cardapio::find($idItemCardapio);
        
        return response()->json(['produto' => $cardapio->nome_item, 'entradas' => $entrada, 'saidas' => $saida]);
    }

    public function resumoEstoque()		
    {		
        $dtInicio  = (request('dtInicio') ? request('dtInicio') : date('Y-m-d'));		
        $dtTermino = (request('dtTermino') ? request('dtTermino') : date('Y-m-d'));		
        $horaInicio  = (request('horaInicio') ? request('horaInicio') : date('00:00'));		
        $horaTermino = (request('horaTermino') ? request('horaTermino') : date('H:i', strtotime('+ 1 minutes')));		
		
        $entrada = DB::table('estoque as e')		
                ->leftJoin('estoque_entrada as ee', 'ee.fk_item_cardapio', '=', 'e.fk_item_cardapio')		
                ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')		
                ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')		
                ->join('cardapio_categoria as ccat', 'ccat.id', '=', 'c.fk_categoria')		
                ->where('ee.created_at', '>=', "$dtInicio $horaInicio")		
                ->where('ee.created_at', '<=', "$dtTermino $horaTermino")		
                ->select([		
                    'ct.id as id_tipo_cardapio',		
                    'ct.nome',		
                    'c.id as id_item_cardapio',		
                    'ccat.nome as categoria',		
                    'c.nome_item as produto',		
                    'e.qtd_atual',		
                    'e.qtd_dose_por_garrafa',		
                    'ee.quantidade',		
                    'c.valor as valor_unitario',		
                    'ee.created_at as data',		
                    'ee.observacao',		
                    DB::raw("'E' as tipo"),		
                    DB::raw("'' as fk_pedido_item")		
                ]);		
		
        $saida = DB::table('estoque as e')		
                ->leftJoin('estoque_saida as es', 'es.fk_item_cardapio', '=', 'e.fk_item_cardapio')		
                ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')		
                ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')		
                ->join('cardapio_categoria as ccat', 'ccat.id', '=', 'c.fk_categoria')		
                ->join('pedido_item as pi', 'pi.id', '=', 'es.fk_pedido_item')		
                ->where('es.created_at', '>=', "$dtInicio $horaInicio")		
                ->where('es.created_at', '<=', "$dtTermino $horaTermino")		
                ->whereRaw('es.fk_pedido_item IS NOT NULL')		
                ->select([		
                    'ct.id as id_tipo_cardapio',		
                    'ct.nome',		
                    'c.id as id_item_cardapio',		
                    'ccat.nome as categoria',		
                    'c.nome_item as produto',		
                    'e.qtd_atual',		
                    'e.qtd_dose_por_garrafa',		
                    'es.quantidade',		
                    'c.valor as valor_unitario',		
                    'es.created_at as data',		
                    'es.observacao',		
                    DB::raw("'S' as tipo"),		
                    DB::raw("es.fk_pedido_item")		
                ]);		
		
          		
        		
                		
        $saidaManual = DB::table('estoque as e')		
                ->leftJoin('estoque_saida as es2', 'es2.fk_item_cardapio', '=', 'e.fk_item_cardapio')		
                ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')		
                ->join('cardapio_tipo as ct', 'ct.id', '=', 'e.fk_tipo_cardapio')		
                ->join('cardapio_categoria as ccat', 'ccat.id', '=', 'c.fk_categoria')		
                ->leftJoin('pedido_item as pi', 'pi.id', '=', 'es2.fk_pedido_item')		
                ->where('es2.created_at', '>=', "$dtInicio $horaInicio")		
                ->where('es2.created_at', '<=', "$dtTermino $horaTermino")		
                ->whereRaw('es2.fk_pedido_item IS NULL')		
                ->select([		
                    'ct.id as id_tipo_cardapio',		
                    'ct.nome',		
                    'c.id as id_item_cardapio',		
                    'ccat.nome as categoria',		
                    'c.nome_item as produto',		
                    'e.qtd_atual',		
                    'e.qtd_dose_por_garrafa',		
                    'es2.quantidade',		
                    'c.valor as valor_unitario',		
                    'es2.created_at as data',		
                    'es2.observacao',		
                    DB::raw("'SM' as tipo"),		
                    DB::raw("'' as fk_pedido_item")		
                ])		
                ->orderBy('c.nome_item', 'asc');		
		
        #printvardie($saidaManual->get());        		
		
        $dados = $entrada->union($saida)->union($saidaManual)->orderBy('categoria')->orderBy('produto')->get();      		
        		
		
        $groupPDV = [];		
        $estoquePdvs = [];		
		
        if($dados->count() > 0) {		
            foreach($dados as $d) {		
                $groupPDV[$d->nome][$d->produto][$d->tipo][] = $d;		
            }		
		
            foreach($groupPDV as $pdv => $groupProdutos) {		
		
                foreach($groupProdutos as $produto => $groupTipoMovimento) {		
		
                    foreach($groupTipoMovimento as $tipo => $items) {		
		
                        $estoquePdvs[$pdv][$produto]['EI'] = $items[0]->qtd_atual;		
		
                        if($tipo == 'E') {		
                            $estoquePdvs[$pdv][$produto]['E'] = array_sum(array_column($items, 'quantidade')).'_'.($items[0]->valor_unitario ?? '0.00').'_'.($items[0]->valor_total ?? '0.00');		
                        }		
		
                        		
                        if($tipo == 'S') {		
                            $estoquePdvs[$pdv][$produto]['S'] = array_sum(array_column($items, 'quantidade')).'_'.($items[0]->valor_unitario ?? '0.00').'_'.($items[0]->valor_total ?? '0.00');		
                        }		
                        		
                        if($tipo == 'SM') {		
                            $estoquePdvs[$pdv][$produto]['SM'] = array_sum(array_column($items, 'quantidade')).'_'.($items[0]->valor_unitario ?? '0.00').'_'.($items[0]->valor_total ?? '0.00');		
                        }		
                    }		
		
                }		
            }		
            		
            $nome_arquivo = "Resumo_do_Estoque";		
            #header("Content-type: application/vnd.ms-excel");		
            header("Content-type: application/xls");		
            header("Content-type: application/force-download");		
            header("Content-Disposition: attachment; filename=$nome_arquivo.xls");		
            header("Pragma: no-cache");		
        }		
		
        #printvardie($estoquePdvs);		
		
        		
		
        return view('estoque.resumo', compact('dtInicio', 'dtTermino', 'horaInicio', 'horaTermino', 'estoquePdvs'));		
		
    }
}

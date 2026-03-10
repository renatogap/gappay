@extends('layouts.default')
@section('conteudo')
<div>
    <h5>
        <i class="material-icons icone">history</i>
        Histórico do Pedido

        <a href="{{url('pedido/visualizacao-gerente')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h5>

    <hr>


    @if (session('sucesso'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ session('sucesso') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ session('error') }}
    </div>
    @endif

    <?php $total = 0 ?>


    <h4 class="text-center"><b>{{ $pedidos[0]->nome_cliente }}</b></h4>

    <div class="mb-3 text-center" style="font-size: 12px;">
        <div>Data <b>{{date('d/m/Y', strtotime($pedidos[0]->dt_pedido))}}</b></div>
        <!-- <div>Mesa <b>{{ $pedidos[0]->mesa }}</b> | Pedido <b>{{ $pedidos[0]->id }}</b></div> -->
        <div>Hora Pedido: <b>{{date('H:i', strtotime($pedidos[0]->dt_pedido))}}</b> @if($pedidos[0]->dt_pronto) | Pedido Pronto: <b>{{date('H:i', strtotime($pedidos[0]->dt_pronto))}}</b>@endif</div>
    </div>

    <table class="table">
        @foreach($pedidos as $indice => $pedido)
        <tr>
            <td width="95%">
                <b>{{ $pedido->unid == 1 ? intval($pedido->quantidade) : $pedido->quantidade }}x {{ $pedido->nome_item }}</b><br>
                @if($pedido->observacao)
                <div class="badge badge-warning" style="font-size: 11px;">* {{ $pedido->observacao }}</div>
                @endif
            </td>
            <td width="5%" align="right" {{ $pedido->status == 4 ? 'colspan=2' : '' }}>
                @if($pedido->status == 4)
                <span class="badge badge-danger">Cancelado</span>
                @else
                {{ number_format($pedido->valor_total_item, 2, ',', '.') }}
                @endif
            </td>
        </tr>

        @endforeach
    </table>

    <hr style="margin-top: -1em;">

    <?php $taxaServico = 0; ?><!-- Ainda não foi pensado como ficará aqui a comissão -->

    <div style="font-size: 1.5em; text-align: right;">
        <strong>
            Total: R$ {{ number_format(($pedidos[0]->valor_total), 2, ',', '.') }}
        </strong>
    </div>

    <div class="float-left" style="font-size: 1.5em;">
        <a href="{{ url('pedido/confirmar-entrega-gerente/'.$pedidos[0]->id) }}" class="btn btn-parque" style="text-shadow: 5px 5px 5px rbga(0,0,0,0.5); box-shadow: 5px 5px 5px rgba(0,0,0,0.5);">Entregue</a>
    </div>
    <br><br><br>
</div>
@endsection
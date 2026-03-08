@extends('layouts.default')
@section('conteudo')
    <div>
        <h4>
            &nbsp;
            <a href="{{url('cliente/meus-pedidos')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
            </a>           
        </h4>
        
        <br>

        @if (session('sucesso'))
            <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! session('sucesso') !!}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! session('error') !!}
            </div>
        @endif

        <h5 class="text-center"><b>{{ $pedidos[0]->nome_cliente }}</b></h5>

        <div class="mb-3 text-center" style="font-size: 12px;">
            Pedido: <b>{{ $pedidos[0]->id }}</b>
            <div>Em: {{date('d/m/Y H:i', strtotime($pedidos[0]->dt_pedido))}}
        </div>
        </div> 

        <table class="table">
            @foreach($pedidos as $indice => $pedido)
            <tr>                    
                <td width="95%">
                <b>{{ $pedido->unid == 1 ? intval($pedido->quantidade) : $pedido->quantidade }} {{ $pedido->categoria }}: {{ $pedido->nome_item }}</b><br>
                    @if($pedido->observacao) 
                        <div class="badge badge-warning" style="font-size: 11px;">* {{ $pedido->observacao }}</div>
                    @endif
                </td>
                <td width="5%" align="right" {{ $pedido->status == 4 ? 'colspan="2"' : '' }}>
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

        <!-- Calcular o valor total -->
        <?php $valorTotal = $pedidos->where('status', '!=', 4)->sum('valor_total_item'); ?>
        <div style="font-size: 1.5em; text-align: right;">
            <strong>
                Total: R$ {{ number_format($valorTotal, 2, ',', '.') }}
            </strong>
        </div>

        <br><br><br>
    </div>
@endsection

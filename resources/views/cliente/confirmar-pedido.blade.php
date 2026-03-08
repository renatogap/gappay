@extends('layouts.default')
@section('conteudo')
<div>
    <h4>
        <span class="material-icons icone" style="color: green;">check_circle_outline</span>
        Confirmar pedido
        <a href="{{url('cliente/cardapio/1')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h4>
    <br>

    @if (session('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session('error') !!}
    </div>
    @endif

    <?php $valorTotal = array_sum(array_column($pedido, 'valor')); ?>

    <table class="table" width="100%">
        @foreach($pedido as $indice => $p)
        <?php $cardapio = App\Models\Entity\Cardapio::find($p->id_cardapio) ?>

        <tr>
            <td width="1%" style="padding: 0;">
                <a href="{{ url('cliente/confirmar-pedido?remove='.$indice) }}" class="btn btn-sm float-left" title="Remover pedido">
                    <i class="material-icons" style="color: darkred;">delete</i>
                </a>
            </td>
            <td width="20%" style="padding-top: 4px;">
                <b>
                    {{ $p->quantidade }}
                    {{ $cardapio->nome_item }}</b>
                @if(isset($p->observacao))
                <br>{{ $p->observacao }}
                @endif
            </td>
            <td width="2%" align="right" style="padding-top: 4px;">
                {{ number_format($p->valor, 2, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </table>

    <div style="font-size: 1.5em; text-align: right;">
        <strong>
            Total: R$ {{ number_format(($valorTotal), 2, ',', '.') }}
        </strong>
    </div>

    <br><br><br>
    <div>
        <a href="{{ url('cliente/pedido/finalizar') }}" class="btn btn-parque btn-lg btn-block">Finalizar pedido</a>
    </div>
</div>
@endsection
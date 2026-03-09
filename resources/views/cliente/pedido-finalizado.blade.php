@extends('layouts.default')
@section('conteudo')
<div class="text-center" style="padding: 8em 0 10em 0;">
    <h4>
        <span class="material-icons icone mb-2" style="font-size: 2em !important; color: blue;">check_circle_outline</span>
        <div>Pedido Finalizado!</div>
    </h4>
    <br>

    <div>
        <strong>Aluno(a):</strong> {{ $params->cartaoCliente->nome }}
    </div>

    <div class="alert alert-info mt-2" style="font-weight: bold; font-size: 1.5em;">
        Saldo: R$ {{ number_format(($params->cartaoCliente->valor_atual - $params->valorTotalPedido), 2, ',', '.') }}
    </div>

    <div class="mt-3">
        <a href="{{ url('cliente/cardapio/1') }}" class="btn btn-parque btn-block">Retornar para o cardápio</a>
    </div>

</div>
@endsection
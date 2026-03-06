@extends('layouts.default')
@section('conteudo')
<style>
    .pedidos-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2em;
        border-radius: 10px;
        margin-bottom: 2em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .pedidos-header h2 {
        margin: 0;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1.8em;
    }

    .pedidos-header .material-icons {
        margin-right: 15px;
        font-size: 2em;
    }

    .voltar-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        color: #667eea;
        text-decoration: none;
        margin-bottom: 1.5em;
        font-weight: 500;
        transition: color 0.3s;
    }

    .voltar-btn:hover {
        color: #764ba2;
    }

    .voltar-btn .material-icons {
        font-size: 1.2em;
    }

    .filtro-container {
        background: white;
        padding: 1.5em;
        border-radius: 8px;
        margin-bottom: 2em;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .filtro-titulo {
        display: flex;
        align-items: center;
        margin-bottom: 1em;
        color: #333;
        font-weight: 600;
    }

    .filtro-titulo .material-icons {
        margin-right: 10px;
        color: #667eea;
        font-size: 1.3em;
    }

    .filtro-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1em;
        margin-bottom: 1em;
    }

    .filtro-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5em;
        color: #333;
        font-size: 0.9em;
    }

    .filtro-form input {
        width: 100%;
        padding: 0.7em;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .filtro-form input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filtro-actions {
        display: flex;
        gap: 0.8em;
    }

    .btn-search,
    .btn-clear {
        flex: 1;
        padding: 0.8em 1.5em;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5em;
        transition: all 0.3s;
    }

    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-search:hover {
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transform: translateY(-2px);
    }

    .btn-clear {
        background: #f0f0f0;
        color: #666;
    }

    .btn-clear:hover {
        background: #e0e0e0;
    }

    .resumo-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1em;
        margin-bottom: 2em;
    }

    .resumo-card {
        background: white;
        padding: 1.5em;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        text-align: center;
        border-left: 4px solid #667eea;
    }

    .resumo-card .label {
        font-size: 0.9em;
        color: #999;
        margin-bottom: 0.5em;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5em;
    }

    .resumo-card .label .material-icons {
        font-size: 1.2em;
        color: #667eea;
    }

    .resumo-card .valor {
        font-size: 1.8em;
        font-weight: bold;
        color: #333;
    }

    .pedidos-container {
        margin-top: 2em;
    }

    .pedidos-container h3 {
        color: #333;
        margin-bottom: 1.5em;
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .pedidos-container h3 .material-icons {
        color: #667eea;
        margin-right: 10px;
        font-size: 1.5em;
    }

    .pedido-grupo {
        margin-bottom: 2em;
    }

    .pedido-data {
        background: linear-gradient(135deg, #667eea 0%, #1126c5 100%);
        color: white;
        padding: 1em 1.5em;
        border-radius: 6px;
        margin-bottom: 1em;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.8em;
        font-weight: 600;
    }

    .pedido-data .material-icons {
        font-size: 1.3em;
    }

    .pedido-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1em;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .pedido-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .pedido-header {
        background: #f8f9fa;
        padding: 1em 1.5em;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pedido-info {
        /* flex: 1; */
        font-weight: bold;
        color: white;
    }

    .pedido-total {
        text-align: right;
        font-weight: 600;
        color: #667eea;
        font-size: 1.3em;
    }

    .pedido-items {
        padding: 0 1.5em;
    }

    .pedido-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8em 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .pedido-item:last-child {
        border-bottom: none;
    }

    .item-detalhes {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 0.8em;
    }

    .item-qty {
        background: #667eea;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9em;
    }

    .item-nome {
        /* font-weight: 600; */
        color: #333;
    }

    .item-status {
        font-size: 0.8em;
        color: #f7f7f7;
    }

    .item-valor {
        text-align: right;
        font-weight: 600;
        color: #333;
        min-width: 80px;
    }

    .item-cancelado .item-nome {
        color: #ccc;
        text-decoration: line-through;
    }

    .badge {
        display: inline-block;
        padding: 0.3em 0.8em;
        border-radius: 20px;
        font-size: 0.75em;
        font-weight: 600;
        background: #ffebee;
        color: #c62828;
    }

    .sem-pedidos {
        text-align: center;
        padding: 3em;
        color: #999;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .sem-pedidos .material-icons {
        font-size: 3em;
        opacity: 0.5;
        margin-bottom: 1em;
        display: block;
    }

    .rodape-total {
        text-align: right;
        padding: 0 1em 1em;
        font-size: 1.3em;
        font-weight: bold;
        color: #333;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .rodape-total .valor {
        color: #0a259d;
        font-size: 1.5em;
    }

    @media (max-width: 768px) {
        .filtro-form {
            grid-template-columns: 1fr;
        }

        .filtro-actions {
            flex-direction: column;
        }

        .resumo-container {
            grid-template-columns: 1fr;
        }

        .pedido-header {
            /* flex-direction: column; */
            align-items: flex-start;
        }

        .pedido-total {
            margin-top: 0.8em;
            color: white;
        }

        .item-detalhes {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .spinner {
        display: none;
    }

    .btn-search:hover .spinner {
        display: inline-block;
        margin-right: 0.5em;
    }

    .btn-search {
        background: linear-gradient(135deg, #3e5fe1 0%, #091a99 100%);
    }
</style>

<h5>
    <i class="material-icons icone">history</i>
    Histórico de Pedidos

    <a href="{{ url('cliente/home') }}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>
<hr>
<form method="GET" action="{{ url('cliente/pedidos') }}" class="filtro_form">
    <div class="row mb-2">
        <div class="col-6 col-md-4">
            <label for="">Data início: </label>
            <input type="date" name="dtInicio" value="{{ $dtInicio }}" max="{{ date('Y-m-d') }}" class="form-control">
        </div>
        <div class="col-6 col-md-2">
            <label for="">Hora início: </label>
            <input type="time" name="horaInicio" value="{{ $horaInicio }}" class="form-control">
        </div>
        <div class="col-6 col-md-4">
            <label for="">Data término: </label>
            <input type="date" name="dtTermino" value="{{$dtTermino}}" max="{{ date('Y-m-d') }}" class="form-control">
        </div>
        <div class="col-6 col-md-2">
            <label for="">Hora término: </label>
            <input type="time" name="horaTermino" value="{{ $horaTermino }}" class="form-control">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block btn-search">Buscar</button>
        </div>
    </div>
</form>

@if(COUNT($itensPedidoCliente) > 0)
@php
$totalGeral = 0;
$totalPedidos = count($pedidoCliente);
@endphp


<div class="pedidos-container">


    @foreach($itensPedidoCliente as $id_pedido => $pedidos)
    @php
    $dataFormatada = $pedidoCliente[$id_pedido]['dt_pedido'];
    $horaFormatada = $pedidoCliente[$id_pedido]['hora_pedido'];
    @endphp

    <div class="pedido-grupo">


        <div class="pedido-card">
            <div class=" pedido-data">
                <div class="pedido-info">
                    Pedido #{{ $id_pedido }}
                    <div class="item-status">
                        {{ $dataFormatada }} - {{ $horaFormatada }}
                    </div>
                </div>
                <div class="pedido-total">
                    R$ {{ number_format($pedidoCliente[$id_pedido]['valor_total'], 2, ',', '.') }}
                </div>
            </div>

            <div class="pedido-items">
                @foreach($pedidos as $item)
                <div class="pedido-item {{ $item->status == 4 ? 'item-cancelado' : '' }}">
                    <div class="item-detalhes">

                        <div>
                            <div class="item-nome">
                                {{ number_format($item->quantidade) }}
                                {{ $item->nome_item }}
                                @if($item->status == 4)
                                <span class="badge">Cancelado</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="item-valor">
                        @if($item->status != 4)
                        R$ {{ number_format($item->valor_total_item, 2, ',', '.') }}
                        @else
                        <span style="color: #ccc;">R$ 0,00</span>
                        @endif
                    </div>
                </div>

                @php $totalGeral += $item->status != 4 ? $item->valor_total_item : 0; @endphp

                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    <div class="rodape-total">
        <div>Total Geral</div>
        <div class="valor">R$ {{ number_format($totalGeral, 2, ',', '.') }}</div>
    </div>
</div>
@else
<div class="sem-pedidos">
    <i class="material-icons">inbox</i>
    <p>Nenhum pedido encontrado no período selecionado</p>
</div>
@endif

<br><br>

@endsection
@extends('layouts.default')

@section('conteudo')
<style>
    /* Estilos do Dashboard */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .dashboard-title {
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .header-icon {
        color: #195287;
        font-size: 28px;
        vertical-align: middle;
    }
    .btn-back {
        font-size: 1.5em;
        color: #333;
        text-decoration: none;
    }
    .dashboard-divider {
        margin-top: 0;
        margin-bottom: 25px;
    }

    /* Profile Header */
    .profile-card {
        background: #195287;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #144370;
        margin-bottom: 25px;
    }
    .profile-info {
        gap: 15px;
    }
    .profile-avatar {
        width: 55px;
        height: 55px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        flex-shrink: 0;
    }
    .profile-name {
        margin: 0;
        font-weight: bold;
        color: #ffffff;
    }
    .profile-meta {
        margin: 3px 0 0 0;
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    .profile-meta-strong {
        color: #ffffff;
    }
    .balance-container {
        display: inline-block;
        text-align: left;
        padding: 10px 15px;
        min-width: 160px;
    }
    .balance-label {
        color: #a5d6a7;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        display: block;
        margin-bottom: 2px;
    }
    .balance-value {
        color: #ffffff;
        margin: 0;
        font-weight: bold;
        font-size: 24px;
    }

    /* Tabs */
    .nav-tabs-custom {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
        gap: 5px;
    }
    .nav-link-custom {
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 6px;
        padding: 10px 16px;
    }

    /* Orders Tab */
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .order-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .order-header {
        background: #eef5fc;
        border-bottom: 1px solid #d0e1f5;
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .order-title-wrapper {
        font-size: 13px;
        color: #495057;
    }
    .order-title {
        font-weight: bold;
        font-size: 14px;
        color: #212529;
    }
    .order-table-badge {
        margin-left: 8px;
        background: #e2e3e5;
        color: #383d41;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 11px;
    }
    .order-time {
        margin-top: 2px;
        color: #888;
    }
    .order-meta-right {
        font-size: 13px;
        color: #888;
    }
    .order-total-header {
        color: #195287;
        font-weight: bold;
        font-size: 14px;
        margin-top: 2px;
    }
    .order-body {
        padding: 15px 18px;
    }
    .order-table {
        margin: 0;
        font-size: 14px;
    }
    .order-table-header-row {
        color: #888;
        font-size: 11px;
        text-transform: uppercase;
        border-top: none;
    }
    .order-table-header {
        border-top: none;
        padding-bottom: 6px;
    }
    .order-row {
        border-bottom: 1px solid #f9f9f9;
    }
    .order-cell {
        vertical-align: middle;
        padding: 8px 0;
        border-top: none;
    }
    .order-cell-content {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .order-item-canceled {
        text-decoration: line-through;
        color: #aaa;
    }
    .order-item-active {
        font-weight: 500;
    }
    .order-badge-canceled {
        font-size: 10px;
        padding: 3px 6px;
    }
    .order-price-canceled {
        text-decoration: line-through;
        color: #aaa;
        font-size: 13px;
    }
    .order-price-zero {
        font-size: 11px;
        color: red;
        font-weight: bold;
    }
    .order-price-active {
        font-weight: 500;
    }
    .order-summary {
        margin-top: 15px;
        border-top: 1px solid #eee;
        padding-top: 10px;
        font-size: 13px;
    }
    .order-summary-flex {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        color: #666;
        flex-wrap: wrap;
    }
    .order-summary-total {
        color: #212529;
    }

    /* Total Consumed Bottom Card */
    .total-card {
        background: #fff;
        border: 2px solid #28a745;
        border-radius: 10px;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.05);
    }
    .total-card-label {
        font-size: 16px;
        font-weight: bold;
        color: #2e7d32;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .total-card-value {
        font-size: 22px;
        color: #1b5e20;
    }

    /* Warning Alerts */
    .alert-custom {
        gap: 8px;
    }

    /* Financial Tab */
    .finance-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }
    .finance-header {
        padding: 18px;
        border-bottom: 1px solid #f1f1f1;
    }
    .finance-title {
        margin: 0;
        font-weight: bold;
        color: #495057;
    }
    .finance-body {
        padding: 0;
    }
    .finance-table {
        margin: 0;
        font-size: 14px;
    }
    .finance-table-header-row {
        background: #fafafa;
        color: #888;
        font-size: 11px;
        text-transform: uppercase;
    }
    .finance-table-header {
        padding: 12px 18px;
        border-top: none;
    }
    .finance-cell {
        padding: 14px 18px;
        color: #666;
        vertical-align: middle;
    }
    .finance-cell-description {
        padding: 14px 18px;
        color: #495057;
        vertical-align: middle;
    }
    .finance-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .finance-value {
        padding: 14px 18px;
        font-weight: bold;
        text-align: right;
        vertical-align: middle;
        font-size: 15px;
    }
</style>

    <div class="dashboard-header">
        <h5 class="dashboard-title">
            <span class="material-icons header-icon">account_box</span>
            <strong>Consultar Aluno</strong>
        </h5>
        <a href="{{url('relatorio/consultar-pedidos')}}" class="material-icons" style="font-size: 1.5em; color: #333; text-decoration: none;" title="Voltar">
            keyboard_backspace
        </a>
    </div>
    <hr style="margin-top: 0; margin-bottom: 25px;">

    @if (session('sucesso'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            {!! session('sucesso') !!}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            {!! session('error') !!}
        </div>
    @endif

    <!-- Profile Header Card -->
    <div style="background: #195287; border-radius: 12px; padding: 20px; border: 1px solid #144370; margin-bottom: 25px;">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center" style="gap: 15px;">
                <div style="width: 55px; height: 55px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; flex-shrink: 0;">
                    <span class="material-icons" style="font-size: 32px;">school</span>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: bold; color: #ffffff;">{{ $cartaoCliente->nome }}</h4>
                    <p style="margin: 3px 0 0 0; color: rgba(255,255,255,0.8); font-size: 14px; display: flex; flex-wrap: wrap; gap: 15px;">
                        <span><strong style="color: #ffffff;">Código Cartão:</strong> {{ $codigo }}</span>
                        @if($cartaoCliente->telefone)
                            <span><strong style="color: #ffffff;">Telefone:</strong> {{ $cartaoCliente->telefone }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <div style="display: inline-block; text-align: left; padding: 10px 15px; min-width: 160px;">
                    <span style="color: #a5d6a7; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 2px;">Saldo Atual</span>
                    <h3 style="color: #ffffff; margin: 0; font-weight: bold; font-size: 24px;">R$ {{ number_format($cartaoCliente->valor_atual, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Pills / Tabs -->
    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist" style="border-bottom: 1px solid #dee2e6; padding-bottom: 10px; gap: 5px;">
        <li class="nav-item">
            <a class="nav-link active" id="pills-extrato-tab" data-toggle="pill" href="#pills-extrato" role="tab" aria-controls="pills-extrato" aria-selected="true" style="font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; padding: 10px 16px;">
                <span class="material-icons" style="font-size: 18px;">account_balance_wallet</span>
                Histórico Financeiro
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-pedidos-tab" data-toggle="pill" href="#pills-pedidos" role="tab" aria-controls="pills-pedidos" aria-selected="false" style="font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; padding: 10px 16px;">
                <span class="material-icons" style="font-size: 18px;">receipt_long</span>
                Histórico de Pedidos
            </a>
        </li>
    </ul>

    <!-- Tab Content Area -->
    <div class="tab-content" id="pills-tabContent">
        
        <!-- Tab: Pedidos -->
        <div class="tab-pane fade" id="pills-pedidos" role="tabpanel" aria-labelledby="pills-pedidos-tab">
            @if(COUNT($itensPedidoCliente) > 0)
                <?php $valorTotal = 0; ?>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($itensPedidoCliente as $id_pedido => $pedidos)
                        <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.03); transition: transform 0.2s;">
                            <!-- Order Header -->
                            <div style="background: #eef5fc; border-bottom: 1px solid #d0e1f5; padding: 12px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <div style="font-size: 13px; color: #495057;">
                                    <span style="font-weight: bold; font-size: 14px; color: #212529;">Pedido #{{ $pedidoCliente[$id_pedido]['id'] }}</span>
                                    @if($pedidoCliente[$id_pedido]['mesa'])
                                        <span style="margin-left: 8px; background: #e2e3e5; color: #383d41; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 11px;">Mesa {{ $pedidoCliente[$id_pedido]['mesa'] }}</span>
                                    @endif
                                    <div style="margin-top: 2px; color: #888;">
                                        {{ $pedidoCliente[$id_pedido]['dt_pedido'] }} às {{ $pedidoCliente[$id_pedido]['hora_pedido'] }}
                                    </div>
                                </div>
                                <div class="text-md-right" style="font-size: 13px; color: #888;">
                                    <div>Operador: <strong>{{ $pedidoCliente[$id_pedido]['usuario'] }}</strong></div>
                                    <div style="color: #195287; font-weight: bold; font-size: 14px; margin-top: 2px;">R$ {{ number_format($pedidoCliente[$id_pedido]['valor_total'], 2, ',', '.') }}</div>
                                </div>
                            </div>
                            
                            <!-- Order Items Table -->
                            <div style="padding: 15px 18px;">
                                <table class="table table-sm" style="margin: 0; font-size: 14px;">
                                    <thead>
                                        <tr style="color: #888; font-size: 11px; text-transform: uppercase; border-top: none;">
                                            <th style="border-top: none; padding-bottom: 6px;">Item do Pedido</th>
                                            <th style="border-top: none; padding-bottom: 6px;" class="text-right">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedidos as $item)
                                            <tr style="border-bottom: 1px solid #f9f9f9;">
                                                <td style="vertical-align: middle; padding: 8px 0; border-top: none;">
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        @if($item->status != 4 && (in_array(6, $perfisUsuario) || in_array(1, $perfisUsuario)))
                                                            <a href="{{url('pedido/confirmar-cancelamento-gerente2/'.$item->id_item.'/'.$codigo)}}" class="text-danger d-inline-flex align-items-center" title="Cancelar Pedido" style="text-decoration: none;">
                                                                <span class="material-icons" style="font-size: 20px;">delete_outline</span>
                                                            </a>
                                                        @endif
                                                        <span style="{{ $item->status == 4 ? 'text-decoration: line-through; color: #aaa;' : 'font-weight: 500;' }}">
                                                            {{ ($item->quantidade >= 1 ? intval($item->quantidade) : $item->quantidade) }}x 
                                                            {{ $item->categoria }}: {{ $item->nome_item }} 
                                                            <!-- <span style="font-size: 11px; color: #888;">({{ $item->tipo_cardapio }})</span> -->
                                                        </span>
                                                        @if($item->status == 4)
                                                            <span class="badge badge-danger" style="font-size: 10px; padding: 3px 6px;">Cancelado</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-right {{ $item->status == 4 ? 'text-danger' : '' }}" style="vertical-align: middle; padding: 8px 0; font-weight: 500; border-top: none;">
                                                    @if($item->status == 4)
                                                        <span style="text-decoration: line-through; color: #aaa; font-size: 13px;">R$ {{ number_format($item->valor_total_item, 2, ',', '.') }}</span>
                                                        <br><span style="font-size: 11px; color: red; font-weight: bold;">R$ 0,00</span>
                                                    @else
                                                        R$ {{ number_format($item->valor_total_item, 2, ',', '.') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Order Summary Details -->
                                <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px; font-size: 13px;">
                                    <div style="display: flex; justify-content: flex-end; gap: 20px; color: #666; flex-wrap: wrap;">
                                        <div style="color: #212529;">Total Geral: <strong>R$ {{ number_format($pedidoCliente[$id_pedido]['valor_total'], 2, ',', '.') }}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $valorTotal = $valorTotal + $item->valor_total; ?>
                    @endforeach
                </div>

                <!-- Bottom Total Card -->
                <div style="background: #fff; border: 2px solid #28a745; border-radius: 10px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; margin-top: 25px; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.05);">
                    <span style="font-size: 16px; font-weight: bold; color: #2e7d32; display: flex; align-items: center; gap: 6px;">
                        <span class="material-icons">monetization_on</span>
                        Total Consumido
                    </span>
                    <strong style="font-size: 22px; color: #1b5e20;">R$ {{ number_format($valorTotal, 2, ',', '.') }}</strong>
                </div>
            @else
                <div class="alert alert-warning d-flex align-items-center" style="gap: 8px;">
                    <span class="material-icons">info</span>
                    Nenhum pedido registrado.
                </div>
            @endif
        </div>

        <!-- Tab: Extrato -->
        <div class="tab-pane fade show active" id="pills-extrato" role="tabpanel" aria-labelledby="pills-extrato-tab">
            @if($historicoCartao->count() > 0)
                <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                    <div style="padding: 18px; border-bottom: 1px solid #f1f1f1;">
                        <h6 style="margin: 0; font-weight: bold; color: #495057;">Histórico Financeiro</h6>
                    </div>
                    <div style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table table-hover" style="margin: 0; font-size: 14px;">
                                <thead>
                                    <tr style="background: #fafafa; color: #888; font-size: 11px; text-transform: uppercase;">
                                        <th style="padding: 12px 18px; border-top: none;">Data / Hora</th>
                                        <th style="padding: 12px 18px; border-top: none;">Operação</th>
                                        <th style="padding: 12px 18px; border-top: none;">Descrição</th>
                                        <th style="padding: 12px 18px; border-top: none;" class="text-right">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historicoCartao as $h)
                                        <?php 
                                            $isPositive = $h->valor > 0;
                                            $valueColor = $isPositive ? '#2e7d32' : '#c62828';
                                            $bgColor = $isPositive ? '#e8f5e9' : '#ffebee';
                                            $icon = $isPositive ? 'add_circle' : 'remove_circle';
                                        ?>
                                        <tr class="linha">
                                            <td style="padding: 14px 18px; color: #666; vertical-align: middle;">
                                                {{ date('d/m/Y H:i', strtotime($h->data)) }}
                                            </td>
                                            <td style="padding: 14px 18px; vertical-align: middle;">
                                                <span class="badge" style="background: {{ $bgColor }}; color: {{ $valueColor }}; padding: 5px 10px; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                   <span class="material-icons" style="font-size: 14px;">{{ $icon }}</span>
                                                   {{ $h->tipo_pagamento }}
                                                </span>
                                            </td>
                                            <td style="padding: 14px 18px; color: #495057; vertical-align: middle;">
                                                {{ $h->observacao }}
                                            </td>
                                            <td style="padding: 14px 18px; font-weight: bold; text-align: right; color: {{ $valueColor }}; vertical-align: middle; font-size: 15px;">
                                                {{ $isPositive ? '+' : '-' }} R$ {{ number_format(abs($h->valor), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning d-flex align-items-center" style="gap: 8px;">
                    <span class="material-icons">info</span>
                    Nenhuma movimentação registrada.
                </div>
            @endif
        </div>

    </div>
    <br><br><br>
@endsection

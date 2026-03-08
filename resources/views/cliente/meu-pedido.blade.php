@extends('layouts.default')
@section('conteudo')
<style>
    * {
        box-sizing: border-box;
    }

    a:hover {
        text-decoration: none;
    }

    .meu-pedido-container {
        max-width: 100%;
        padding: 0;
        display: flex;
        justify-content: center;
    }

    .header-pedido {
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        padding: 2em;
        border-radius: 10px;
        margin-bottom: 2em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-pedido h2 {
        margin: 0;
        font-size: 1.8em;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-pedido .material-icons {
        font-size: 2.2em;
    }


    .ticket-comprovante {
        background: #dbf3ff;
        width: 100%;
        max-width: 500px;
        /* border: 2px solid #333; */
        /* border-radius: 8px; */
        padding: 0;
        /* box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); */
        position: relative;
        overflow: hidden;
        margin-bottom: 2em;
    }

    /* Efeito de perfuração para recorte */
    .ticket-comprovante::before,
    .ticket-comprovante::after {
        content: '';
        position: absolute;
        left: 0;
        width: 100%;
        height: 20px;
        background: repeating-linear-gradient(90deg,
                transparent,
                transparent 10px,
                #fff 10px,
                #fff 20px);
        background-size: 20px 100%;
        z-index: 2;
    }

    .ticket-comprovante::before {
        top: -10px;
        box-shadow: inset 0 10px 10px -10px rgba(0, 0, 0, 0.3);
    }

    .ticket-comprovante::after {
        bottom: -10px;
        box-shadow: inset 0 -10px 10px -10px rgba(0, 0, 0, 0.3);
    }

    .ticket-conteudo {
        padding: 2em;
        padding-top: 2.5em;
        padding-bottom: 2.5em;
    }

    .ticket-logo {
        text-align: center;
        /* margin-bottom: 1.5em; */
        font-size: 1.4em;
        font-weight: 700;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5em;
    }

    .ticket-logo .material-icons {
        color: #3153e7;
        font-size: 1.6em;
    }

    .ticket-divisor {
        border-top: 2px dashed #333;
        margin: 1em 0;
    }

    .ticket-item {
        margin-bottom: 1.2em;
        text-align: center;
    }

    .ticket-item-label {
        font-size: 0.8em;
        text-transform: uppercase;
        color: #999;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 0.3em;
    }

    .ticket-item-valor {
        font-size: 1.1em;
        color: #333;
        font-weight: 700;
    }

    .ticket-numero-pedido {
        font-size: 1.3em;
        font-weight: 700;
        color: #3153e7;
        /* font-family: 'Courier New', monospace; */
        letter-spacing: 2px;
    }

    .ticket-data {
        font-size: 0.9em;
        /* color: #666; */
    }

    .ticket-cliente {
        font-size: 1em;
        font-weight: 600;
        color: #333;
    }

    .ticket-itens {
        margin: 1.5em 0;
        text-align: left;
        border-top: 1px dotted #999;
    }

    .ticket-item-linha {
        display: flex;
        justify-content: space-between;
        padding: 0.6em 0;
        border-bottom: 1px dotted #999;
        font-size: 0.85em;
    }

    .ticket-item-linha:last-child {
        border-bottom: none;
    }

    .ticket-item-nome {
        flex: 1;
        font-weight: 600;
        color: #333;
    }

    .ticket-item-qtd {
        color: #666;
        margin: 0 0.5em;
        text-align: center;
        min-width: 30px;
    }

    .ticket-item-valor {
        /* color: #3153e7; */
        font-weight: 600;
        text-align: right;
        min-width: 60px;
    }

    .ticket-totais {
        margin-top: 1.5em;
        /* padding-top: 1.5em; */
        /* border-top: 2px solid #333; */
    }

    .ticket-total-linha {
        display: flex;
        justify-content: space-between;
        font-size: 0.95em;
        margin-bottom: 0.5em;
    }

    .ticket-total-final {
        display: flex;
        justify-content: space-between;
        font-size: 1.3em;
        font-weight: 700;
        color: #333;
        border-top: 1px dotted #999;
        padding-top: 0.8em;
        margin-top: 0.8em;
    }

    .ticket-qrcode {
        text-align: center;
        margin: 1.5em 0;
        padding: 1.5em 0;
        /* border-top: 2px dashed #333; */
        /* border-bottom: 2px dashed #333; */
    }

    .ticket-qrcode-img {
        max-width: 180px;
        height: auto;
        margin-bottom: 0.8em;
    }

    .ticket-qrcode-label {
        font-size: 0.8em;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .ticket-rodape {
        text-align: center;
        font-size: 0.8em;
        color: #999;
        margin-top: 1em;
        padding-top: 1em;
        border-top: 1px dotted #999;
    }

    .btn-imprimir {
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        border: none;
        padding: 0.9em 2em;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        font-size: 0.95em;
        margin-top: 1em;
    }

    .btn-imprimir:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(49, 83, 231, 0.3);
    }

    .alerta {
        padding: 1em;
        border-radius: 8px;
        margin-bottom: 1.5em;
        display: flex;
        align-items: flex-start;
        gap: 0.8em;
    }

    .alerta.sucesso {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #2e7d32;
    }

    .alerta.erro {
        background: #ffebee;
        color: #c62828;
        border-left: 4px solid #c62828;
    }

    .alerta .material-icons {
        font-size: 1.3em;
        flex-shrink: 0;
        margin-top: 0.1em;
    }

    @media print {
        body {
            background: white;
        }

        .header-pedido,
        .btn-imprimir {
            display: none;
        }

        .meu-pedido-container {
            max-width: 80mm;
            margin: 0 auto;
        }

        .ticket-comprovante {
            box-shadow: none;
            max-width: 100%;
        }
    }

    @media (max-width: 600px) {
        .ticket-conteudo {
            padding: 1.5em;
        }

        .ticket-numero-pedido {
            font-size: 1.1em;
        }

        .ticket-item-linha {
            font-size: 0.8em;
        }
    }
</style>
<div>
    <h5>
        <i class="material-icons icone">receipt</i>
        Detalhes do Pedido

        <a href="{{url('cliente/meus-pedidos')}}" class="material-icons float-right" style="font-size: 1.3em !important; color: #333;">
            keyboard_backspace
        </a>
    </h5>
</div>
<hr>
<div>
    @if(session('sucesso'))
    <div class="alerta sucesso">
        <i class="material-icons">check_circle</i>
        <p>{!! session('sucesso') !!}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="alerta erro">
        <i class="material-icons">error</i>
        <p>{!! session('error') !!}</p>
    </div>
    @endif

    <div class="meu-pedido-container">
        <div class="ticket-comprovante">
            <div class="ticket-conteudo">

                <div class="ticket-logo">
                    {{ $pedidos[0]->nome_cliente }}
                </div>

                <div class="ticket-item">
                    <div class="ticket-numero-pedido">Pedido #{{ str_pad($pedidos[0]->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>

                <div class="ticket-item">
                    <div class="ticket-item-label">Data e Hora</div>
                    <div class="ticket-data">{{ date('d/m/Y H:i', strtotime($pedidos[0]->dt_pedido)) }}h</div>
                </div>

                <!-- Itens do Pedido -->
                <div class="ticket-itens">
                    @foreach($pedidos as $pedido)
                    <div class="ticket-item-linha">
                        <span class="ticket-item-nome">{{ $pedido->unid == 1 ? intval($pedido->quantidade) : $pedido->quantidade }}x {{ $pedido->nome_item }}</span>
                        <span class="ticket-item-valor">R$ {{ number_format($pedido->valor_total_item, 2, ',', '.') }}</span>
                    </div>
                    @if($pedido->observacao)
                    <div style="font-size: 0.75em; color: #ff9800; margin-top: -0.3em; margin-bottom: 0.5em;">
                        * {{ $pedido->observacao }}
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="ticket-totais">
                    <?php $subtotal = $pedidos->where('status', '!=', 4)->sum('valor_total_item');
                    $taxa = $pedidos[0]->taxa_servico ?? 0; ?>

                    @if($taxa > 0)
                    <div class="ticket-total-linha">
                        <span>Subtotal:</span>
                        <span>R$ {{ number_format($subtotal - $taxa, 2, ',', '.') }}</span>
                    </div>
                    <div class="ticket-total-linha">
                        <span>Taxa de Serviço:</span>
                        <span>R$ {{ number_format($taxa, 2, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="ticket-total-final">
                        <span>TOTAL:</span>
                        <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="ticket-qrcode">
                    {!! $qrCode !!}
                    <div class="ticket-qrcode-label">Escaneie para receber o pedido</div>
                </div>



                <div class="ticket-rodape">
                    Obrigado pela sua compra!<br>
                    {{ config('app.NOME_SISTEMA') }}
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
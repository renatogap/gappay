@extends('layouts.default')
@section('conteudo')
<style>
    * {
        box-sizing: border-box;
    }

    a:hover {
        text-decoration: none;
    }

    .meus-pedidos-container {
        max-width: 100%;
        padding: 0;
    }

    .header-pedidos {
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

    .header-pedidos h2 {
        margin: 0;
        font-size: 1.8em;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-pedidos .material-icons {
        font-size: 2.2em;
    }

    .voltar-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.3s;
        padding: 0.5em 1em;
        border-radius: 6px;
    }

    .voltar-btn:hover {
        opacity: 0.8;
        text-decoration: none;
    }

    .voltar-btn .material-icons {
        font-size: 1.2em;
    }

    .alerta-vazio {
        background: #f0f4ff;
        border: 2px dashed #3153e7;
        border-radius: 12px;
        padding: 3em;
        text-align: center;
        color: #666;
    }

    .alerta-vazio .material-icons {
        font-size: 4em;
        color: #3153e7;
        margin-bottom: 1em;
        opacity: 0.7;
    }

    .alerta-vazio h3 {
        color: #333;
        font-size: 1.3em;
        margin: 0.5em 0;
    }

    .alerta-vazio p {
        margin: 0.5em 0;
        font-size: 0.95em;
    }

    .btn-fazer-pedido {
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        border: none;
        padding: 0.9em 2em;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1em;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        text-decoration: none;
    }

    .btn-fazer-pedido:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(49, 83, 231, 0.3);
        text-decoration: none;
    }

    .pedidos-lista {
        display: grid;
        gap: 1.5em;
    }

    .pedido-card {
        background: white;
        border-radius: 12px;
        padding: 1.8em;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #3153e7;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: pointer;
    }

    .pedido-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(49, 83, 231, 0.15);
    }

    .pedido-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.2em;
        flex-wrap: wrap;
        gap: 1em;
    }

    .pedido-numero {
        font-size: 1.1em;
        font-weight: 700;
        color: #333;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }

    .pedido-numero .material-icons {
        color: #3153e7;
        font-size: 1.3em;
    }

    .pedido-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        padding: 0.6em 1.2em;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85em;
        background: #fff4e6;
        color: #ff9800;
    }

    .pedido-status .material-icons {
        font-size: 1.2em;
    }

    .pedido-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5em;
        /* border-bottom: 1px solid #f0f0f0; */
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.4em;
    }

    .info-label {
        font-size: 0.85em;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        font-weight: 600;
    }

    .info-valor {
        font-size: 1.1em;
        color: #333;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }

    .info-valor .material-icons {
        color: #3153e7;
        font-size: 1.2em;
    }

    .pedido-itens {
        background: #f8f9ff;
        border-radius: 8px;
        padding: 1em;
        margin-bottom: 1.2em;
    }

    .pedido-itens-titulo {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.8em;
        display: flex;
        align-items: center;
        gap: 0.5em;
        font-size: 0.95em;
    }

    .pedido-itens-titulo .material-icons {
        color: #3153e7;
        font-size: 1.2em;
    }

    .item {
        display: flex;
        justify-content: space-between;
        padding: 0.6em 0;
        /* border-bottom: 1px solid rgba(0, 0, 0, 0.05); */
        font-size: 0.9em;
    }

    .item:last-child {
        border-bottom: none;
    }

    .item-nome {
        color: #555;
        flex: 1;
    }

    .item-quantidade {
        color: #999;
        margin: 0 0.8em;
        font-weight: 600;
    }

    .item-valor {
        color: #3153e7;
        font-weight: 600;
        min-width: 80px;
        text-align: right;
    }

    .pedido-totais {
        display: flex;
        flex-direction: column;
        gap: 0.8em;
        padding-top: 1em;
    }

    .total-linha {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95em;
        color: #666;
    }

    .total-linha.destaque {
        font-size: 1.2em;
        font-weight: 700;
        color: #333;
        border-top: 2px solid #f0f0f0;
        padding-top: 1em;
        margin-top: 0.5em;
    }

    .total-valor {
        font-weight: 600;
        color: #3153e7;
    }

    .pedido-acoes {
        display: flex;
        gap: 1em;
        margin-top: 1.5em;
        padding-top: 1.5em;
        border-top: 1px solid #f0f0f0;
    }

    .btn-pedido {
        flex: 1;
        padding: 0.9em 1.5em;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5em;
        font-size: 0.95em;
    }

    .btn-visualizar {
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        text-decoration: none;
    }

    .btn-visualizar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(49, 83, 231, 0.3);
    }

    .btn-cancelar {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ef5350;
    }

    .btn-cancelar:hover {
        background: #ffcdd2;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .meus-pedidos-container>* {
        animation: fadeInUp 0.6s ease forwards;
    }

    .header-pedidos {
        animation-delay: 0.1s;
    }

    .pedidos-lista {
        animation-delay: 0.2s;
    }

    .pedido-card:nth-child(n) {
        animation-delay: calc(0.2s + (var(--index) * 0.1s));
    }

    @media (max-width: 768px) {
        .header-pedidos {
            flex-direction: column;
            text-align: center;
            gap: 1em;
        }

        .header-pedidos h2 {
            flex-direction: column;
            gap: 0.5em;
        }

        .pedido-header {
            /* flex-direction: column; */
            /* align-items: flex-start; */
        }

        .pedido-info-grid {
            grid-template-columns: 1fr;
        }

        .pedido-acoes {
            flex-direction: column;
        }

        .pedido-card {
            padding: 1.2em;
        }
    }
</style>

<div class="meus-pedidos-container">
    <h4>
        <i class="material-icons">shopping_cart</i>
        Meus Pedidos
        <a href="{{url('cliente/home')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h4>
    <hr>

    @if($pedidos->isEmpty())
    <div class="alerta-vazio">
        <i class="material-icons">check_circle</i>
        <h3>Nenhum pedido pendente</h3>
        <p>Você não possui pedidos aguardando processamento no momento.</p>
        <a href="{{ url('cliente/cardapio/1') }}" class="btn-fazer-pedido">
            <i class="material-icons">add_shopping_cart</i>
            Fazer Novo Pedido
        </a>
    </div>
    @else
    <div class="pedidos-lista">
        @forelse($pedidos as $index => $pedido)
        <a href="{{ url('cliente/meu-pedido/'.$pedido->id) }}" class="pedido-card" style="--index: {{ $loop->index }}">
            <div class="pedido-header">
                <div class="pedido-numero">
                    <i class="material-icons">receipt</i>
                    Pedido #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}
                </div>
                <div class="pedido-status">
                    <i class="material-icons">schedule</i>
                    Pendente
                </div>
            </div>

            <div class="pedido-info-grid">
                <div class="info-item">
                    <span class="info-label">Data do Pedido</span>
                    <span class="info-valor">
                        <i class="material-icons">calendar_today</i>
                        {{ date('d/m/Y', strtotime($pedido->dt_pedido)) }}
                        {{ date('H:i', strtotime($pedido->dt_pedido)) }}
                    </span>
                </div>
            </div>



            <div class="pedido-totais">
                @if($pedido->taxa_servico > 0)
                <div class="total-linha">
                    <span>Subtotal:</span>
                    <span class="total-valor">R$ {{ number_format($pedido->valor_total - $pedido->taxa_servico, 2, ',', '.') }}</span>
                </div>
                <div class="total-linha">
                    <span>Taxa de Serviço:</span>
                    <span class="total-valor">R$ {{ number_format($pedido->taxa_servico, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-linha destaque">
                    <span>Valor Total:</span>
                    <span class="total-valor">R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>
        </a>
        @empty
        <div class="alerta-vazio">
            <i class="material-icons">check_circle</i>
            <h3>Nenhum pedido pendente</h3>
            <p>Você não possui pedidos aguardando processamento no momento.</p>
        </div>
        @endempty
    </div>
    @endif
</div>

<script>
    function confirmarCancelamento(pedidoId) {
        if (confirm('Tem certeza que deseja cancelar este pedido?')) {
            // Aqui você pode adicionar a lógica para cancelar o pedido
            // window.location.href = `/cliente/pedido/${pedidoId}/cancelar`;
            alert('Funcionalidade de cancelamento em desenvolvimento');
        }
    }
</script>
@endsection
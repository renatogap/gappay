@extends('layouts.default')
@section('conteudo')
<style>
    * {
        box-sizing: border-box;
    }

    .home-container {
        max-width: 100%;
        padding: 0;
    }

    .boas-vindas {
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        padding: 2em;
        border-radius: 10px;
        margin-bottom: 2em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .boas-vindas h2 {
        margin: 0 0 0.5em 0;
        font-size: 1.6em;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .boas-vindas .material-icons {
        font-size: 2em;
    }

    .boas-vindas p {
        margin: 0;
        opacity: 0.95;
        font-size: 0.95em;
    }

    #saldo {
        font-size: 1.5em;
        font-weight: bold;
        letter-spacing: 2px;
    }

    .saldo-container {
        margin: 2em 0;
    }

    .saldo-card {
        background: white;
        border-radius: 12px;
        padding: 2em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        position: relative;
        overflow: hidden;
    }

    .saldo-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3153e7 0%, #043795 100%);
    }

    .saldo-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #999;
        font-weight: 600;
        margin-bottom: 0.8em;
        font-size: 0.95em;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .saldo-label .material-icons {
        font-size: 1.2em;
        color: #3153e7;
    }

    .saldo-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1em;
    }

    .saldo-valor {
        font-size: 2.5em;
        font-weight: bold;
        color: #333;
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
    }

    #saldo-oculto {
        font-size: 1.5em;
        font-weight: bold;
        letter-spacing: 2px;
    }

    #btn-toggle-saldo {
        /* background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        border: none;
        cursor: pointer;
        color: white;
        font-size: 1.3em;
        padding: 12px 14px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3); */
        transition: all 0.3s ease;
        float: right;
        border: none;
        margin-top: 15px;
    }

    #btn-toggle-saldo:hover {
        transform: scale(1.1);
    }

    #btn-toggle-saldo:active {
        transform: scale(0.95);
    }

    .atalhos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1.5em;
        margin-bottom: 3em;
    }

    .atalho-card {
        background: white;
        border-radius: 12px;
        padding: 1.8em;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 2px solid transparent;
        position: relative;
        overflow: visible;
    }

    .badge-pedidos-pendentes {
        position: absolute;
        bottom: -8px;
        right: -8px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9em;
        /* box-shadow: 0 3px 10px rgba(255, 107, 107, 0.4); */
        border: 3px solid white;
    }

    .atalho-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        /* background: linear-gradient(90deg, #3153e7 0%, #043795 100%); */
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .atalho-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(102, 126, 234, 0.25);
        border-color: #3153e7;
        text-decoration: none;
    }

    .atalho-card:hover::before {
        transform: scaleX(1);
    }

    .atalho-icone {
        width: 70px;
        height: 70px;
        margin: 0 auto 1em auto;
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s ease;
    }

    .atalho-card:hover .atalho-icone {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .atalho-icone .material-icons {
        font-size: 2.5em;
    }

    .atalho-titulo {
        font-weight: 600;
        color: #333;
        font-size: 0.95em;
        margin-bottom: 0.5em;
    }

    .atalho-descricao {
        font-size: 0.8em;
        color: #999;
        display: none;
    }

    .atalho-card:hover .atalho-descricao {
        display: block;
        color: #3153e7;
        font-weight: 500;
    }

    .sair-container {
        margin-top: 3em;
        display: flex;
        justify-content: center;
    }

    .btn-sair {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
        border: none;
        padding: 0.9em 2.5em;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.8em;
        font-size: 1em;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        text-decoration: none;
    }

    .btn-sair:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    }

    .btn-sair:active {
        transform: scale(0.95);
    }

    .btn-sair .material-icons {
        font-size: 1.3em;
    }

    @media (max-width: 768px) {
        .atalhos-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1em;
        }

        .atalho-card {
            padding: 1.3em;
        }

        .atalho-icone {
            width: 55px;
            height: 55px;
        }

        .atalho-icone .material-icons {
            font-size: 2em;
        }

        .saldo-valor,
        .saldo-oculto {
            font-size: 2em;
        }

        .boas-vindas h2 {
            font-size: 1.3em;
        }

        .saldo-card {
            padding: 1.5em;
        }
    }

    @media (max-width: 480px) {

        .saldo-valor,
        .saldo-oculto {
            font-size: 1.8em;
        }
    }

    /* Animação ao carregar */
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

    .home-container>* {
        animation: fadeInUp 0.6s ease forwards;
    }

    .boas-vindas {
        animation-delay: 0.1s;
    }

    .saldo-container {
        animation-delay: 0.2s;
    }

    .atalhos-grid {
        animation-delay: 0.3s;
    }

    .sair-container {
        animation-delay: 0.4s;
    }
</style>

<div class="home-container">
    <div class="boas-vindas">
        <h2>
            <i class="material-icons">person</i>
            {{ $cartaoCliente->nome }}
        </h2>
        <p>Bem-vindo(a) de volta! Acompanhe seu consumo abaixo.</p>
    </div>

    <div class="alert alert-info">
        Saldo atual:

        <a href="#" id="btn-toggle-saldo" title="Mostrar/Esconder saldo">
            <i class="material-icons">visibility</i>
        </a>

        <br>

        <b id="saldo">R$ {{ number_format($cartaoCliente->valor_atual, 2, ',', '.') }}</b>
        <span id="saldo-oculto" style="display:none;">R$ •••••</span>
    </div>

    <div class="atalhos-grid">
        <a href="{{ url('cliente/meus-pedidos') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">shopping_cart</i>
            </div>
            <div class="atalho-titulo">Meus Pedidos</div>
            @if($pedidosPendentes > 0)
            <div class="badge-pedidos-pendentes" title="{{ $pedidosPendentes }} pedido(s) pendente(s)">
                {{ $pedidosPendentes }}
            </div>
            @endif
        </a>

        <a href="{{ url('cliente/extrato') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">receipt_long</i>
            </div>
            <div class="atalho-titulo">Extrato</div>
        </a>

        <a href="{{ url('cliente/pedidos') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">history</i>
            </div>
            <div class="atalho-titulo">Histórico de Consumo</div>
        </a>

        <a href="{{ url('cliente/cardapio/1') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">restaurant_menu</i>
            </div>
            <div class="atalho-titulo">Cardápio</div>
        </a>

        <a href="{{ url('cliente/recarga') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">currency_exchange</i>
            </div>
            <div class="atalho-titulo">Recarga</div>
        </a>
    </div>

    <div class="sair-container">
        <a href="{{ url('cliente/logout') }}" class="btn-sair">
            <i class="material-icons">power_settings_new</i>
            Sair da Conta
        </a>
    </div>
</div>

<script>
    document.getElementById('btn-toggle-saldo').addEventListener('click', function() {
        const saldo = document.getElementById('saldo');
        const saldoOculto = document.getElementById('saldo-oculto');
        const icon = document.getElementById('icon-toggle');

        if (saldo.style.display === 'none') {
            saldo.style.display = 'block';
            saldoOculto.style.display = 'none';
            icon.textContent = 'visibility';
        } else {
            saldo.style.display = 'none';
            saldoOculto.style.display = 'block';
            icon.textContent = 'visibility_off';
        }
    });
</script>
@endsection
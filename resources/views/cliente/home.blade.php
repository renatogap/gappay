@extends('layouts.default-cliente-new')
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

    .seletor-aluno-container {
        background: white;
        border-radius: 12px;
        padding: 1em 1.5em;
        margin-bottom: 1.5em;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 1em;
        border-left: 4px solid #3153e7;
    }

    .seletor-label {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #666;
        font-size: 0.85em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        margin: 0;
    }

    .seletor-label .material-icons {
        font-size: 1.1em;
        color: #3153e7;
    }

    .seletor-wrapper {
        position: relative;
        flex: 1;
    }

    .seletor-aluno {
        width: 100%;
        appearance: none;
        background: #f5f7ff;
        border: 2px solid #e8ecff;
        border-radius: 8px;
        padding: 0.6em 2.5em 0.6em 1em;
        font-size: 0.95em;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        transition: border-color 0.2s ease;
        outline: none;
    }

    .seletor-aluno:hover,
    .seletor-aluno:focus {
        border-color: #3153e7;
        background: #eef1ff;
    }

    .seletor-icone {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #3153e7;
        pointer-events: none;
        font-size: 1.2em;
    }

    .seletor-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #eef1ff;
        border: 1.5px solid #3153e7;
        border-radius: 10px;
        padding: 12px 14px;
        cursor: pointer;
        width: 100%;
        transition: background 0.15s;
    }
    .seletor-trigger:active { background: #dde2ff; }
    .seletor-trigger-info { display: flex; align-items: center; gap: 10px; }
    .seletor-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: #3153e7;
        color: white;
        font-size: 13px;
        font-weight: 600;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .seletor-nome { font-size: 15px; font-weight: 600; color: #333; }
    .seletor-sub  { font-size: 12px; color: #999; }

    .bs-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 9999;
        align-items: flex-end;
    }
    .bs-overlay.open { display: flex; }
    .bs-sheet {
        background: white;
        border-radius: 20px 20px 0 0;
        width: 100%;
        padding-bottom: env(safe-area-inset-bottom, 16px);
        animation: bsSlideUp 0.28s cubic-bezier(0.25,0.8,0.25,1);
    }
    @keyframes bsSlideUp {
        from { transform: translateY(100%); }
        to   { transform: translateY(0); }
    }
    .bs-handle {
        width: 38px; height: 4px;
        background: #ddd;
        border-radius: 2px;
        margin: 12px auto 0;
    }
    .bs-title {
        display: flex; align-items: center; gap: 6px;
        font-size: 12px; font-weight: 600;
        color: #999; text-transform: uppercase; letter-spacing: 0.6px;
        padding: 14px 20px 10px;
        border-bottom: 1px solid #f0f0f0;
    }
    .bs-option {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 20px;
        width: 100%;
        background: none; border: none;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer; transition: background 0.1s;
    }
    .bs-option:active { background: #f5f7ff; }
    .bs-option.selected { background: #eef1ff; }
    .bs-opt-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: #3153e7;
        color: white;
        font-size: 14px; font-weight: 600;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .bs-opt-nome { font-size: 15px; font-weight: 600; color: #333; }
    .bs-option-form:last-child .bs-option { border-bottom: none; }

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
            {{-- {{ $responsavel->nome }} --}}
        </h2>
        <p>Bem-vindo(a) de volta! Acompanhe seu consumo abaixo.</p>
    </div>

    {{-- Só exibe se houver mais de 1 aluno --}}
    @if($alunos->count() > 1)
        <button class="seletor-trigger" id="open-bs" type="button" style="margin-bottom: 1.5em;">
            <div class="seletor-trigger-info">
                <div class="seletor-avatar" id="trigger-avatar">
                    {{ strtoupper(substr($cartaoCliente->nome, 0, 1)) }}{{ strtoupper(substr(explode(' ', $cartaoCliente->nome)[1] ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="seletor-nome" id="trigger-nome">{{ $cartaoCliente->nome }}</div>
                    <div class="seletor-sub">Toque para alterar aluno</div>
                </div>
            </div>
            <i class="material-icons" style="color:#3153e7">expand_less</i>
        </button>

        <div class="bs-overlay" id="bs-overlay">
            <div class="bs-sheet">
                <div class="bs-handle"></div>
                <div class="bs-title">
                    <i class="material-icons" style="font-size:16px">group</i>
                    Selecionar aluno
                </div>
                @foreach($alunos as $aluno)
                <form action="{{ url('cliente/trocar-aluno') }}" method="POST" class="bs-option-form">
                    @csrf
                    <input type="hidden" name="aluno_id" value="{{ $aluno->id }}">
                    <button type="submit" class="bs-option {{ $aluno->id == $cartaoCliente->id ? 'selected' : '' }}">
                        <div class="bs-opt-avatar">
                            {{ strtoupper(substr($aluno->nome, 0, 1)) }}{{ strtoupper(substr(explode(' ', $aluno->nome)[1] ?? 'A', 0, 1)) }}
                        </div>
                        <div style="flex:1; text-align:left">
                            <div class="bs-opt-nome">{{ $aluno->nome }}</div>
                        </div>
                        @if($aluno->id == $cartaoCliente->id)
                        <i class="material-icons" style="color:#3153e7">check</i>
                        @endif
                    </button>
                </form>
                @endforeach
            </div>
        </div>
    @endif

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

        <a href="{{ url('cliente/alunos') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">person_add</i>
            </div>
            <div class="atalho-titulo">Alunos</div>
        </a>

        <a href="{{ url('cliente/meus-dados') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">settings</i>
            </div>
            <div class="atalho-titulo">Meus Dados</div>
        </a>

        <a href="{{ url('cliente/senha/redefinir') }}" class="atalho-card">
            <div class="atalho-icone">
                <i class="material-icons">lock_reset</i>
            </div>
            <div class="atalho-titulo">Alterar Senha</div>
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

    document.getElementById('open-bs').addEventListener('click', function() {
        document.getElementById('bs-overlay').classList.add('open');
    });
    document.getElementById('bs-overlay').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
</script>
@endsection
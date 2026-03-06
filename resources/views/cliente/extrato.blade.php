@extends('layouts.default')
@section('conteudo')
<style>
    .extrato-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2em;
        border-radius: 10px;
        margin-bottom: 2em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .extrato-header h2 {
        margin: 0;
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .extrato-header .material-icons {
        margin-right: 15px;
        font-size: 2em;
    }

    .saldo-resumo {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5em;
        margin-top: 1.5em;
    }

    .saldo-card {
        background: rgba(255, 255, 255, 0.15);
        padding: 1.5em;
        border-radius: 8px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .saldo-card label {
        display: block;
        font-size: 0.9em;
        opacity: 0.9;
        margin-bottom: 0.5em;
        font-weight: 500;
    }

    .saldo-card .valor {
        font-size: 1.8em;
        font-weight: bold;
    }

    .saldo-card .material-icons {
        font-size: 1.5em;
        margin-right: 0.5em;
        vertical-align: middle;
    }

    .transacao-container {
        margin-top: 2em;
    }

    .transacao-container h4 {
        color: #333;
        margin-bottom: 1.5em;
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .transacao-container h4 .material-icons {
        color: #667eea;
        margin-right: 10px;
        font-size: 1.5em;
    }

    .transacao-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.2em;
        margin-bottom: 1em;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .transacao-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .transacao-info {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 1.5em;
    }

    .transacao-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5em;
        color: white;
        flex-shrink: 0;
    }

    .transacao-icon.entrada {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .transacao-icon.saida {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    }

    .transacao-details {
        flex: 1;
    }

    .transacao-details .descricao {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.3em;
    }

    .transacao-details .subtitulo {
        font-size: 0.85em;
        color: #999;
        margin-bottom: 0.2em;
    }

    .transacao-details .tipo {
        font-size: 0.8em;
        display: inline-block;
        background: #f0f0f0;
        padding: 0.3em 0.8em;
        border-radius: 20px;
        color: #666;
    }

    .transacao-valor {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-left: 2em;
    }

    .transacao-valor .valor {
        font-size: 1.3em;
        font-weight: bold;
        margin-bottom: 0.5em;
    }

    .transacao-valor .valor.entrada {
        color: #11998e;
    }

    .transacao-valor .valor.saida {
        color: #ff6b6b;
    }

    .transacao-valor .data {
        font-size: 0.85em;
        color: #999;
    }

    .sem-transacoes {
        text-align: center;
        padding: 3em;
        color: #999;
    }

    .sem-transacoes .material-icons {
        font-size: 3em;
        opacity: 0.5;
        margin-bottom: 1em;
        display: block;
    }

    .filtro-container {
        display: flex;
        gap: 1em;
        margin-bottom: 2em;
        align-items: center;
    }

    .filtro-container input,
    .filtro-container select {
        padding: 0.7em;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: inherit;
    }

    .filtro-container input {
        flex: 1;
    }

    .filtro-container button {
        background: #667eea;
        color: white;
        border: none;
        padding: 0.7em 1.5em;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5em;
        transition: background 0.3s;
    }

    .filtro-container button:hover {
        background: #5568d3;
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

    @media (max-width: 768px) {
        .saldo-resumo {
            grid-template-columns: 1fr;
        }

        .transacao-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .transacao-valor {
            width: 100%;
            align-items: flex-start;
            margin-left: 0;
            margin-top: 1em;
        }

        .filtro-container {
            flex-direction: column;
        }

        .filtro-container input,
        .filtro-container select {
            width: 100%;
        }

        .btn-search {
            background: linear-gradient(135deg, #3e5fe1 0%, #040c46 100%);
        }
    }
</style>

<h5>
    <i class="material-icons icone">history</i>
    Histórico de Transações

    <a href="{{ url('cliente/home') }}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>
<hr>
<form method="GET" action="{{ url('cliente/extrato') }}">
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
            <button type="submit" class="btn btn-primary btn-block btn-search"><i class="material-icons icone">search</i> Buscar</button>
        </div>
    </div>
</form>


<div class="transacao-container">
    @if($extrato->count() > 0)
    @foreach($extrato as $transacao)
    @php
    $isEntrada = $transacao->valor > 0;
    $dataFormatada = \Carbon\Carbon::parse($transacao->data)->format('d/m/Y H:i');
    $dataRelativa = \Carbon\Carbon::parse($transacao->data)->diffForHumans();
    @endphp

    <div class="transacao-item">
        <div class="transacao-info">
            <div class="transacao-icon {{ $isEntrada ? 'entrada' : 'saida' }}">
                <i class="material-icons">
                    {{ $isEntrada ? 'add_circle' : 'remove_circle' }}
                </i>
            </div>

            <div class="transacao-details">
                <div class="descricao">{{ $transacao->observacao ?? 'Sem descrição' }}</div>
                <!-- <span class="subtitulo float-right">{{ $dataRelativa }}</span> -->
                <div class="subtitulo">{{ $dataFormatada }}</div>
                <span class="tipo">{{ $transacao->tipo_pagamento }}</span>
            </div>
        </div>

        <div class="transacao-valor">
            <div class="valor {{ $isEntrada ? 'entrada' : 'saida' }}">
                {{ $isEntrada ? '+' : '-' }} R$ {{ number_format(abs($transacao->valor), 2, ',', '.') }}
            </div>

        </div>
    </div>
    @endforeach
    @else
    <div class="sem-transacoes">
        <i class="material-icons">inbox</i>
        <p>Nenhuma transação encontrada</p>
    </div>
    @endif
</div>

<script>
    document.getElementById('filtro-descricao')?.addEventListener('input', function(e) {
        const filtro = e.target.value.toLowerCase();
        document.querySelectorAll('.transacao-item').forEach(item => {
            const descricao = item.querySelector('.descricao').textContent.toLowerCase();
            item.style.display = descricao.includes(filtro) ? 'flex' : 'none';
        });
    });

    // Print styling
    window.addEventListener('beforeprint', function() {
        document.querySelectorAll('.voltar-btn, .filtro-container').forEach(el => {
            el.style.display = 'none';
        });
    });

    window.addEventListener('afterprint', function() {
        document.querySelectorAll('.voltar-btn, .filtro-container').forEach(el => {
            el.style.display = '';
        });
    });
</script>
@endsection
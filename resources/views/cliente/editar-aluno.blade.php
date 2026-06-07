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
    <h4>
        <i class="material-icons">edit</i>
        Editar dados de Aluno
        <a href="{{url('cliente/alunos')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h4>
    <hr>
    <div class="boas-vindas">
        <h2>
            <i class="material-icons">person</i>
            {{ $responsavel->nome }}
        </h2>
        <p>Alterar dados do aluno.</p>
    </div>

    @if (session('success'))
        <div class="card-panel green lighten-4" style="color: #1b5e20; font-size: 14px; background-color: #ccf5d8 !important; padding: 10px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="card-panel red lighten-4" style="color: #b71c1c; font-size: 14px; background-color: #ffcdd2 !important; padding: 10px; border-radius: 5px;">
            {{ session('error') }}
        </div>
    @endif

    <h6 class="mb-2" style="font-weight: 600;">
        <span class="material-icons" style="font-size: 1em; vertical-align: middle;">school</span>
        Alunos
    </h6>

    <form action="{{ url('cliente/aluno/' . $aluno['id'] . '/update') }}" method="POST">
        @csrf
        @method('PUT')
        <div id="lista-alunos">
            <div class="aluno-item card card-body mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Aluno</strong>
                </div>
                <input type="hidden" class="form-control" name="id" value="{{ $aluno['id'] ?? '' }}" required>
                <div class="form-group">
                    <label>Nome do aluno</label>
                    <input type="text" class="form-control" name="nome" value="{{ $aluno['nome'] ?? '' }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Ano</label>
                        <input type="text" class="form-control" name="serie" value="{{ $aluno['serie'] ?? '' }}" required>
                    </div>
                </div>
            </div>
        </div>
    
        <button type="submit" class="btn btn-success btn-block">
            Alterar
        </button>
    </form>
</div>

@endsection
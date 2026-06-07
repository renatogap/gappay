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

    .aluno-card {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        border: 2px solid transparent;
        transition: border-color .2s;
    }
    .aluno-card:hover { border-color: #3153e7; }
    .avatar {
        width: 42px; height: 42px; border-radius: 50%;
        background: #e6f1fb; color: #185fa5;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600; font-size: 14px; flex-shrink: 0;
    }
    .aluno-info { flex: 1; min-width: 0; }
    .aluno-nome { font-weight: 600; font-size: .95em; color: #333; }
    .badge-serie {
        display: inline-block; background: #eaf3de; color: #3b6d11;
        border-radius: 4px; padding: 2px 8px; font-size: .8em; font-weight: 500;
    }
    .actions { display: flex; gap: 6px; }
    .btn-action {
        width: 34px; height: 34px; border-radius: 8px;
        border: 1px solid #ddd; background: white;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
    }
    .btn-edit:hover { background: #e6f1fb; border-color: #3153e7; color: #3153e7; }
    .btn-del:hover  { background: #fcebeb; border-color: #a32d2d; color: #a32d2d; }
</style>


<div class="home-container">
    <h4>
        <i class="material-icons">school</i>
        Meus Alunos
        <a href="{{ url('cliente/home') }}" class="material-icons float-right" style="font-size:1.3em;color:#333;">
            keyboard_backspace
        </a>
    </h4>
    <hr>

    <div class="boas-vindas">
        <h2><i class="material-icons">person</i> {{ $responsavel->nome }}</h2>
        <p>Gerencie os alunos vinculados à sua conta.</p>
    </div>

    <h6 class="mb-2" style="font-weight:600;">
        <span class="material-icons" style="font-size:1em;vertical-align:middle;">school</span>
        Alunos
    </h6>

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

    <a href="{{ url('cliente/aluno/create') }}" class="btn btn-outline-primary btn-block mt-3">
        <span class="material-icons" style="font-size:1em;vertical-align:middle;">add</span>
        Adicionar aluno
    </a>

    @forelse($alunos as $aluno)
        <div class="aluno-card">
            <div class="avatar">
                {{ strtoupper(substr($aluno->nome, 0, 1)) }}{{ strtoupper(substr(strstr($aluno->nome, ' '), 1, 1)) }}
            </div>
            <div class="aluno-info">
                <div class="aluno-nome">{{ $aluno->nome }}</div>
                <div class="mt-1">
                    <span class="badge-serie">{{ $aluno->serie }}</span>
                </div>
            </div>
            <div class="actions">
                <a href="{{ url('cliente/aluno/'.$aluno->id.'/edit') }}" class="btn-action btn-edit" title="Editar">
                    <span class="material-icons" style="font-size:1.1em;">edit</span>
                </a>
                <form action="{{ url('cliente/aluno/'.$aluno->id.'/delete') }}" method="POST" class="form-excluir">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-action btn-del" title="Excluir" onclick="abrirModalExcluir(this, '{{ $aluno->nome }}')">
                        <span class="material-icons" style="font-size:1.1em;">delete</span>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <span class="material-icons" style="font-size:3em;opacity:.4;">school</span>
            <p>Nenhum aluno cadastrado ainda.</p>
        </div>
    @endforelse
</div>

<div id="modal-excluir" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:1em;">
    <div style="background:white; border-radius:16px; padding:2em 1.5em; max-width:380px; width:100%; text-align:center; position:relative;">

        <div style="width:64px; height:64px; border-radius:50%; background:#fef2f2; display:flex; align-items:center; justify-content:center; margin:0 auto 1em;">
            <span class="material-icons" style="font-size:2em; color:#a32d2d;">delete_forever</span>
        </div>

        <h5 style="font-weight:700; color:#333; margin:0 0 .4em;">Excluir aluno</h5>
        <p style="color:#666; font-size:.9em; margin:0 0 .3em;">Tem certeza que deseja excluir</p>
        <p id="modal-nome-aluno" style="color:#3153e7; font-weight:600; font-size:.95em; margin:0 0 1.5em;"></p>
        <p style="color:#999; font-size:.8em; margin:-1em 0 1.5em;">Esta ação não poderá ser desfeita.</p>

        <div style="display:flex; gap:.8em; justify-content:center;">
            <button onclick="fecharModalExcluir()"
                style="flex:1; padding:.8em; border-radius:50px; border:1px solid #ddd; background:white; color:#555; font-size:.95em; cursor:pointer; font-weight:500; transition:background .2s;">
                Cancelar
            </button>
            <button id="btn-confirmar-excluir"
                style="flex:1; padding:.8em; border-radius:50px; border:none; background:linear-gradient(135deg,#ff6b6b,#ee5a6f); color:white; font-size:.95em; cursor:pointer; font-weight:600; box-shadow:0 4px 12px rgba(255,107,107,.3); transition:transform .2s;">
                <span class="material-icons" style="font-size:1em; vertical-align:middle;">delete</span>
                Excluir
            </button>
        </div>
    </div>
</div>


<script>
    var formExcluir = null;

    function abrirModalExcluir(btn, nomeAluno) {
        formExcluir = btn.closest('.form-excluir');
        document.getElementById('modal-nome-aluno').textContent = nomeAluno;
        document.getElementById('modal-excluir').style.display = 'flex';
    }

    function fecharModalExcluir() {
        document.getElementById('modal-excluir').style.display = 'none';
        formExcluir = null;
    }

    document.getElementById('btn-confirmar-excluir').addEventListener('click', function () {
        if (formExcluir) formExcluir.submit();
    });

    document.getElementById('modal-excluir').addEventListener('click', function (e) {
        if (e.target === this) fecharModalExcluir();
    });
</script>
@endsection
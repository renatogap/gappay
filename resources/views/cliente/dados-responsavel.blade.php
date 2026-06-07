@extends('layouts.default-cliente-new')
@section('conteudo')
<style>
    * { box-sizing: border-box; }

    .home-container { max-width: 100%; padding: 0; }

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

    .boas-vindas p { margin: 0; opacity: 0.95; font-size: 0.95em; }

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
        color: white;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .home-container > * { animation: fadeInUp 0.6s ease forwards; }
</style>

<div class="home-container">
    <h4>
        <i class="material-icons">manage_accounts</i>
        Meus Dados
        <a href="{{ url('cliente/home') }}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h4>
    <hr>

    <div class="boas-vindas">
        <h2>
            <i class="material-icons">person</i>
            {{ $responsavel->nome }}
        </h2>
        <p>Atualize seus dados cadastrais.</p>
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

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px 16px; color: #991b1b; font-size: 14px; margin-bottom: 12px;">
            <ul style="margin: 0; padding-left: 1.2em;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-body mb-3 p-3">
        <form action="{{ url('cliente/meus-dados/update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-row">
                <input type="hidden" class="form-control" id="id" name="id" value="{{ $responsavel->id }}" required>
                <div class="form-group col-md-6">
                    <label for="nome">Nome completo</label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $responsavel->nome) }}" required>
                    @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone', $responsavel->telefone) }}"  maxlength="16" placeholder="(99) 9 9999-9999" required>
                    @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group  col-md-6">
                    <label for="email">E-mail cadastrado</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $responsavel->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
            </div>

            <button type="submit" class="btn btn-success btn-block mt-3">
                <i class="material-icons" style="font-size: 1em; vertical-align: middle;">save</i>
                Salvar alterações
            </button>
        </form>
    </div>

    <div class="sair-container">
        <a href="{{ url('cliente/logout') }}" class="btn-sair">
            <i class="material-icons">power_settings_new</i>
            Sair da Conta
        </a>
    </div>
</div>

<script>
   var telInput = document.getElementById('telefone');

    function mascararTelefone(valor) {
        var v = valor.replace(/\D/g, '').slice(0, 11);
        if (v.length <= 10) {
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        } else {
            v = v.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
        }
        return v;
    }

    telInput.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace') {
            var v = this.value;
            if (/[\s\-\(\)]$/.test(v)) {
                this.value = v.slice(0, -1);
            }
        }
    });

    telInput.addEventListener('input', function () {
        this.value = mascararTelefone(this.value);
    });

    telInput.value = mascararTelefone(telInput.value);
</script>

@endsection
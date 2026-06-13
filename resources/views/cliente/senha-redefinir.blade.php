<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('policia.slogan') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="icon" sizes="96x96" type="image/png" href="{{ asset('images/favicon/android-chrome-192x192.png') }}" type="imagem/png">
    <link rel="icon" sizes="96x96" type="image/png" href="{{ asset('images/favicon/android-chrome-512x512.png') }}" type="imagem/png">


    <link href="{{ asset('materialize-css/materialize.min.css') }}" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            background: <?= config('policia.background') ?>;
            width: 100%;
        }

        .box {
            background: #ffffff;
            border: 1px solid #dadce0;
            border-radius: 10px;
            padding: 30px 0;
            float: none;
            max-width: 400px;
            width: 96%;
            margin: 3em auto;
        }

        .cabeca {
            text-align: center;
            padding: 0 20px;
        }

        .cabeca .material-icons {
            font-size: 3em;
            color: #1565c0;
            margin-bottom: 8px;
        }

        .logo {
            width: 18em !important;
            margin-top: 1em;
        }

        .logo-sistema {
            width: 10em !important;
            margin-top: 2em;
        }

        .cabeca img {
            width: 10em;
        }

        .cabeca h1 {
            color: #333;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 8px;
        }

        .cabeca h2 {
            color: #3c3c3c;
            font-weight: 300;
            font-size: 18px;
            margin: 0 0 15px;
        }

        .cabeca p {
            color: #777;
            font-size: 14px;
            margin: 0 0 20px;
            line-height: 1.5;
        }

        .corpo {
            padding: 15px 20px 0;
        }

        .corpo input {
            padding: 15px;
            font-size: 16px;
            height: 20px;
            color: #777;
            border: 1px solid #ccc;
        }

        .corpo a {
            font-size: 14px;
        }

        .token-grid {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 8px 0 4px;
        }

        .token-grid input {
            width: 44px !important;
            height: 52px !important;
            text-align: center;
            font-size: 1.4em !important;
            font-weight: bold;
            border: 2px solid #ccc !important;
            border-radius: 8px !important;
            padding: 0 !important;
            color: #333 !important;
            transition: border-color 0.2s;
        }

        .token-grid input:focus {
            border-color: #1565c0 !important;
            outline: none;
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1);
        }

        .pe {
            padding: 0 40px;
            text-align: center;
        }

        .pe div {
            padding: 10px 0;
            clear: both;
            color: #555;
            font-size: 12px;
        }

        .pe .letreiro {
            text-align: justify !important;
        }

        .pe .letreiro i,
        .pdtp {
            padding-top: 10px;
        }

        .indicator {
            display: none;
            height: 30px;
            padding: 8px;
            position: absolute;
            right: 5px;
            text-align: center;
            top: 5px;
            width: 30px;
        }

        .indicator.on {
            display: block;
        }
    </style>
</head>
<body>

    <div class="box">
        <div class="cabeca">
             <img class="logo-sistema" src="{{ url(config('policia.logo_sistema')) }}" />
            <h1 style="font-weight: bold; margin-top: 1em;">Sistema {{config('policia.nome')}}</h1>
            <img class="logo" src="{{ url(config('policia.logo')) }}" />
            <!--<h1>{{config('policia.nome')}}</h1>-->
            <?php if (config('app.env') == 'local'): ?>
                <!-- <h1 style="color: darkblue; text-decoration: blink;">DESENVOLVIMENTO</h1> -->
            <?php endif; ?>
            <h1>Redefinir senha</h1>
            
            @if($modo === 'recuperacao')
                <p>Digite o código que enviamos para o seu e-mail e cadastre uma nova senha.</p>
            @endif
        </div>

        <div class="corpo">

            @if (session('success'))
                <div class="card-panel green lighten-4" style="color: #1b5e20; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="card-panel red lighten-4" style="color: #b71c1c; font-size: 14px;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('cliente/senha/redefinir') }}" method="POST">
                @csrf

                {{-- E-mail --}}
                <div class="input-field">
                    {{-- <label for="email" class="active">E-mail</label> --}}
                    <input type="hidden" name="email" id="email"
                           value="{{ $email }}"
                           readonly required>
                    @error('email')
                        <span style="color: #b71c1c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Token em caixinhas --}}
                @if($modo === 'recuperacao')
                    <div style="margin-bottom: 8px;">
                        <label style="font-size: 13px; color: #777;">Código recebido no e-mail</label>
                        <div class="token-grid" id="token-inputs">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" maxlength="1" inputmode="numeric"
                                    pattern="[0-9]" data-index="{{ $i }}">
                            @endfor
                        </div>
                        <input type="hidden" name="token" id="token" value="{{ old('token') }}" required>
                        @error('token')
                            <div style="color: #b71c1c; font-size: 12px; text-align: center; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @else
                    <div class="input-field">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" name="senha_atual" id="senha_atual" minlength="6" required>
                        @error('senha_atual')
                            <span style="color: #b71c1c; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                {{-- Nova senha --}}
                <div class="input-field">
                    <label for="senha">Nova senha</label>
                    <input type="password" name="senha" id="senha" minlength="6" required>
                    @error('senha')
                        <span style="color: #b71c1c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirmar senha --}}
                <div class="input-field">
                    <label for="confirmar_senha">Confirmar nova senha</label>
                    <input type="password" name="confirmar_senha" id="confirmar_senha" minlength="6" required>
                    @error('confirmar_senha')
                        <span style="color: #b71c1c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row" style="margin-top: 16px;">
                    <button class="btn waves-effect blue darken-3 right hide-on-small-only" type="submit">
                        Redefinir senha
                    </button>
                    <button class="btn waves-effect blue darken-3 show-on-small hide-on-med-and-up" type="submit"
                            style="width: 100%; margin-bottom: 12px;">
                        Redefinir senha
                    </button>
                </div>

                @if($modo === 'recuperacao')
                    <div style="text-align: center; margin-top: 8px; padding-bottom: 4px;">
                        <a href="{{ url('cliente/login') }}" style="font-size: 14px;">
                            Voltar para o login
                        </a>
                    </div>
                @else
                    <div style="text-align: center; margin-top: 8px; padding-bottom: 4px;">
                        <a href="{{ url('cliente/home') }}" style="font-size: 14px;">
                            Voltar para o home
                        </a>
                    </div>
                @endif

            </form>
        </div>

        <div class="pe">
            <div class="creditos">
                &copy; <?php echo date('Y') ?> - Desenvolvido por {{ config('policia.nome') }}>.
            </div>
        </div>
    </div>

</body>
<script src="{{ asset('materialize-css/materialize.min.js') }}"></script>
<script>
    const inputs = document.querySelectorAll('#token-inputs input');
    const hidden = document.getElementById('token');

    function syncHidden() {
        hidden.value = Array.from(inputs).map(i => i.value).join('');
    }

    inputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
            syncHidden();
            if (this.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            var paste = (e.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, 6);

            paste.split('').forEach(function(char, i) {
                if (inputs[i]) inputs[i].value = char;
            });
            syncHidden();

            var next = inputs[Math.min(paste.length, inputs.length - 1)];
            if (next) next.focus();
        });
    });
</script>
</html>

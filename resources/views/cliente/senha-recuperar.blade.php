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

        .corpo {
            padding: 15px 20px 0px;
        }

        .cabeca {
            text-align: center;
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
            color: #555;
            font-weight: 300;
            font-size: 22px;
            font-weight: normal;
            margin: 0 0 15px;
        }

        .cabeca h2 {
            color: #3c3c3c;
            font-weight: 300;
            font-size: 18px;
            font-weight: normal;
            margin: 0 0 15px;
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

        .pe {
            padding: 0px 40px;
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
            display: block
        }
    </style>
</head>

<body>

    <div class="box">
        <div class="cabeca">
            <img class="logo-sistema" src="{{ url(config('policia.logo_sistema')) }}" />
            <h1 style="font-weight: bold; margin-top: 1em;">Sistema {{config('policia.nome')}}</h1>
            <img class="logo" src="{{ url(config('policia.logo')) }}" />
             <h1>Recuperar senha</h1>
            <p>Enviaremos um código para redefinir sua senha.</p>
        </div>
        {{-- <div class="cabeca">
            <i class="material-icons">lock_reset</i>
            <h1>Recuperar senha</h1>
            <p>Informe seu e-mail cadastrado e enviaremos um código para redefinir sua senha.</p>
        </div> --}}

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

            <form action="{{ url('cliente/senha/recuperar') }}" method="POST">
                @csrf

                <div class="input-field">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email"
                           id="email" value="{{ old('email') }}"
                           placeholder="seu@email.com" required>
                    @error('email')
                        <span style="color: #b71c1c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row" style="margin-top: 16px;">
                    <button class="btn waves-effect blue darken-3 right hide-on-small-only" type="submit">
                        Enviar código
                    </button>
                    <button class="btn waves-effect blue darken-3 show-on-small hide-on-med-and-up" type="submit"
                            style="width: 100%; margin-bottom: 12px;">
                        Enviar código
                    </button>
                </div>

                <div style="text-align: center; margin-top: 8px; padding-bottom: 4px;">
                    <a href="{{ url('cliente/login') }}" style="font-size: 14px;">
                        Voltar para o login
                    </a>
                </div>

            </form>
        </div>

        <div class="pe">
            <div class="creditos">
                &copy; <?php echo date('Y') ?> - Desenvolvido por {{ config('policia.nome') }}.
            </div>
        </div>
    </div>

</body>
<script src="{{ asset('materialize-css/materialize.min.js') }}"></script>
</html>

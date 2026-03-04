<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <title>{{ config('policia.nome') }}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="icon" sizes="96x96" type="image/png" href="{{ asset('images/favicon/android-chrome-192x192.png') }}" type="imagem/png">
    <link rel="icon" sizes="96x96" type="image/png" href="{{ asset('images/favicon/android-chrome-512x512.png') }}" type="imagem/png">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('iconfont/material-icons.css') }}">

    <style>
        html,
        body {
            height: 100%;
            background: <?= config('policia.background') ?>;
            width: 100%;
        }

        .container-fluid {
            background: #ffffff;
            /* margin-top: 4.5em; */
            padding-top: 1em;
            padding-bottom: 1em;
            border-radius: 5px;
            width: 96%;
            text-shadow: 5px 5px 5px rbga(0, 0, 0, 0.5);
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);
        }

        .bg-dark {
            background: <?= config('policia.background') ?>;
            color: white;
        }

        .color-dark {
            color: <?= config('policia.background') ?>;
        }

        .btn-parque {
            background: <?= config('policia.btn-parque') ?>;
            color: white;
        }

        .btn-parque:hover {
            background: <?= config('policia.btn-parque-hover') ?>;
            color: white;
        }

        .btn-secondary {
            color: #333;
            background: <?= config('policia.btn-secondary') ?>;
        }

        .btn-secondary:hover {
            background: #ccc !important;
            color: #333;
        }

        form label {
            font-weight: bold;
        }


        .icone {
            font-size: 1.2em !important;
            display: inline-flex;
            vertical-align: top;
        }

        .btn-flutuante {
            /*background: #033328;
            border: 1px solid #033328;*/
            position: fixed;
            float: bottom;
            bottom: 15px;
            right: 15px;
            z-index: 100;
            font-size: 30px;
            padding: 15px 20px 15px 22px;
        }

        .btn-circulo {
            border-radius: 50px;
            -webkit-box-shadow: 9px 7px 5px rgba(50, 50, 50, 0.77);
            -moz-box-shadow: 9px 7px 5px rgba(50, 50, 50, 0.77);
            box-shadow: 9px 7px 5px rgba(50, 50, 50, 0.77);
        }

        .navbar-brand {
            background: none !important;
        }

        #global-loader {
            position: fixed;
            inset: 0;
            background: #0d0a0ae5;
            z-index: 99999;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #global-loader.active {
            opacity: 1;
            pointer-events: all;
        }

        .loader-gif {
            width: 120px;
            height: auto;
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {

            /* 0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); } */
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0);
            }
        }
    </style>
    <script>
        var BASE_URL = "{{url('')}}/";
    </script>

    @yield('cabecalho')
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark">
        <a class="navbar-brand" href="{{url('')}}">
            <i class="material-icons icone" style="font-size: 1.5em !important;">home</i> <b>{{ config('policia.nome') }}</b>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">


            <ul class="navbar-nav ml-auto">
                @if(!auth()->user())
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('seguranca/usuario') }}">Entrar</a>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ auth()->user()->nome }}</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="{{ url('seguranca/usuario/home') }}">Página inicial</a>
                        <a class="dropdown-item" href="{{ url('seguranca/usuario/alterar-senha') }}">Alterar senha</a>
                        <a class="dropdown-item" href="{{ url('seguranca/usuario/logout') }}">Sair</a>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </nav>

    <div id="container-fluid" class="container-fluid col-sm-12 col-md-8 mb-5">
        @yield('conteudo')
    </div>

    <div id="global-loader">
        <img
            src="{{ asset('images/logo-sistema.png') }}"
            alt="Carregando..."
            class="loader-gif">
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

    <script>
        //Efeito de loading na troca de páginas
        window.addEventListener('beforeunload', () => {
            document.getElementById('global-loader').classList.add('active');
        });
    </script>

    @yield('scripts')

</body>

</html>
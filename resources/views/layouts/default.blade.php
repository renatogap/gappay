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

        /* STEPPERS */
        .cadastro-steps {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            margin-bottom: 30px;
        }

        .cadastro-steps .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            min-width: 90px;
            text-align: center;
        }

        .cadastro-steps .circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #d9d9d9;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: .3s;
        }

        .cadastro-steps .step.active .circle {
            background: #195287ff;
            color: white;
        }

        .cadastro-steps .step.done .circle {
            background: #195287ff;
            color: white;
        }

        .cadastro-steps .step .label {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
            line-height: 1.3;
        }

        .cadastro-steps .step.active .label,
        .cadastro-steps .step.done .label {
            color: #222;
            font-weight: 600;
        }

        .cadastro-steps .line {
            flex: 1;
            height: 2px;
            background: #d9d9d9;
            margin: 20px 10px 0;
            max-width: 80px;
        }

        .cadastro-steps .material-icons {
            font-size: 18px;
        }
        /* FIM STEPPERS */

        /* Login Responsável */
         @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Sora:wght@700;800&display=swap');
        .login-wrapper {
                min-height: 80vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px 0;
            }

            .login-card {
                width: 100%;
                max-width: 420px;
                background: #132236;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 24px 64px rgba(0,0,0,0.4);
            }

            .login-header {
                background: linear-gradient(135deg, #1a3a5c 0%, #0f2540 100%);
                padding: 36px 40px 28px;
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .login-header::before {
                content: '';
                position: absolute;
                top: -40px; right: -40px;
                width: 180px; height: 180px;
                border-radius: 50%;
                background: rgba(255,255,255,0.04);
            }

            .login-header::after {
                content: '';
                position: absolute;
                bottom: -60px; left: 30px;
                width: 240px; height: 240px;
                border-radius: 50%;
                background: rgba(255,255,255,0.03);
            }

            .login-logo {
                font-family: 'Sora', sans-serif;
                font-size: 32px;
                font-weight: 800;
                color: #ffffff;
                letter-spacing: -0.5px;
                position: relative;
                z-index: 1;
            }

            .login-logo span {
                color: #4fc3f7;
            }

            .login-subtitle {
                font-family: 'DM Sans', sans-serif;
                font-size: 13px;
                color: rgba(255,255,255,0.45);
                letter-spacing: 1.5px;
                text-transform: uppercase;
                margin-top: 6px;
                position: relative;
                z-index: 1;
            }

            .login-body {
                padding: 36px 40px 32px;
            }

            .login-body .form-label {
                font-family: 'DM Sans', sans-serif;
                font-size: 13px;
                font-weight: 600;
                color: rgba(232,237,242,0.6);
                text-transform: uppercase;
                letter-spacing: 0.8px;
                margin-bottom: 6px;
            }

            .login-body .form-control {
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 8px;
                color: #e8edf2;
                font-family: 'DM Sans', sans-serif;
                font-size: 15px;
                padding: 10px 14px;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .login-body .form-control:focus {
                background: rgba(255,255,255,0.07);
                border-color: rgba(79,195,247,0.5);
                box-shadow: 0 0 0 3px rgba(79,195,247,0.1);
                color: #e8edf2;
                outline: none;
            }

            .login-body .form-control::placeholder {
                color: rgba(232,237,242,0.25);
            }

            .login-body .form-control.is-invalid {
                border-color: #e57373;
            }

            .btn-login {
                width: 100%;
                background: linear-gradient(135deg, #4fc3f7, #0288d1);
                border: none;
                border-radius: 8px;
                color: #0d1b2a;
                font-family: 'DM Sans', sans-serif;
                font-size: 15px;
                font-weight: 700;
                padding: 12px;
                cursor: pointer;
                transition: opacity 0.2s, transform 0.1s;
                margin-top: 8px;
            }

            .btn-login:hover {
                opacity: 0.92;
                transform: translateY(-1px);
            }

            .btn-login:active {
                transform: translateY(0);
            }

            .login-links {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid rgba(255,255,255,0.06);
            }

            .login-links a {
                font-family: 'DM Sans', sans-serif;
                font-size: 13px;
                color: rgba(79,195,247,0.8);
                text-decoration: none;
                transition: color 0.2s;
            }

            .login-links a:hover {
                color: #4fc3f7;
            }
        /* Fim Login Responsável */

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
<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <title>{{ config('policia.slogan') }}</title>

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
    </style>
    <script>
        var BASE_URL = "{{url('')}}/";
    </script>

    @yield('cabecalho')
</head>

<body>

    <div id="container-fluid" class="container-fluid col-sm-12 col-md-8 mb-5 mt-3">

        <div class="mb-3" style="text-align: center;">
            <a href="{{ route('set-locale', 'PT') }}" title="Português">
                <img src="{{asset('images/locale/icon-brasil.png')}}" width="50" alt="PT">
            </a>
            <a href="{{ route('set-locale', 'EN') }}" title="Inglês">
                <img src="{{asset('images/locale/icon-usa.png')}}" width="50" alt="EN">
            </a>
        </div>

        @yield('conteudo')


        @if(request()->session()->exists('pedido'))
        <a href="{{ url('pedido/confirmar-pedido') }}" class="btn btn-primary btn-lg pull-right btn-circulo btn-flutuante" title="Finalizar pedido">
            <i class="material-icons">local_grocery_store</i>
            <div style="margin-top: 0; font-size: 20px; font-weight: bold;">{{ COUNT(request()->session()->get('pedido')) }}</div>
        </a>
        @endif
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

    @yield('scripts')

</body>

</html>
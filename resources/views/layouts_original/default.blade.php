<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('favicon.ico')}}">

    <title>{{config('policia.nome')}}</title>

    <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/antecedentes.css')}}" rel="stylesheet">
    @yield('cabecalho')
</head>

<body>
    <h1 class="hidden">{{config('policia.nome')}}</h1>
    <header>
        <nav class="navbar navbar-inverse">
            <h3 class="hidden">Atalhos</h3>
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{url('')}}"><img class="pull-left" width="30px"
                            src="{{url('images/logo.png')}}"> {{config('policia.nome')}}
                    </a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    @if(isset($menu))
                    <ul class="nav navbar-nav">
                        <li><a><i class="glyphicon glyphicon-menu-right hidden-xs"></i></a></li>
                        @foreach($menu as $raiz)
                        @if(isset($raiz['submenu']))
                        @include('layouts.submenu', ['submenu' => $raiz])
                        @else
                        <li><a href="{{url($raiz['acao'])}}">{{$raiz['nome']}}</a></li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                    <ul class="nav navbar-nav pull-right">
                        @if(\Illuminate\Support\Facades\Auth::check())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="glyphicon glyphicon-user"></i> {{\Illuminate\Support\Facades\Auth::user()->nome}}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{url('seguranca/usuario/home')}}">Página inicial</a></li>
                                <li><a href="{{url('seguranca/usuario/alterar-senha')}}">Alterar senha</a></li>
                                <li><a href="{{url('usuario/logout')}}">Sair</a></li>
                            </ul>
                        </li>
                        @else
                        <li><a href="{{url('seguranca/usuario')}}">Entrar</a></li>
                        @endif
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
    </header>
    @yield('conteudo')
    <footer class="footer">
        <h2 class="hidden">Copyright</h2>
        <div class="container">
            <p class="text-muted">&copy; Copyright {{date('Y')}} - {{config('policia.nome')}}</p>
        </div>
    </footer>
    <script src="{{asset('bootstrap/js/bootstrap-native.min.js')}}"></script>
    <script>
        BASE_URL = "{{asset('')}}"
    </script>
    @if(config('app.env') === 'production')
    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-40659860-1', 'auto');
        ga('send', 'pageview');
    </script>
    @endif
    @yield('scripts')
</body>

</html>
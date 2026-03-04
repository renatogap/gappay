<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Esqueci a senha</title>

    <style>
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h2>Alteração de senha</h2>
    <p>Olá, {{ $info['nome'] }} Você solicitou a troca de senha do seu usuário. <br>
        Clique no link aqui abaixo ou copie e cole o endereço no seu navegador para atualizar sua senha.</p>
    <p>Atenção, o link disponibilizado expira em 24 horas. <br>
        Se precisar solicite outro na tela de login do sistema
        clicando em "Esqueci e-mail ou senha"</p>
    <p>
        <a href="{{ config('policia.url_front') }}/atualizar-senha?hash={{ $info['link']->hash }}">{{ config('policia.url_front') }}/atualizar-senha?hash={{ $info['link']->hash }}</a>
    </p>

    <p>
        Mensagem gerada automaticamente através do "esqueci a senha" do sistema {{ config('policia.nome') }}
        <br>
        GapPay
        <br>
    </p>
</body>
</html>

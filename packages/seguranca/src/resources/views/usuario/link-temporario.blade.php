<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Esqueci a Senha</title>
</head>
<body>
    <h1>Troca de senha</h1>
    <p>Olá, {{ $this->$info->nome }} Você solicitou a troca de senha do seu usuário:</p>
    <p><a href="#">{{ $this->info->link->hash }}</a></p>

    <p>
        Atenciosamente,
        Equipe GapPay
    </p>
</body>
</html>

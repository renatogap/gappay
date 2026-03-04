@extends('layouts.email')
@section('conteudo')
    <h1>Alteração de senha</h1>
    <p>Olá, {{ $info['nome'] }} Você solicitou a troca de senha do seu usuário. <br>
        Clique no link aqui abaixo ou copie e cole o endereço no seu navegador para atualizar sua senha.</p>
    <p>Atenção, o link disponibilizado expira em 24 horas. <br>
        Se precisar solicite outro na tela de login do sistema
        clicando em "Esqueci e-mail ou senha"</p>
    <p>
        <a class="button" href="{{ config('policia.url_front') }}/atualizar-senha/{{ $info['link']->hash }}">Alterar senha</a>
    </p>

@endsection

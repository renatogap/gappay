@extends('layouts.email')
@section('titulo', 'Ative sua conta')
@section('conteudo')
    <h1>Bem-vindo(a) ao {{ config('policia.nome') }}!</h1>
    <p>Olá {{ $info['nome'] }},</p>
    <p>Seu cadastro foi realizado com sucesso. Para concluir o processo, é necessário criar sua senha de acesso.</p>
    <p>Clique no botão abaixo para definir sua senha:</p>
    <a href="{{ config('policia.url_front') }}/atualizar-senha/{{ $info['hash'] }}" class="button">Criar senha</a>

    <p>Atenção, o link disponibilizado expira em 48 horas. <br>
        Se precisar solicite outro na tela de login do sistema clicando em "Esqueci e-mail ou senha"</p>
    <p>

    <p>E-mail gerado automaticamente pelo sistema {{ config('policia.nome') }} </p>
@endsection
@extends('layouts.email')
@section('titulo', 'A senha de seu usuário foi alterada')
@section('conteudo')
    <h1>Alteração de senha</h1>
    <p>Olá {{ $info['nome'] }},</p>
    <p>A senha de seu usuário no sistema {{ config('policia.nome') }} foi redefinida com sucesso.</p>
    <p>Se você não fez esta alteração ou acredita que algum usuário não autorizado acessou sua conta, clique no botão abaixo para solicitar a troca de senha.
        Na tela de login vá em "Esqueceu senha ou e-mail":</p>
    <p>
        <a class="button" href="{{ config('policia.url_front') }}/login">Acessar {{ config('policia.nome') }}</a>
    </p>

    <p>E-mail gerado automaticamente pelo sistema <a href="{{config('app.url')}}">{{ config('policia.nome') }}</a></p>
@endsection
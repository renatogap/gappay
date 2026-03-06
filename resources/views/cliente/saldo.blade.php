@extends('layouts.default')
@section('conteudo')
<h5>
    <i class="material-icons icone">attach_money</i> Consultar Saldo

    <a href="{{url('cliente/home')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>
<hr>
<div class="text-center" style="padding: 3em 0 4em 0;">

    <div class="alert alert-info">

        <h5>
            <div>Saldo atual:<br><br> <b style="font-size: 1.5em;">R$ {{ number_format($cartaoCliente->valor_atual, 2, ',', '.') }}</b></div>
        </h5>
        <br>

    </div>
@endsection
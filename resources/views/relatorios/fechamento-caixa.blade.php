@extends('layouts.default')
@section('conteudo')
<style>
    .linha:hover {
        background: #eee;
    }
</style>
<div>
    <h5>
        Fechamento de Caixa

        <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h5>
    <hr>

    <?php
    $valorTotal = 0;
    ?>


    <form method="GET" action="{{ url('relatorio/fechamento-caixa') }}">
        <div class="row mb-2">
            <div class="col-6 col-md-4">
                <label for="">Data início: </label>
                <input type="date" name="dtInicio" value="{{ $dtInicio }}" max="{{ date('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label for="">Hora início: </label>
                <input type="time" name="horaInicio" value="{{ $horaInicio }}" class="form-control">
            </div>
            <div class="col-6 col-md-4">
                <label for="">Data fim: </label>
                <input type="date" name="dtTermino" value="{{$dtTermino}}" max="{{ date('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label for="">Hora fim: </label>
                <input type="time" name="horaTermino" value="{{ $horaTermino }}" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block btn-parque">Ver Faturamento</button>
            </div>
        </div>
    </form>
    <br>
    <div class="row" style="margin: -1em;">
        @if($entradaCaixa && $resumoFaturamento)
        <div class="col-md-12">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <button type="buttom" class="btn btn-warning btn-block">Resumo do Faturamento</button>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" style="margin-top: -0.5em;">
                        <div class="panel-body p-3 mb-3 bg-dark">
                            <div class="row">
                                @foreach($resumoFaturamento as $resumo)
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title"><b>{{ $resumo->tipo_pagamento }}</b></div>
                                            <p class="card-text">R$ {{ number_format($resumo->valor, 2, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title"><b>Devoluções</b></div>
                                            <p class="card-text">-R$ {{ number_format($totalDevolucoes, 2, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-12">
            @if($entradaCaixa)
            @foreach($entradaCaixa as $id_usuario => $entradaFormaPagamento)
            <?php $totalUsuario = 0; ?>

            <?php $usuario = \GapPay\Seguranca\Models\Entity\Usuario::find($id_usuario) ?>


            <table class="table table-hover table-sm mb-0" width="100%">
                <tr class="bg-primary text-white" onclick="showDetalhes(<?= $id_usuario ?>)" style="font-size: 14px; font-weight: bold; cursor: pointer;">
                    <th colspan="2" class="pt-2 pb-2"><span class="material-icons icone">person</span> {{ $usuario->nome }}</th>
                    <th width="25%" style="text-align: right;"> &nbsp; </th>
                </tr>
            </table>
            @foreach($entradaFormaPagamento as $tipoPagamento => $entradas)
            <div class="detalhes-{{ $id_usuario }} show">
                <table class="table table-hover table-sm mb-0" width="100%">
                    <tr class="p-2 pl-3" style="font-size: 12px; font-weight: bold; background: #eee;">
                        <th colspan="2">{{ $tipoPagamento }}</th>
                        <th width="25%" style="text-align: right;">
                            {{ number_format( array_sum(array_column($entradas, 'valor')), 2, ',', '.') }}
                        </th>
                    </tr>
                </table>

                <?php $totalUsuario = $totalUsuario + array_sum(array_column($entradas, 'valor')); ?>

                <table class="table table-hover table-sm" width="100%">
                    @foreach($entradas as $entrada)
                    <tr>
                        <td class="8%" style="font-size: 13px;">{{ date('d/m H:i', strtotime($entrada->data)) }}</td>
                        <td class="80%" style="font-size: 13px;">{{ $entrada->observacao }}</td>
                        <td width="10%" style="font-size: 13px; color: <?= $entrada->valor > 0 ? 'green' : 'red'  ?>;" align="right" class="pr-2">
                            {{ number_format($entrada->valor, 2, ',', '.') }}
                        </td>
                    </tr>
                    <?php $valorTotal = $valorTotal + $entrada->valor; ?>
                    @endforeach

                </table>
            </div>
            @endforeach
            <div id="total-{{$id_usuario}}" class="float-right mr-2" style="margin-top: -1.8em; font-weight: bold; color: #fff; ">R$ {{ number_format($totalUsuario, 2, ',', '.') }}</div>

            @endforeach
            @else
            <div class="alert alert-info mt-3">Nenhum registro encontrado</div>
            @endif
        </div>
    </div>
    <hr style="margin-bottom: 1em; margin-top: 0em;">

    <div class="float-right" style="font-size: 1.5em;">
        <b>Total geral R$ {{ number_format($valorTotal, 2, ',', '.') }}</b>
    </div>
    <br><br><br>
</div>
@endsection

@section('scripts')
<script>
    function showDetalhes(idUser) {
        if (!$('.detalhes-' + idUser).hasClass('show')) {
            $('.detalhes-' + idUser).fadeIn();
            $('.detalhes-' + idUser).addClass('show');
            $('#total-' + idUser).css({
                "color": '#000',
                "margin-top": "0px"
            });
        } else {
            $('.detalhes-' + idUser).fadeOut();
            $('.detalhes-' + idUser).removeClass('show');
            $('#total-' + idUser).css({
                "color": '#fff',
                "margin-top": "-1.7em"
            });
        }
    }
</script>
@endsection
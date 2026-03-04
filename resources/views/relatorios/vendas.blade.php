@extends('layouts.default')
@section('conteudo')
<style>
    .linha:hover {
        background: #eee;
    }
</style>
<div>
    <h5>
        Vendas

        <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h5>
    <hr>


    <form method="GET" action="{{ url('relatorio/vendas') }}">
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
                <label for="">Data término: </label>
                <input type="date" name="dtTermino" value="{{$dtTermino}}" max="{{ date('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label for="">Hora término: </label>
                <input type="time" name="horaTermino" value="{{ $horaTermino }}" class="form-control">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-6 col-md-4">
                <input type="checkbox" name="maisVendidos" {{ $maisVendidos ? 'checked' : '' }}> Os 10 mais vendidos
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block btn-parque"><i class="material-icons icone">search</i> Buscar</button>
            </div>
        </div>
    </form>
    <br>
    <div class="row">
        <div class="col-md-12">
            @if(count($vendas) > 0)

            <?php $total = 0; ?>

            <table class="table table-hover table-sm mb-0" width="100%">
                <tr class="bg-dark">
                    <th>PRODUTO</th>
                    <th style="float: right;">QTD</th>
                    <th style="text-align: right;">TOTAL</th>
                </tr>
                @foreach($vendas as $venda)
                <?php $total += $venda->valorTotal; ?>
                <tr class="p-2 pl-3" style="font-size: 12px; font-weight: bold; background: #eee;">
                    <th class="p-2">
                        {{ mb_strtoupper($venda->produto) }}
                        <!-- - {{ $venda->fk_produto }} -->
                    </th>
                    <th width="10%" class="pr-2" style="text-align: right;">
                        {{ number_format($venda->quantidade, 0) }}
                    </th>
                    <th width="25%" class="pr-2" style="text-align: right;">
                        {{ number_format($venda->valorTotal, 2) }}
                    </th>
                </tr>
                @endforeach
            </table>

            <div style="text-align: right; font-weight: bold; font-size: 18px;">Total: R$ {{ number_format($total, 2) }}</div>
            @else
            <div class="alert alert-info mt-3">Nenhum registro encontrado</div>
            @endif
        </div>
    </div>

    <br><br><br>
</div>
@endsection
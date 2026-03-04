@extends('layouts.default')
@section('conteudo')
<style>
    .linha:hover {
        background: #eee;
    }
</style>
    <div>
        <h5>
            @if(in_array(4, $perfisUsuario))
                <span class="material-icons icone" style="font-size: 1.5em;">print</span> Meus Faturamentos
            @else
                <span class="material-icons icone" style="font-size: 1.5em">print</span> Faturamento dos PDVs
            @endif            
            <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
            </a>
        </h5>
        <hr>

        <?php $valorTotal = 0; ?>

        
        <form method="GET" action="{{ url('relatorio/resumo/pdv') }}">
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
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-parque"><i class="material-icons icone">search</i> Buscar</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-12">
                @if($dados)

                    @foreach($dados as $pdv => $produtos)
                        <table class="table table-hover table-sm mb-0" width="100%">

                            <?php $valorTotalPDV = 0; ?>

                            <tr class="bg-success text-white" onclick="showDetalhes('<?= str_replace(' ', '', $pdv) ?>')" style="font-size: 14px; font-weight: bold; cursor: pointer;">
                                <td class="pl-2" colspan="2"><b>{{ $pdv }}</b></td>                                
                                <td width="25%" align="right" class="pr-2">
                                    <b>R$ {{ number_format( array_sum(array_column($produtos, 'valor_total')), 2, ',', '.') }}</b>
                                </td>
                            </tr>
                        </table>
                            <!-- <tr bgColor="#cccccc">
                                <th>Produto</th>
                                <th>Qtd.</th>
                                <th style="text-align: right">Valor R$</th>
                            </tr> -->

                            @foreach($produtos as $item)
                            <div class="detalhes-{{ str_replace(' ', '', $pdv) }}" style="display: none;">
                                <table class="table table-hover table-sm mb-0" width="100%">
                                    <tr>
                                        <td>
                                            {{ $item->fk_produto }} - {{ mb_strtoupper($item->produto) }}
                                        </td>
                                        <td width="5%">({{ number_format($item->quantidade, 0) }})</td>
                                        <td width="10%" align="right" class="pr-2">
                                            {{ number_format($item->valor_total, 2, ',', '.') }}
                                        </td>
                                    </tr>

                                    <?php $valorTotalPDV += $item->valor_total; ?>
                                    

                                </table>
                            </div>
                            @endforeach

                            <?php $valorTotal += $valorTotalPDV; ?>

                    @endforeach
                @else
                        <div class="alert alert-info mt-3">Nenhum registro encontrado</div>
                @endif
            </div>
        </div>
        <hr style="margin: 0;">
        
        <div class="float-right" style="font-size: 1.5em;">
            <strong>
                Total: R$ {{ number_format($valorTotal, 2, ',', '.') }}
            </strong>
        </div>
        <br><br><br>
    </div>
@endsection

@section('scripts')
<script>
    function showDetalhes(pdvId) {
        if(!$('.detalhes-'+pdvId).hasClass('show')){
            $('.detalhes-'+pdvId).fadeIn();
            $('.detalhes-'+pdvId).addClass('show');
            $('#total-'+pdvId).css({"color": '#000', "margin-top": "0px"});
        }else {
            $('.detalhes-'+pdvId).fadeOut();
            $('.detalhes-'+pdvId).removeClass('show');
            $('#total-'+pdvId).css({"color": '#fff', "margin-top": "-1.7em"});
        }
    }
</script>
@endsection
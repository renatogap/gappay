@extends('layouts.default')
@section('conteudo')
<style>
    .linha:hover {
        background: #eee;
    }
</style>
<div>
    <h5>
        <span class="material-icons icone" style="font-size: 1.3em; color: #333;">print</span>
        Movimentação do Estoque

        <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h5>
    <hr>
    <form id="form" method="GET" action="{{ url('estoque/relatorio') }}">
        <div class="row mb-2">
            <div class="col-6 col-md-4">
                <label for="">Data início: </label>
                <input type="date" name="dtInicio" id="dtInicio" value="{{ $dtInicio }}" max="{{ date('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label for="">Hora início: </label>
                <input type="time" name="horaInicio" id="horaInicio" value="{{ $horaInicio }}" class="form-control">
            </div>
            <div class="col-6 col-md-4">
                <label for="">Data término: </label>
                <input type="date" name="dtTermino" id="dtTermino" value="{{$dtTermino}}" max="{{ date('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label for="">Hora término: </label>
                <input type="time" name="horaTermino" id="horaTermino" value="{{ $horaTermino }}" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block btn-parque"><i class="material-icons icone">search</i> Buscar</button>
                <button type="submit" class="btn btn-primary btn-block btn-warning" onclick="exportarEstoque(event)"><i class="material-icons icone">file_download</i> Exportar Estoque</button>
            </div>
        </div>
    </form>
    <br>

    <div class="row">
        <div class="col-md-12">
            @if($estoquePdvs)
            @foreach($estoquePdvs as $pdv => $estoquePdv)

            <?php list($nomePDV, $id_pdv) = explode('_', $pdv);  ?>

            <table class="table table-hover mb-0" width="100%">
                <tr class="bg-success text-white" onclick="showDetalhes({{ $id_pdv }})" style="font-size: 14px; font-weight: bold; cursor: pointer;">
                    <th colspan="2" class="pt-2 pb-2">
                        {{ $nomePDV }}

                        <span id="load-pdv-<?= $id_pdv ?>"></span>
                    </th>
                    <th width="25%" style="text-align: right;"> &nbsp; </th>
                </tr>
            </table>

            @foreach($estoquePdv as $produto => $itens)

            <?php list($nome, $qtd_atual, $id_item, $id_produto) = explode('__', $produto);  ?>

            <div class="detalhes-{{ $id_pdv }}" style="display: none;">

                <table class="table table-hover mb-0" width="100%">
                    <tr class="p-2 pl-3" onclick="showDetalhesItem(<?= $id_pdv ?>, <?= $id_item ?>)" style="font-size: 12px; font-weight: bold; background: #eee;">
                        <th colspan="2">
                            {{$id_produto}} - {{ mb_strtoupper($nome) }}

                            <span id="load-item-<?= $id_item ?>"></span>
                        </th>
                        <th width="35%" style="text-align: right; color: <?= ($qtd_atual == 0 ? 'red' : ($qtd_atual <= 10 ? 'orange' : '#212529')) ?>">
                            Hoje: {{ $qtd_atual }}
                        </th>
                    </tr>
                </table>
            </div>

            <div class="detalhes-item-{{ $id_item }}" style="display: none;">

            </div>
            @endforeach

            @endforeach
            @else
            <div class="alert alert-info mt-3">Nenhum registro encontrado para este filtro</div>
            @endif
        </div>
    </div>

    <br><br><br>
</div>

<!-- Modal Estoque Detalhados -->
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo-modal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="conteudo-modal" class="modal-body">

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var form = document.getElementById('form');
    var tituloModal = document.getElementById('titulo-modal');

    function exportarEstoque(e) {
        e.preventDefault();


        form.action = BASE_URL + '/estoque/resumo';

        e.stopPropagation();
        form.submit();

        form.action = BASE_URL + '/estoque/relatorio';
    }

    function showDetalhes(idPdv) {
        if (!$('.detalhes-' + idPdv).hasClass('show')) {
            $('.detalhes-' + idPdv).fadeIn();
            $('.detalhes-' + idPdv).addClass('show');
            $('#total-' + idPdv).css({
                "color": '#000',
                "margin-top": "0px"
            });
            //$('#load-pdv-'+idPdv).html('<img src="'+BASE_URL+'images/ajax-loader.gif">');
        } else {
            $('.detalhes-' + idPdv).fadeOut();
            $('.detalhes-' + idPdv).removeClass('show');
            $('#total-' + idPdv).css({
                "color": '#fff',
                "margin-top": "-1.7em"
            });
        }
    }

    function showDetalhesItem(idPdv, idItem) {
        if (!$('.detalhes-item-' + idItem).hasClass('show')) {
            $('.detalhes-item-' + idItem).fadeIn();
            $('.detalhes-item-' + idItem).addClass('show');
            //$('#total-'+idItem).css({"color": '#000', "margin-top": "0px"});
            $('#load-item-' + idItem).html('<img src="' + BASE_URL + 'images/ajax-loader.gif">');

            var params = {
                id_item_cardapio: idItem,
                id_tipo_cardapio: idPdv,
                dtInicio: form.dtInicio.value,
                horaInicio: form.horaInicio.value,
                dtTermino: form.dtTermino.value,
                horaTermino: form.horaTermino.value,
            };

            $.ajax({
                url: BASE_URL + '/estoque/detalhes-item',
                dataType: 'json',
                data: params,
                success: function(resp) {
                    var size = resp.length;
                    var table = '<table class="table table-hover table-sm" width="100%">';
                    table += '<thead>';
                    table += '<tr>';
                    table += '    <th width="25%">Data</th>';
                    table += '    <th style="text-align: center;">Estoque<br>Inicial</th>';
                    table += '    <th style="text-align: center;">Entradas</th>';
                    table += '    <th style="text-align: center;">Saídas</th>';
                    table += '    <th style="text-align: center;">Estoque<br>Final</th>';
                    table += '</tr>';
                    table += '</thead>';

                    if (size > 0) {

                        for (var i = 0; i < size; i++) {
                            table += '<tr onclick="modalDetalhesEstoque(' + idItem + ',' + idPdv + ",'" + resp[i].data_en + "'" + ')" data-toggle="modal" data-target="#modal" bgColor="' + (resp[i].semana == 'Sab' || resp[i].semana == 'Dom' ? '#eee' : '') + '">';
                            table += '    <td style="font-size: 13px;">' + resp[i].data + ' ' + resp[i].semana + '</td>';
                            table += '    <td align="center" style="font-size: 13px;">' + resp[i].estoque_inicial + '</td>';
                            table += '    <td align="center" style="font-size: 13px; color: green">' + resp[i].entradas + '</td>';
                            table += '    <td align="center" style="font-size: 13px; color: red" class="pr-2">' + resp[i].saidas + '</td>';
                            table += '    <td align="center" style="font-size: 13px;" class="pr-2">' + resp[i].saldo_final + '</td>';
                            table += '</tr>';
                        }
                    }

                    table += '</table>';

                    $('.detalhes-item-' + idItem).html(table);

                    $('#load-item-' + idItem).html('');
                }
            })
        } else {
            $('.detalhes-item-' + idItem).fadeOut();
            $('.detalhes-item-' + idItem).removeClass('show');
            //$('#total-'+idUser).css({"color": '#fff', "margin-top": "-1.7em"});
            $('.detalhes-item-' + idItem).html('');
        }


    }

    function modalDetalhesEstoque(idItem, idPdv, dataCorrente) {

        $.ajax({
            url: BASE_URL + 'estoque/detalhamento-item',
            dataType: 'json',
            data: {
                id_item_cardapio: idItem,
                id_tipo_cardapio: idPdv,
                data: dataCorrente
            },
            success: function(resp) {
                var sizeEntrada = resp.entradas.length;
                var sizeSaida = resp.saidas.length;


                tituloModal.innerHTML = resp.produto;

                table = '';

                if (sizeEntrada > 0) {
                    table += '<h5>Entradas</h5>';

                    table += '<table class="table table-hover table-sm" width="100%">';
                    table += '<thead>';
                    table += '<tr bgColor="#ffc107">';
                    table += '    <th width="15%">Data</th>';
                    table += '    <th style="text-align: center;">Observação</th>';
                    table += '    <th style="text-align: center;">Quantidade</th>';
                    table += '</tr>';
                    table += '</thead>';

                    for (var i = 0; i < sizeEntrada; i++) {
                        table += '<tr>';
                        table += '    <td style="font-size: 13px;">' + resp.entradas[i].data + '</td>';
                        table += '    <td style="font-size: 13px;">' + resp.entradas[i].observacao + '</td>';
                        table += '    <td width="8%" align="center" style="font-size: 13px; color: green">' + resp.entradas[i].quantidade + '</td>';
                        table += '</tr>';
                    }

                    table += '</table>';
                }


                table += '<br />';


                if (sizeSaida > 0) {
                    table += '<h5>Saídas</h5>';

                    table += '<table class="table table-hover table-sm" width="100%">';
                    table += '<thead>';
                    table += '<tr bgColor="#ffc107">';
                    table += '    <th width="15%">Data</th>';
                    table += '    <th style="text-align: center;">Observação</th>';
                    table += '    <th style="text-align: center;">Quantidade</th>';
                    table += '</tr>';
                    table += '</thead>';

                    for (var i = 0; i < sizeSaida; i++) {
                        table += '<tr>';
                        table += '    <td style="font-size: 13px;">' + resp.saidas[i].data + '</td>';
                        table += '    <td style="font-size: 13px;">' + resp.saidas[i].observacao + '</td>';
                        table += '    <td align="center" style="font-size: 13px; color: red;">' + resp.saidas[i].quantidade + '</td>';
                        table += '</tr>';
                    }

                    table += '</table>';
                }

                if (sizeEntrada == 0 && sizeSaida == 0) {
                    table += '<div class="alert alert-info">Não há registros de entrada e saída neste período.</div>';
                }


                document.getElementById('conteudo-modal').innerHTML = table;
            }
        });
    }
</script>
@endsection
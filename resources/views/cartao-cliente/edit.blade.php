@extends('layouts.default')

@section('conteudo')
<style>
    select[readonly] {
        background: #eee;
        /*Simular campo inativo - Sugestão @GabrielRodrigues*/
        pointer-events: none;
        touch-action: none;
    }
</style>
<h5>
    Cartão do Aluno

    <a href="{{url('cartao-cliente')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>
<span class="badge {{ ($entrada->status==1 ? 'badge-info' : ($entrada->status==2 ? 'badge-success' :  'badge-danger')) }}" style="font-size: 14px;">
    {{ ($entrada->status==1 ? 'DEVOLVIDO' : ($entrada->status==2 ? 'EM USO' : ($entrada->status==3 ? 'BLOQUEADO' : 'PERDIDO'))) }}
</span>
<hr>

<form method="post" action="{{ url('cartao-cliente/store') }}">
    {{ @csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$entrada->id}}">
    <input type="hidden" name="tipo" value="1">
    <input type="hidden" name="hash" value="{{$cartao->hash}}">

    @if (session('sucesso'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ session('sucesso') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div><label>Data do cadastro</label></div>
                <input type="text" name="data" value="{{date('d/m/Y H:i', strtotime($entrada->created_at))}}" class="form-control" readonly>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <div><label>Nome *</label></div>
                <input type="text" name="nome" class="form-control" value="{{$entrada->nome}}" required>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-6 col-md-4">
            <div class="form-group">
                <div><label>CPF:</label></div>
                <input type="tel" name="cpf" class="form-control" value="{{$entrada->cpf}}" maxlength="14" onkeydown="fMasc(this, mCPF);">
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
                <div><label>Telefone *</label></div>
                <input type="tel" name="telefone" class="form-control" value="{{$entrada->telefone}}" onkeyup="mTel(this)" required maxlength="14">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-md-4">
            <div class="form-group">
                <div><label>Saldo disponível *</label></div>
                <input type="text" name="valor" class="form-control" value="{{$entrada->valor_atual}}" disabled>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
                <div><label>Tipo de pagamento *</label></div>
                <select name="tipo_pagamento" class="form-control" required>
                    @foreach($formaPagamento as $p)
                    <option {{ $entrada->fk_tipo_pagamento == $p->id ? 'selected' : '' }} value="{{ $p->id }}">{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-md-4">
            <div class="form-group">
                <div><label>Valor da Caução *</label></div>
                <input type="tel" name="valorCartao" class="form-control" required readonly value="{{$entrada->valor_cartao}}" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="form-group">
                <div><label>Observacao:</label></div>
                <textarea name="observacao" class="form-control">{{$entrada->observacao}}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">

            @if($entrada->status != 1)
            <button type="submit" class="btn btn-parque ml-3 btn">
                <!--<i class="material-icons">save</i>--> Salvar
            </button>

            @if($entrada->status == 2)
            <a href="#" data-toggle="modal" data-target="#modalDevolucao" class="btn btn-info btn">
                <!--<i class="material-icons">how_to_vote</i>--> Devolução
            </a>

            <a href="#" onclick="bloqueiaDesbloqueiaCartao(3, '{{$cartao->codigo}}')" class="btn btn-danger">
                <i class="material-icons icone">lock</i> Bloquear
            </a>
            @elseif($entrada->status == 3)
            @if(!$entrada->fk_cartao_transferido)
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal" onclick="lerQrCode()">
                Transferir
            </a>
            @endif

            <a href="#" onclick="bloqueiaDesbloqueiaCartao(2, '{{$cartao->codigo}}')" class="btn btn-success">
                Desbloquear
            </a>
            @endif

            @endif


            <a href="#" class="btn btn-warning ml-3" data-toggle="modal" data-target="#modalConsumo">Consumo / Extrato</a>

            @if($entrada->devolvido == 'S' && $entrada->valor_atual > 0 && in_array(6, $perfisUsuario))
            <a href="#" onclick="zerarCartao()" class="btn btn-dark ml-3">
                Zerar Cartão
            </a>
            @endif
        </div>
    </div>



</form>

<!-- Modal -->
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="material-icons" style="font-size: 30px;">qr_code_scanner</span> Aproxime o cartão
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.reload()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-none" id="div-aguarde" style="text-align: center">
                    <h5 style="font-weight: normal;" class="mt-3 mb-0">
                        <div class="material-icons" style="color: darkgreen; font-size: 2em;">
                            hourglass_full
                        </div>
                        <div>Aguarde, carregando...</div>
                    </h5>
                    <br><br><br><br>
                </div>

                <div id="mudarCamera" class="ml-1" onclick="mudarCamera()">
                    <span class="material-icons" style="font-size: 2.5em; position: absolute; z-index: 1; color: orange; cursor: pointer;">flip_camera_ios</span>
                </div>

                <video id="preview" style="width: 100%;"></video>
            </div>
        </div>
    </div>
</div>

<!-- Modal Devolução -->
<div id="modalDevolucao" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold;">
                    Devolução
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for=""><b>Crédito do Cartão</b></label>
                            <input type="tel" name="valorCartao" id="valorCartao" value="{{$entrada->valor_atual}}" class="form-control form-control-lg" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for=""><b>Devolução do Caução</b></label>
                            <input type="tel" name="valorCaucao" id="valorCaucao" value="{{$entrada->valor_cartao}}" class="form-control form-control-lg" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for=""><b>O cartão foi devolvido para o Caixa ?</b></label>
                            <div>
                                <input type="radio" name="rdCartaoDevolvido" id="rdCartaoDevolvido" value="S" onclick="isDevolveuCartao(this)" checked> Sim &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="rdCartaoDevolvido" id="rdCartaoDevolvido" value="N" onclick="isDevolveuCartao(this)"> Não, o aluno levou
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for=""><b>Valor da Devolução</b></label>
                            <input type="tel" name="valorDevolvido" id="valorDevolvido" value="{{ number_format(($entrada->valor_atual + $entrada->valor_cartao),2) }}" class="form-control form-control-lg">
                            <br>
                            <div style="font-size: 13px">Limite máximo de devolução: <b class="text-danger">R$ {{ config('policia.limite_devolucao') }}.00</b></div>
                        </div>
                    </div>
                </div>


                <div id="errorModalDevolucao" class="alert alert-danger d-none"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-parque" onclick="devolverCartao('{{$cartao->codigo}}')">Salvar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Consumo -->
<div id="modalConsumo" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold;">
                    Consumo do Aluno
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @if($pedidosCliente->count() > 0)
                <?php $total = 0; ?>

                <table class="table table-striped table-sm">
                    <tr class="bg-dark" style="color: white;">
                        <th width="25%">Data</th>
                        <th>Produto</th>
                        <th width="15%">Valor</th>
                    </tr>
                    @foreach($pedidosCliente as $pedido)
                    <tr>
                        <td style="font-size: 12px">{{ date('d/m H:i', strtotime($pedido->data)) }}</td>
                        <td style="font-size: 12px">
                            {{ $pedido->unid == 1 ? intval($pedido->quantidade) : $pedido->quantidade }} {{ $pedido->nome_item }}
                            {{ $pedido->status==4 ? '<span style="color: red;">Cancelado</span>' : '' }}
                        </td>
                        <td style="font-size: 12px" align="right">{{ number_format($pedido->valor, 2, ',','.') }}</td>
                    </tr>

                    <?php $total = $total + $pedido->valor; ?>
                    @endforeach
                </table>


                <div class="float-right" style="font-weight: bold; font-size: 18px;">
                    Total R$ {{ number_format($total, 2, ',','.')}}
                </div>

                <div style="clear: both"></div>
                <div style="color: red;">* Os valores acima são de consumo e não incluem a comissão</div>

                <br><br>

                <h5 class="modal-title" style="font-weight: bold;">
                    Extrato
                </h5>
                <div class="row">
                    <?php
                    $valorTotal = 0;
                    ?>
                    <div class="col-md-12">
                        @if($entradaCaixa)
                        <div class="detalhes">
                            <table class="table table-striped table-hover table-sm" width="100%">
                                @foreach($entradaCaixa as $id_usuario => $entrada)
                                <?php $totalUsuario = 0; ?>

                                <tr>
                                    <td class="8%" style="font-size: 13px;">{{ date('d/m H:i', strtotime($entrada->data)) }}</td>
                                    <td class="80%" style="font-size: 13px;">
                                        {{ $entrada->observacao }} no <strong>{{ $entrada->tipo_pagamento }}</strong>
                                    </td>
                                    <td width="10%" style="font-size: 13px; color: {{$entrada->valor > 0 ? 'green' : 'red'}};" align="right" class="pr-2">
                                        {{ number_format($entrada->valor, 2, ',', '.') }}
                                    </td>
                                </tr>
                                <?php $valorTotal = $valorTotal + $entrada->valor; ?>

                                @endforeach
                            </table>
                        </div>

                        @else
                        <div class="alert alert-info mt-3">Nenhum registro encontrado</div>
                        @endif
                    </div>
                </div>
                <hr style="margin-bottom: 1em; margin-top: 0em;">

                <div class="float-right" style="font-size: 1.5em;">
                    <b>Saldo: R$ {{ number_format($valorTotal, 2, ',', '.') }}</b>
                </div>

                @else
                <div class="alert alert-info">
                    Este aluno ainda não teve consumo
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


<!--<form id="form-cartao-delete" action="{{ url('admin/cartao/delete') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="id" value="{{ isset($cartao)? $cartao->id : '' }}">
    </form>
-->

@endsection

@section('scripts')
<script type="text/javascript" src="{{url('js/instascan.min.js')}}"></script>
<script>
    var valorCartao = document.getElementById('valorCartao');
    var valorCaucao = document.getElementById('valorCaucao');
    var valorDevolvido = document.getElementById('valorDevolvido');

    var indexCamera = 1;

    var audio = new Audio(BASE_URL + 'beep1second.mp3');

    var scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });

    function play() {
        audio.play();
    }

    function pause() {
        audio.pause();
    }

    function lerQrCode() {
        play();
        pause();

        scanner.addListener('scan', function(content) {
            scanner.stop();
            play();
            $('#div-aguarde').removeClass('d-none');
            document.getElementById('preview').classList.add('d-none');
            document.getElementById('mudarCamera').innerHTML = '';
            window.location = BASE_URL + 'cartao-cliente/transferir-credito/' + content + '?id=' + document.getElementById('id').value;
        });

        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length == 1) {
                scanner.start(cameras[0]);
            } else if (cameras.length > 1) {
                scanner.start(cameras[1]);
            } else {
                alert("There is no camera on the device!");
            }
        });
    }

    function mudarCamera() {
        scanner.stop();
        indexCamera++;

        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length >= indexCamera) {
                scanner.start(cameras[indexCamera]);
            } else {
                indexCamera = 0;
                scanner.start(cameras[indexCamera]);
            }
        });
    }

    function isDevolveuCartao(e) {
        if (e.value == 'S') {
            valorCaucao.value = '{{ config("parque.valor_cartao") }}';
            valorDevolvido.value = (parseFloat(valorCartao.value) + parseFloat('{{ config("parque.valor_cartao") }}'));
        } else {
            valorCaucao.value = '0.00';
            valorDevolvido.value = valorCartao.value;
        }
    }

    function devolverCartao(codigo) {
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'cartao-cliente/devolver-cartao',
            data: {
                _token: document.getElementsByName('_token')[0].value,
                codigo: codigo,
                valorDevolvido: document.getElementById('valorDevolvido').value,
                rdCartaoDevolvido: $('input[name="rdCartaoDevolvido"]:checked')[0].value
            },
            dataType: 'json',
            async: false,
            success: function(resp) {
                window.location.reload();
            },
            error: function(error) {
                var msgerro = document.getElementById('errorModalDevolucao');
                msgerro.innerHTML = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + error.responseJSON.message;
                msgerro.classList.remove('d-none');
            }
        });
    }

    function bloqueiaDesbloqueiaCartao(status, codigo) {
        if (confirm('Deseja realmente mudar o status deste cartão?')) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'cartao-cliente/bloqueia-desbloqueia',
                data: {
                    _token: document.getElementsByName('_token')[0].value,
                    codigo: codigo,
                    status: status
                },
                dataType: 'json',
                success: function(resp) {
                    window.location.reload();
                }
            })
        }
    }

    function zerarCartao() {
        if (confirm('Deseja realmente zerar este cartão?')) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'cartao-cliente/zerar-cartao',
                data: {
                    _token: document.getElementsByName('_token')[0].value,
                    id: document.getElementById('id').value
                },
                dataType: 'json',
                success: function(resp) {
                    window.location.reload();
                }
            })
        }
    }

    function fMasc(objeto, mascara) {
        obj = objeto
        masc = mascara
        setTimeout("fMascEx()", 1)
    }

    function fMascEx() {
        obj.value = masc(obj.value)
    }

    function mTel(elem) {
        var tel = elem.value;
        tel = tel.replace(/\D/g, "")
        tel = tel.replace(/^(\d{2})/, "($1)")

        if (tel.length == 9) {
            tel = tel.replace(/(.{1})$/, "-$1")
        } else if (tel.length == 10) {
            tel = tel.replace(/(.{2})$/, "-$1")
        } else if (tel.length == 11) {
            tel = tel.replace(/(.{3})$/, "-$1")
        } else if (tel.length == 12) {
            tel = tel.replace(/(.{4})$/, "-$1")
        } else if (tel.length > 12) {
            tel = tel.replace(/(.{4})$/, "-$1")
        }

        elem.value = tel;
    }

    function mCPF(cpf) {
        cpf = cpf.replace(/\D/g, "")
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2")
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2")
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
        return cpf
    }
</script>


@endsection
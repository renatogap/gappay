<?php

use App\Models\Entity\Produto;
?>
@extends('layouts.default')


@section('conteudo')

<h5>Editar Item do Cardápio

    <a href="{{url('cardapio')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>
<span class="badge {{ $cardapio->status == 1 ? 'badge-success' : 'badge-danger' }}">
    {{ $cardapio->status == 1 ? 'Ativo' : 'Inativo' }}
</span>
<hr>

<form method="post" action="{{ url('cardapio/store') }}" enctype="multipart/form-data">
    {{ @csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$cardapio->id}}">

    @if (session('sucesso'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session('sucesso') !!}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session('error') !!}
    </div>
    @endif

    @if($fotoCardapio)
    <div class="col-md-12 d-block d-md-none">
        <div class="form-group">
            <div class="col-md-12" style="text-align: center;">
                <img src="{{url('cardapio/ver-foto/'.$cardapio->id)}}" onerror="this.src='<?= url('images/foto-error.png') ?>'" width="85%">
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-12">Nome do PDV</label>
                <div class="col-md-12">
                    <select name="tipo" class="form-control float-left" readonly onchange="changeTipoCardapio(this.value)">
                        @foreach($comboTipo as $c)
                        @if($id_tipo_cardapio == $c->id || $cardapio->fk_tipo_cardapio == $c->id)
                        <option selected value="{{$c->id}}">{{$c->nome}}</option>
                        @endif
                        @endforeach
                    </select>
                    <!--
                        <a href="{{ url('cardapio/tipo-cardapio?action=edit/'.$cardapio->id).'&id='.$cardapio->id }}" class="btn btn-primary float-right">
                            +
                        </a>
                        -->
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-md-8">
            <div class="form-group">
                <label for="" class="col-md-12">Categoria</label>
                <div class="col-md-12">
                    <select name="categoria" class="form-control">
                        @if($comboCategoria)
                        <option value="">SELECIONE...</option>
                        @else
                        <option value="">SELECIONE O TIPO PRIMEIRO...</option>
                        @endif

                        @if($comboCategoria)
                        @foreach($comboCategoria as $c)
                        <option {{ $cardapio->fk_categoria === $c->id ? 'selected' : '' }} value="{{$c->id}}">{{$c->nome}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="col-md-12">Descrição do item *</label>
                    <div class="col-md-12">
                        <input type="text" name="nomeItem" class="form-control" value="{{$cardapio->nome_item}}" required>
                    </div>
                </div>
            </div>
        </div> -->

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="form-group">
                <label for="" class="col-md-12">Nome do produto *</label>
                <div class="col-md-12">
                    <!-- <input type="text" name="nomeItem" class="form-control" required> -->
                    <select name="produto" id="comboProduto" class="form-control float-left" style="width: 86%;" required>
                        @foreach($produtos as $c)
                        @if($cardapio->fk_produto == $c->id)
                        <option selected value="{{$c->id}}">{{$c->nome}}</option>
                        @endif
                        @endforeach
                    </select>


                    <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalProduto">
                        <span class="material-icons icone" style="font-size: 1.1em !important; font-weight: bold;">
                            edit
                        </span>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="form-group">
                <label for="" class="col-md-12">Detalhes do item</label>
                <div class="col-md-12">
                    <input type="text" name="detalheItem" class="form-control" value="{{$cardapio->detalhe_item}}">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="col-md-12">Unidade *</label>
                <div class="col-md-12 col-6">
                    <input type="tel" name="unidade" class="form-control" value="{{ number_format($cardapio->unid, 0) }}">
                    <!-- 
                    Descomentar caso queira permitir números decimais na unidade

                    <input type="tel" name="unidade" class="form-control" value="{{ number_format($cardapio->unid, 2) }}"> 
                    -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="col-md-12">Valor *</label>
                <div class="col-md-12 col-6">
                    <input type="tel" name="valor" class="form-control" value="{{$cardapio->valor}}" maxlength="5">
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="" class="col-md-12">Nova Foto</label>
                <div class="col-md-12">
                    <input type="file" name="foto[]" multiple accept="image/*" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 d-none d-md-block">
            <div class="form-group">
                <label class="col-md-2">&nbsp;</label>
                <div class="col-md-12">
                    <div style="width: 90%; height: 10em; right: 0;">
                        <img src="{{url('cardapio/ver-foto/'.$cardapio->id)}}" width="80%" height="100%" onerror="this.src='<?= url('images/foto-error.png') ?>'">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">

                <button type="submit" class="btn btn-parque ml-3 btn">Salvar</button>

                @if($cardapio->status == 1)
                <button type="button" class="btn btn-danger btn" onclick="inativarItemCardapio()">Inativar Item</button>
                @else
                <button type="button" class="btn btn-success btn" onclick="ativarItemCardapio()">Ativar Item</button>
                @endif
                <!-- <a href="{{url('cardapio')}}" class="btn btn-secondary">Cancelar</a> -->
            </div>
        </div>
    </div>
</form>

<!-- Modal Produto -->
<div id="modalProduto" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Lista de Produto
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formModalProduto" action="{{ url('cardapio/salvar-produto') }}" onsubmit="salvarModalProduto(event, this)">
                    {{ @csrf_field() }}

                    <input type="hidden" name="id_produto" id="id_produto" value="{{$cardapio->fk_produto}}">

                    <div id="error" class="alert alert-danger d-none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <span id="msgError">{!! session('error') !!}</span>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="col-md-12">Produto</label>
                                <div class="col-md-12">
                                    <input type="text" name="produto" id="produto" value="<?= (Produto::find($cardapio->fk_produto))->nome ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-parque">Salvar</button>
                                    <button class="btn btn-secondary" id="close-modal-produto" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var id_produto = document.getElementById('id_produto');
    var produto = document.getElementById('produto');

    function changeTipoCardapio(value) {
        if (value == 'TODOS') {
            window.location = 'edit/' + <?= $cardapio->fk_tipo_cardapio; ?>;
        } else {
            window.location = "?id_tipo_cardapio=" + value;
        }
    }

    function inativarItemCardapio() {
        if (confirm('Deseja realmente Inativar este item?')) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'cardapio/inativar-item',
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

    function ativarItemCardapio() {
        if (confirm('Deseja realmente ativar este item?')) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'cardapio/ativar-item',
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

    function salvarModalProduto(event, e) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: e.action,
            data: {
                _token: document.getElementsByName('_token')[0].value,
                produto: produto.value,
                id_produto: id_produto.value
            },
            dataType: 'JSON',
            success: function(resp) {
                var prod = document.getElementById('comboProduto');
                prod.innerHTML = '<option value="' + resp.newproduto.id + '" selected>' + resp.newproduto.nome + '</option>'

                //fechar modal Produto
                document.getElementById('close-modal-produto').click();
            },
            error: function(request) {
                var error = document.getElementById('error');
                var msgError = document.getElementById('msgError');
                msgError.innerHTML = request.responseJSON.message;
                error.classList.remove('d-none');
                //alert(request.responseJSON.message);
            }
        })
    }
</script>


@endsection
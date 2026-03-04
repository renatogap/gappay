@extends('layouts.default')
@section('conteudo')
<link rel="stylesheet" href="{{asset('js/autocompleteJS/autocompleteJS.css')}}">
    <h5>Novo Item do Cardápio
        <a href="{{url('cardapio')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h5>
    <hr>

    <form method="post" action="{{ url('cardapio/store') }}" enctype="multipart/form-data">
        {{ @csrf_field() }}

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

        <div class="row mb-2">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="" class="col-md-12">Nome do PDV*</label>
                    <div class="col-md-12">
                        <select name="tipo" class="form-control" onchange="changeTipoCardapio(this.value)" required>
                            @if($id_tipo_cardapio)
                                <option value="TODOS">VER TODOS...</option>
                            @else
                                <option value="">SELECIONE...</option>
                            @endif
                            @foreach($comboTipo as $c)
                            <option {{ $id_tipo_cardapio == $c->id ? 'selected' : '' }} value="{{$c->id}}">{{$c->nome}}</option>
                            @endforeach
                        </select>
                        
                    </div>
                    <div class="col-md-12">
                        <a href="{{ url('cardapio/tipo-cardapio?action=create') }}">
                            Gerenciar PDVs
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="col-md-12">Categoria*</label>
                    <div class="col-md-10">
                        <select name="categoria" class="form-control" required>
                            @if($comboCategoria)
                                <option value="">SELECIONE...</option>
                            @else
                                <option value="">SELECIONE O PDV PRIMEIRO...</option>
                            @endif

                            @if($comboCategoria)
                                @foreach($comboCategoria as $c)
                                <option value="{{$c->id}}">{{$c->nome}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12">
                        <a href="#" class="" data-toggle="modal" data-target="#modal" onclick="limparModal()">
                            + Adicionar nova categoria
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="col-md-12">Produto *</label>
                    <div class="col-md-10">
                        <!-- <input type="text" name="nomeItem" class="form-control" required> -->
                        <select name="produto" id="comboProduto"  class="form-control" required>
                            <option value="">SELECIONE...</option>
                            @foreach($produtos as $c)
                                @if($c->nome)
                                    <option value="{{$c->id}}">{{$c->id}} - {{$c->nome}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <a href="#" class="" data-toggle="modal" data-target="#modalProduto" onclick="limparModal()">
                            + Adicionar novo produto
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="col-md-12">Detalhes do item</label>
                    <div class="col-md-12">
                        <input type="text" name="detalheItem" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-6">
                <div class="form-group">
                    <label for="" class="col-md-12">Unidade *</label>
                    <div class="col-md-12">
                        <input type="tel" name="unidade" class="form-control" value="1">
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="form-group">
                    <label for="" class="col-md-12">Valor *</label>
                    <div class="col-md-12">
                        <input type="tel" name="valor" class="form-control" maxlength="11">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="" class="col-md-12">Foto</label>
                    <div class="col-md-12">
                    <input type="file" name="foto[]" multiple accept="image/*" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-parque ml-3 btn">Salvar</button>
                    <a href="{{url('cardapio')}}" class="btn btn-secondary">Cancelar</a>
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
                        
                        <div id="error" class="alert alert-danger d-none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <span id="msgError">{!! session('error') !!}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="col-md-12">Produto</label>
                                    <div class="col-md-12">
                                        <input type="text" name="produto" id="produto" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-parque">Salvar</button>
                                        <button class="btn btn-secondary" id="close-modal-produto" data-dismiss="modal" onclick="limparModal()">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Cadastrar Categoria
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modalCategoria" action="{{ url('cardapio/salvar-categoria') }}" onsubmit="salvarModal(event, this)">
                        {{ @csrf_field() }}
                        
                        <div id="error" class="alert alert-danger d-none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <span id="msgError">{!! session('error') !!}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="col-md-12">Tipo de Cardápio</label>
                                    <div class="col-md-12">
                                        <select name="tipo" id="tipo" class="form-control" required>
                                            @foreach($comboTipo as $c)
                                            <option value="{{$c->id}}">{{$c->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="col-md-12">Categoria</label>
                                    <div class="col-md-12">
                                        <input type="text" name="categoria" id="categoria" class="form-control" placeholder="Ex: Pratos Executivos" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-parque">Salvar</button>
                                        <button class="btn btn-secondary" data-dismiss="modal" onclick="limparModal()">Fechar</button>
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
<script src="{{asset('js/autocompleteJS/autocompleteJS.js')}}"></script>
<script>
    var tipo = document.getElementById('tipo');
    var categoria = document.getElementById('categoria');
    var produto = document.getElementById('produto');

    new AutocompleteJS('comboProduto');

    function limparModalProduto() {
        produto.value = '';
    }

    function limparModal() {
        categoria.value = '';
    }

    function changeTipoCardapio(value){
        if(value == 'TODOS') {
            window.location='create';
        }else {
            window.location='create?id_tipo_cardapio='+value;
        }
    }

    function salvarModalTipoCardapio(event, e) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: e.action,
            data: {
                _token: document.getElementsByName('_token')[0].value,
                tipo: tipo.value
            },
            dataType: 'JSON',
            success: function(resp) {
                alert(resp.message);

                window.location.reload();
            },
            error: function (request) {
                var error = document.getElementById('error');
                var msgError = document.getElementById('msgError');
                msgError.innerHTML = request.responseJSON.message;
                error.classList.remove('d-none');
                //alert(request.responseJSON.message);
            }
        })
    }

    function salvarModal(event, e) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: e.action,
            data: {
                _token: document.getElementsByName('_token')[0].value,
                tipo: tipo.value,
                categoria: categoria.value
            },
            dataType: 'JSON',
            success: function(resp) {
                alert(resp.message);

                window.location.reload();
            },
            error: function (request) {
                var error = document.getElementById('error');
                var msgError = document.getElementById('msgError');
                msgError.innerHTML = request.responseJSON.message;
                error.classList.remove('d-none');
                //alert(request.responseJSON.message);
            }
        })
    }

    function salvarModalProduto(event, e) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: e.action,
            data: {
                _token: document.getElementsByName('_token')[0].value,
                produto: produto.value,
            },
            dataType: 'JSON',
            success: function(resp) {
                var prod = document.getElementById('comboProduto');
                prod.innerHTML += '<option value="'+resp.newproduto.id+'" selected>'+resp.newproduto.nome+'</option>'

                //fechar modal Produto
                document.getElementById('close-modal-produto').click();
            },
            error: function (request) {
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
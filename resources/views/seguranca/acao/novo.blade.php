@extends('seguranca.layouts.default')
@section('conteudo')
    <form id="form" class="form-horizontal container">
        <fieldset>
            <legend>Inclusão de Ação</legend>
            <div class="form-group">
                <label class="col-sm-2 control-label">Ação *</label>
                <div class="col-sm-4">
                    <input type="text" name="nome" required placeholder="sugestão: modulo/controlador/acao" class="form-control" value="{{$acao}}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Descrição</label>
                <div class="col-sm-6">
                    <input type="text" name="descricao" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox col-sm-offset-2">
                    <label>
                        <input class="checkbox-inline" type="checkbox" id="destaque" name="destaque"> Destaque (aparece para o usuário na tela de permissões do perfil)
                    </label>
                </div>
            </div>
            <div id="div_amigavel" class="form-group" style="display: none">
                <label class="col-sm-2 control-label">Nome amigável *</label>
                <div class="col-sm-3">
                    <input type="text" name="nome_amigavel" placeholder="Ex: Cadastrar usuário" class="form-control">
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Dependências</legend>
            <div class="form-group">
                <label class="col-sm-2 control-label">Ação</label>
                <div class="col-sm-3">
                    <select id="acao2" class="form-control">
                        <option value="">selecione</option>
                        @foreach($aAcao as $acao)
                        <option value="{{$acao->id}}">{{$acao->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-5 col-md-offset-2">
                    <button title="adicionar" type="button" class="btn btn-default" id="adicionar">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </button>
                    <button title="remover" type="button" class="btn btn-default" id="remover">
                        <span class="glyphicon glyphicon-minus-sign"></span>
                    </button>
                </div>
            </div><br>
            <div id="conteudo_grid" class="col-sm-7 col-sm-offset-2">
                <table id="grid" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <br>
            </div>
            <div id="mensagem" class="alert alert-danger col-sm-6 col-sm-offset-2" style="display: none;"></div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary" id="salvar">Salvar</button>
                    <a class="btn btn-default" id="cancelar" href="{{url('seguranca/acao')}}">Cancelar</a><br>&nbsp;<br>
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
@section('scripts')
    <script src="{{asset('js/jquery.js')}}"></script>
    @include('seguranca.layouts.datatables-simples', ['colunas' => ['acao']])
    <script>
        jQuery(function(){
            $('#adicionar').click(function(){
                if($('#acao2').val() == 0) {
                    alert('Selecione uma ação');
                }

                if(oTable.row('#'+$('#acao2').val()).data() != undefined) {
                    alert('Ação repetida');
                    return false;
                }

                oTable.row.add({
                    'DT_RowId': $('#acao2').val(),
                    'acao': $('#acao2 :selected').text() + '<input type="hidden" value="' + $('#acao2').val() + '" name="dependencia[]">'
                }).draw();
            });

            $('#remover').click(function(){
                oTable.rows('.selected').remove().draw();
            });

            $('#destaque').click(function() {
                if ($('#destaque').is(':checked'))
                    $('#div_amigavel').fadeIn();
                else
                    $('#div_amigavel').fadeOut('fast');
            });

            $('#form').submit(function(){
                $.ajax({
                    method: "POST",
                    url: "{{url('seguranca/acao/store')}}",
                    data: $('#form').serialize().replace(/[^&]+=\.?(?:&|$)/g, '').replace(/&$/, ''),
                    success: function(json) {
                        if(json.msg) {
                            $('#mensagem').removeClass('alert-danger')
                                    .addClass('alert-success')
                                    .html('<ul>'+json.msg+'</ul>')
                                    .fadeIn();
                        }
                    },
                    error: function(data) {
                        var html = '';
                        if(data.status != 422) {
                            $('#mensagem').html('<ul><li>Falha ao salvar formulário. Erro '+data.status+'</li></ul>')
                                    .removeClass('alert-success')
                                    .addClass('alert-danger')
                                    .fadeIn();
                        } else {
                            $.each(data.responseJSON, function(index, value) {
                                html += '<li>'+ value +'</li>'
                            });

                            $('#mensagem').html('<ul>'+html+'</ul>')
                                    .removeClass('alert-success')
                                    .addClass('alert-danger')
                                    .fadeIn();
                        }
                    }
                });
                return false;
            });
        });
    </script>
@endsection
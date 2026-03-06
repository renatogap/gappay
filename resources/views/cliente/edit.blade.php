@extends('layouts.default')
@section('conteudo')
    <h3>
        Editar Cliente
        <a href="{{url('cliente/list')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>  
    </h3>
    <hr>

    <form id="formulario" method="post" action="{{ url('cliente/store') }}" autocomplete="off" onsubmit="aguarde(this)">
        {{ @csrf_field() }}

        <input type="hidden" name="id" value="{{$cliente->id}}">
        <input type="hidden" name="tipo" value="{{$cliente->fk_tipo_cliente}}"> <!-- ALUNO -->
        <input type="hidden" name="data" value="{{ $cliente->created_at }}"  class="form-control">

        <!-- INFORMAÇÃO PARA O CARTÃO DO CLIENTE -->
        <input type="hidden" name="valor" value="0">
        <input type="hidden" name="tipoPagamento" value="1"> <!-- DINHEIRO - ROBERTO PAGARÁ -->
        <input type="hidden" name="valorCartao" value="{{ (old('valorCartao')) ?? config('parque.valor_cartao') }}" required>
        <input type="hidden" name="creditoCartao" value="0">

        
        <!-- <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <div><label>Data</label></div>
                    <input type="text" name="data" value="{{ date('d/m/Y H:i')}}"  class="form-control">
                </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <div><label>Tipo de Cliente *</label></div>
                    <select name="tipo" class="form-control" required onchange="showOrHideEscola(this.value)">
                        @foreach($tiposCliente as $v)
                            <option {{ $v->id == $cliente->fk_tipo_cliente ? 'selected' : '' }} value="{{ $v->id }}">{{ $v->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-8 <?= !$cliente->fk_tipo_cliente ? 'd-none' : '' ?>">
                <div class="form-group">
                    <div><label>Escola *</label></div>
                    <select name="escola" class="form-control" required>
                        <option value="">SELECIONE...</option>
                        @foreach($escolas as $e)
                            <option {{ ($e->id == $cliente->fk_escola) ? 'selected' : '' }} value="{{ $e->id }}">{{ $e->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div><label>Nome do Titular *</label></div>
                    <input type="text" name="nome" class="form-control" required value="{{ $cliente->nome ?? '' }}" placeholder="Nome do Titular">
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>CPF *</label></div>
                    <input type="tel" name="cpf" class="form-control" value="{{ $cliente->cpf ? formatarCpfCnpj($cliente->cpf) : '' }}" maxlength="14" onkeydown="fMasc(this, mCPF);">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Telefone *</label></div>
                    <input type="tel" name="telefone" class="form-control" value="{{ $cliente->telefone ?? '' }}">
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Dia do Vencimento *</label></div>
                    <select name="diaVencimento" class="form-control">
                        <option value="{{ $cliente->dia_vencimento }}">Todo dia {{ $cliente->dia_vencimento }}</option>
                        <option value="5">Todo dia 05</option>
                        <option value="10">Todo dia 10</option>
                        <option value="15">Todo dia 15</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div><label>ID do Cartão *</label></div>                    
                    <input type="text" name="codigo" class="form-control" value="{{ $cartao->codigo ?? '' }}" placeholder="Leitura do cartão">
                    <span style="color: #555;"> 
                        <i class="material-icons" style="font-size: 2em; color: #333; float: right; margin: -1.2em 0.2em;">
                            qr_code_scanner
                        </i>
                    </span>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Valor Pago *</label></div>
                    <input type="tel" name="valor" id="valorPago" class="form-control" onkeyup="changeValor(this.value)" required value="{{ (old('valor')) ?? '' }}">
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Tipo de pagamento *</label></div>
                    <select name="tipo_pagamento" class="form-control" required>
                            @foreach($formaPagamento as $p)
                                <option {{ (old('tipo_pagamento'))==$p->id ? 'selected' : '' }} value="{{ $p->id }}">{{ $p->nome }}</option>
                            @endforeach
                        </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Valor do Caução</label></div>
                    <input type="tel" name="valorCartao" id="valorCartao" readonly class="form-control" value="{{ (old('valorCartao')) ?? config('parque.valor_cartao') }}" required>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Crédito do Cartão</label></div>
                    <input type="tel" name="creditoCartao" id="creditoCartao" readonly class="form-control" value="{{ (old('creditoCartao')) ?? '0.00' }}">
                </div>
            </div>
        </div>
        -->


        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <div><label>Observacao</label></div>
                    <textarea name="observacao" class="form-control">{{$cliente->observacao ?? ''}}</textarea>
                </div>
            </div>
        </div>

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

        <div class="row">
            <div class="form-group">
                <button type="submit" id="btnSalvar" class="btn btn-parque ml-3 btn"><i class="material-icons icone">save</i> Salvar</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDependente" onclick="modalNovo()">Novo Dependênte</button>
            </div>
        </div>
    </form>

    @if($dependentes->count() > 0)
        <hr>

        <h3>Dependentes</h3>

        <table class="table table-responsive table-bordered" width="100%">
            <thead>
                <tr style="background: {{ config('parque.btn-parque') }}; color: white;">
                    <th>&nbsp;</th>
                    <th>Nome</th>
                    <th>Parentesco</th>
                    <th>Telefone</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody>
            @foreach($dependentes as $d)
                <tr>
                    <td width="1%" align="center">
                        <a href="#" data-toggle="modal" data-target="#modalDependente" onclick="editarDependente(<?= $d->id ?>)">
                            <i class="material-icons icone">edit</i>
                        </a>
                    </td>
                    <td width="50%">{{$d->nome}}</td>
                    <td>{{$d->grau_parentesco}}</td>
                    <td>{{$d->telefone}}</td>
                    <td align="center">
                        <span class="badge badge-{{$d->status ? 'success' : 'danger'}}">
                            {{$d->status ? 'Ativo' : 'Inativo'}}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif


    <!-- Modal -->
    <div id="modalDependente" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formDependente" method="post" onsubmit="return false" action="{{url('dependente/store')}}">
                {{ @csrf_field() }}

                <input type="hidden" name="id" id="idDependente">
                <input type="hidden" name="id_cliente" value="{{$cliente->id}}">
                <input type="hidden" name="tipo" value="4"> <!-- DEPENDENTE -->
                <input type="hidden" name="tipoPagamento" value="1"> <!-- DINHEIRO - ROBERTO PAGARÁ -->
                <input type="hidden" name="creditoCartao" value="0">
                <input type="hidden" name="valorCartao" value="{{ config('parque.valor_cartao') }}">

                <div class="modal-content">
                    <div class="modal-header" style="background: <?= config('parque.background') ?>; color: white;">
                        <h5 class="modal-title">
                            Dependênte
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div><label>Nome do Titular</label></div>
                                    <input type="text" disabled class="form-control" value="{{ $cliente->nome }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div><label>Nome do Dependênte*</label></div>
                                    <input type="text" name="nomeDependente" id="nomeDependente" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <div><label>CPF *</label></div>
                                    <input type="tel" name="cpfDependente" id="cpfDependente" class="form-control" maxlength="14" onkeydown="fMasc(this, mCPF);">
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <div><label>Telefone *</label></div>
                                    <input type="tel" name="telefoneDependente" id="telefoneDependente" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <div><label>Grau de Parentesco*</label></div>
                                    <select name="grauParentesco" id="grauParentesco" class="form-control" required>
                                        <option value="">SELECIONE...</option>
                                        @foreach($grauParentesco as $v)
                                            <option value="{{ $v->id }}">{{ $v->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <div><label>ID do Cartão *</label></div>                    
                                    <input type="text" name="codigo" id="codigoDependente" class="form-control">
                                    <span style="color: #555;">
                                        Leitura do cartão 
                                        <i class="material-icons" style="font-size: 2em; color: #333; float: right; margin: -1.2em 0.2em;">
                                            qr_code_scanner
                                        </i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="error" class="alert alert-danger d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-parque btn" onclick="salvarDependente()">Salvar</button>
                        <button type="button" id="btAtivar" class="btn btn-success btn d-none" onclick="ativarDependente()">Ativar</button>
                        <button type="button" id="btInativar" class="btn btn-danger btn d-none" onclick="inativarDependente()">Inativar</button>
                        <button type="button" class="btn btn-secondary btn" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var formDependente = document.getElementById('formDependente');
    var divEscola      = document.getElementById('divEscola');
    var escola         = document.getElementById('escola');
    var btSalvar       = document.getElementById('btnSalvar');

    function showOrHideEscola(tipo_cliente) {
        if(tipo_cliente == 3){
            divEscola.classList.add('d-none');
            escola.value = '';
        }else if(tipo_cliente == 2){
            divEscola.classList.remove('d-none');
        }
    }

    function salvarDependente() {

        formData = new FormData(formDependente);

        axios.post(formDependente.action, formData)
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                var divError = document.getElementById('error');
                divError.innerHTML = '<b>Alerta</b> '+error.response.data.error;
                divError.classList.remove('d-none');
            })
    }

    function editarDependente(id) {
        axios.get(BASE_URL+'dependente/edit/'+id)
            .then((response) => {
                formDependente.idDependente.value = response.data.id;
                formDependente.nomeDependente.value = response.data.nome;
                formDependente.cpfDependente.value = response.data.cpf;
                formDependente.telefoneDependente.value = response.data.telefone;
                formDependente.grauParentesco.value = response.data.fk_grau_parentesco;
                formDependente.codigoDependente.value = response.data.codigo;

                if(response.data.status == 1) {
                    document.getElementById('btInativar').classList.remove('d-none');
                    document.getElementById('btAtivar').classList.add('d-none');
                }else {
                    document.getElementById('btAtivar').classList.remove('d-none');
                    document.getElementById('btInativar').classList.add('d-none');
                }
            })
            .catch((error) => {
                var divError = document.getElementById('error');
                divError.innerHTML = '<b>Alerta!</b> '+error.response.data.error;
                divError.classList.remove('d-none');
            })
    }

    function ativarDependente() {
        axios.get(BASE_URL+'dependente/ativar/'+formDependente.idDependente.value)
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                var divError = document.getElementById('error');
                divError.innerHTML = '<b>Alerta!</b> '+error.response.data.error;
                divError.classList.remove('d-none');
            })
    }

    function inativarDependente() {
        axios.get(BASE_URL+'dependente/inativar/'+formDependente.idDependente.value)
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                var divError = document.getElementById('error');
                divError.innerHTML = '<b>Alerta!</b> '+error.response.data.error;
                divError.classList.remove('d-none');
            })
    }

    function modalNovo() {
        formDependente.idDependente.value = '';
        formDependente.nomeDependente.value = '';
        formDependente.cpfDependente.value = '';
        formDependente.telefoneDependente.value = '';
        formDependente.grauParentesco.value = '';
        formDependente.codigoDependente.value = '';
    }

    function aguarde(e) {
        btSalvar.textContent = 'Aguarde...';
        btSalvar.disabled = true;

        if(!validaForm()) {
            btSalvar.textContent = 'Salvar';
            btSalvar.disabled = false;
        }
    }

    function validaForm() {
        valida = true;

        var msgError = new Array();

        $('#formulario :input').not('button').each(function(i, v){
            if(v.required) {
                if(!v.value){
                    msgError.push('O campo '+v.parentNode.parentNode.children[0].textContent+' é obrigatório.');
                    valida = false;
                }
            }
        });

        return valida;
    }


    function fMasc(objeto,mascara) {
        obj=objeto
        masc=mascara
        setTimeout("fMascEx()",1)
    }
    function fMascEx() {
        obj.value=masc(obj.value)
    }
    
    function mCPF(cpf){
        cpf=cpf.replace(/\D/g,"")
        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
        cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
        return cpf
    }
</script>
@endsection

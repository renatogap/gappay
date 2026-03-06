@extends('layouts.default')
@section('conteudo')
    <h3>
        Cadastrar Cliente
        <a href="{{url('cliente/list')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>  
    </h3>
    <hr>

    <form id="formulario" method="post" action="{{ url('cliente/store') }}" autocomplete="off" onsubmit="aguarde(this)">
        {{ @csrf_field() }}

        <input type="hidden" name="id" value="">

        <!-- INFORMAÇÃO PARA O CARTÃO DO CLIENTE -->
        <input type="hidden" name="data" value="{{ date('d/m/Y H:i')}}"  class="form-control">
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
                    <select name="tipo" class="form-control" required onchange="changeEscola(this.value)">
                        @foreach($tiposCliente as $v)
                            <option {{ (old('tipo')==$v->id) ? 'selected' : '' }} value="{{ $v->id }}">{{ $v->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="divEscola" class="col-md-8">
                <div class="form-group">
                    <div><label>Escola</label></div>
                    <select name="escola" id="escola" class="form-control">
                        <option value="">SELECIONE...</option>
                    @foreach($escolas as $e)
                        <option {{ (old('escola')==$e->id || $escolas->count() == 1) ? 'selected' : '' }} value="{{ $e->id }}">{{ $e->nome }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div><label>Nome do Titular*</label></div>
                    <input type="text" name="nome" class="form-control" required value="{{ (old('nome')) ?? '' }}">
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>CPF *</label></div>
                    <input type="tel" name="cpf" class="form-control" value="{{ (old('cpf')) ?? '' }}" maxlength="14" onkeydown="fMasc(this, mCPF);">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Telefone *</label></div>
                    <input type="tel" name="telefone" class="form-control" value="{{ (old('telefone')) ?? '' }}">
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>Dia do Vencimento *</label></div>
                    <select name="diaVencimento" class="form-control">
                        <option value="05">Todo dia 05</option>
                        <option value="10">Todo dia 10</option>
                        <option value="15">Todo dia 15</option>
                    </select>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <div><label>ID do Cartão *</label></div>
                    <input type="text" name="codigo" class="form-control" value="{{ (old('codigo')) ?? '' }}">
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
            <div class="col-md-12">
                <div class="form-group">
                    <div><label>Observacao</label></div>
                    <textarea name="observacao" class="form-control">{{ (old('observacao')) ?? 'Cadastro do cliente...' }}</textarea>
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
        @if (isset($errors) && count($errors) > 0)
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Erros encontrados!</strong>
            <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
        </div>
        @endif

        <div class="row">
            <div class="form-group">
                <button type="submit" id="btnSalvar" class="btn btn-parque ml-3 btn"><i class="material-icons icone">save</i> Salvar</button>
            </div>
        </div>

    </form>

@endsection

@section('scripts')
<script>
    var btSalvar = document.getElementById('btnSalvar');
    var divEscola = document.getElementById('divEscola');
    var escola = document.getElementById('escola');

    function changeEscola(tipo_cliente) {
        if(tipo_cliente == 3){
            divEscola.classList.add('d-none');
            escola.value = '';
        }else if(tipo_cliente == 2){
            divEscola.classList.remove('d-none');
        }
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

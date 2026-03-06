@extends('layouts.default')
@section('conteudo')
    <h3>
        Clientes
        <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>  
    </h3>
    <hr>

    <div class="input-group float-left" style="width: 60%;">
        <input type="text" id="inputBusca" class="form-control" value="{{$texto ?? ''}}" placeholder="Pesquisar..." onkeyup="pesquisar()">
        <div class="input-group-append" style="cursor: pointer;" onclick="pesquisar()">
            <span class="input-group-text" id="basic-addon2"><span class="material-icons">search</span></span>
        </div>
    </div>
    <a href="{{url('cliente/create')}}" class="btn btn-primary float-right"><i class="material-icons icone">add</i> Novo Cliente</a>
    <br /><br />

    @if($clientes->count() > 0)
        <table class="table table-responsive table-hover" width="100%">
            <thead>
                <tr style="background: {{ config('parque.btn-parque') }}; color: white;">
                    <th>&nbsp;</th>
                    <th>Cliente</th>
                    <!-- <th>Tipo</th> -->
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th title="Nº de Dependentes">Nº Dep.</th>
                    <th title="Dia de Vencimento">Venc.</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($clientes as $c)
                <tr>
                    <td width="1%" align="center">
                        <a href="{{url('cliente/edit/'.$c->id)}}">
                            <i class="material-icons icone">edit</i>
                        </a>
                    </td>
                    <td width="40%">{{$c->nome}}</td>
                    <!-- <td>{{$c->tipo_cliente}}</td> -->
                    <td>{{$c->telefone}}</td>
                    <td>{{ formatarCpfCnpj($c->cpf)}}</td>
                    <td align="center">{{$c->dependentes}}</td>
                    <td align="center">{{$c->dia_vencimento}}</td>
                    <td align="center">
                        <span class="badge badge-{{$c->status ? 'success' : 'danger'}}">
                            {{$c->status ? 'Ativo' : 'Inativo'}}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">Nenhum cliente cadastrado.</div>
    @endif



@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var btSalvar = document.getElementById('btnSalvar');
    var inputBusca = document.getElementById('inputBusca');
    var formDependente = document.getElementById('formDependente');
    
    function pesquisar() {
        if(event.key == 'Enter' || event.key == undefined) {
            window.location = BASE_URL+'cliente/list/'+inputBusca.value;
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

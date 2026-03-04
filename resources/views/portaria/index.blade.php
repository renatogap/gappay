@extends('layouts.default')
@section('conteudo')
    <h4>
        Controle de Portaria
        <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>  
    </h4>
    <hr>
    @if (session('sucesso'))
        <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session('sucesso') !!}
        </div>
    @endif
    

    <form id="formulario" method="post" action="{{ url('portaria/validar-entrada') }}" autocomplete="off" onsubmit="salvar(this)">
        {{ @csrf_field() }}

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div><label>Faça a leitura do cartão</label></div>
                            <input type="tel" name="codigo" id="codigo" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div><label>ou informe o CPF</label></div>
                            <input type="tel" name="cpf" id="cpf" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-parque btn-block">Validar Entrada</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <fieldset style="border: 1px solid #ccc; height: 210px; border-radius: 5px">
                    <legend class="pr-3 pl-3 ml-3" style="width: auto;">Aguardando validação</legend>

                    <div id="divLiberar" class="d-none alert alert-success p-4 m-2 mt-4" style="font-size: 30px;">
                        Entrada Liberada!
                    </div>

                    <div id="divLiberarComAlerta" class="d-none alert alert-warning pr-4 pl-4 pb-4 m-2">
                        <div style="font-size: 30px;">Entrada Liberada!</div>
                        <div id="msgInfo"></div>
                    </div>

                    <div id="divBloquear" class="d-none alert alert-danger pr-4 pl-4 pb-4 m-2">
                        <div style="font-size: 30px;">Entrada Bloqueada!</div>
                        <div id="msgError"></div>
                    </div>
                </fieldset>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var form        = document.getElementById('formulario');
    var divLiberar  = document.getElementById('divLiberar');
    var divBloquear = document.getElementById('divBloquear');
    var divLiberarComAlerta = document.getElementById('divLiberarComAlerta');
    var msgInfo     = document.getElementById('msgInfo');    
    var msgError    = document.getElementById('msgError');    
    var codigo      = document.getElementById('codigo');
    var cpf         = document.getElementById('cpf');

    function salvar(e) {
        event.preventDefault();

        if(!codigo.value && !cpf.value) {
            alert('Faça a leitura do código do cartão ou digite o CPF do cliente.');
            return false;
        }

        var formData = new FormData(form);

        axios.post(e.action, formData)
            .then((response) => {
                if(response.data.success) {
                    divLiberar.classList.remove('d-none');
                    divBloquear.classList.add('d-none');
                    divLiberarComAlerta.classList.add('d-none');
                }
                else if(response.data.info) {
                    divLiberarComAlerta.classList.remove('d-none');
                    divLiberar.classList.add('d-none');
                    divBloquear.classList.add('d-none');
                    msgInfo.innerHTML = response.data.info;
                }
            })
            .catch((error) => {
                divBloquear.classList.remove('d-none');
                divLiberar.classList.add('d-none');
                divLiberarComAlerta.classList.add('d-none');
                msgError.innerHTML = error.response.data.error;
            })
    }
</script>
@endsection
@extends('layouts.default')


@section('conteudo')
<h5>
   Selecione um aluno para realizar o pedido
    <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>
<hr>
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

<form id="form" method="get" action="{{url('pedido/selecionar-aluno')}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Nome do Aluno</label>
                <input type="text" name="nome" id="nome" class="form-control" value="{{ ($request->nome ?? '') }}">
            </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-parque">
                    <span class="material-icons icone">search</span> Pesquisar
                </button>
            </div>
        </div>
    </div>
</form>

@if($lista->count() > 0)
<div class="list-group">
    @foreach($lista as $i => $v)
    {{-- <a href="{{ url('pedido/cardapio/1?aluno='.Crypt::encryptString($v->id)) }}" class="list-group-item"> --}}
    <a href="{{ url('pedido/cardapio/1?aluno='.$v->id) }}" class="list-group-item">
        <div style="color: #666;">
            <span style="float: right; color: #666; font-size: 11px;">
                {{ $v->data }}
            </span>
            <span style="font-size: 14px;">
                @if($v->fk_cliente_titular)
                <i class="material-icons" title="Aluno" style="color: green;">school</i>
                @endif
                <strong>{{ $v->nome }}</strong>
            </span>
            <br>
            @if($v->responsavel)
            <span style="color: #999; font-size: 12px;">RESPONSÁVEL: {{ $v->responsavel }}</span>
            <br>
            @endif
            <span class="{{ ($v->valor_atual > 0 ? 'text-success' : 'text-danger') }}">R$ {{ $v->valor_atual }}</span>
            <span class="float-right" style="font-size: 14px;">
                {!! $v->status_desc !!}
            </span>
        </div>
    </a>
    @endforeach
</div>
@else
<div class="alert alert-info">Nenhum registro encontrado.</div>
@endif

@endsection

@section('scripts')
<script type="text/javascript" src="{{url('js/instascan.min.js')}}"></script>
<script type="text/javascript" src="{{url('js/app/controllers/EntradaClienteController.js')}}"></script>
<script>
    var oController = new EntradaClienteController();

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
            window.location = BASE_URL + 'cartao-cliente/create/' + content;
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
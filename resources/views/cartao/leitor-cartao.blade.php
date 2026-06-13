@extends('layouts.default')

@section('conteudo')
    <h5>
        Localizar o aluno
        <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h5>
    <hr>
    @if (session('sucesso'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            {!! session('sucesso') !!}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            {!! session('error') !!}
        </div>
    @endif

    <form id="form" method="get" action="{{url('relatorio/consultar-pedidos')}}">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Nome do Aluno</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div style="position: relative; flex-grow: 1;">
                            <input type="text" name="nome" id="nome" class="form-control" autocomplete="off"
                                value="{{ ($request->nome ?? '') }}" style="padding-right: 2.5rem;" placeholder="Buscar...">
                            <button type="button" id="limpar-nome"
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #999; display: none; outline: none; padding: 0;">
                                <span class="material-icons" style="font-size: 20px; vertical-align: middle;">close</span>
                            </button>
                        </div>
                        <button class="btn btn-secondary btn-sm" type="button" data-toggle="modal" data-target="#modal"
                            onclick="lerQrCode()"
                            style="height: 38px; display: inline-flex; align-items: center; justify-content: center;"
                            title="Ler QR Code">
                            <span class="material-icons" style="font-size: 20px;">qr_code_scanner</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if($lista->count() > 0)
        <div class="list-group">
            @foreach($lista as $i => $v)
                <a href="{{ url('cartao-cliente/add-credito/' . $v->cartao_codigo) }}" class="list-group-item">
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
                            <span style="color: #999; font-size: 12px;">Responsável: {{ $v->responsavel }}</span>
                            <br>
                        @endif
                        <span class="{{ ($v->valor_atual > 0 ? 'text-success' : 'text-danger') }}">R$
                            {{ $v->valor_atual }}</span>
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

    <!-- Modal -->
    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="material-icons icone" style="font-size: 30px;">qr_code_scanner</span> Aproxime o cartão
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="window.location.reload()">
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
                        <span class="material-icons"
                            style="font-size: 2.5em; position: absolute; z-index: 1; color: orange; cursor: pointer;">flip_camera_ios</span>
                    </div>

                    <video id="preview" style="width: 100%;"></video>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{url('js/instascan.min.js')}}"></script>
    <script>
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

            scanner.addListener('scan', function (content) {
                scanner.stop();
                play();
                $('#div-aguarde').removeClass('d-none');
                document.getElementById('preview').classList.add('d-none');
                document.getElementById('mudarCamera').innerHTML = '';
                window.location = BASE_URL + 'cartao-cliente/add-credito/' + content;
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

        const inputNome = document.getElementById('nome');
        const btnLimpar = document.getElementById('limpar-nome');

        function filtrarAlunos() {
            const value = inputNome.value;
            const texto = value.toLowerCase().trim().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
            const items = document.querySelectorAll('.list-group-item');
            let encontrados = 0;

            if (btnLimpar) {
                btnLimpar.style.display = value ? 'block' : 'none';
            }

            items.forEach(item => {
                const strongElement = item.querySelector('strong');
                if (strongElement) {
                    const nome = strongElement.textContent.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");

                    if (nome.includes(texto)) {
                        item.style.display = '';
                        encontrados++;
                    } else {
                        item.style.display = 'none';
                    }
                }
            });

            const listGroup = document.querySelector('.list-group');
            let mensagemVazia = document.getElementById('pesquisa-vazia');

            if (encontrados === 0) {
                if (!mensagemVazia) {
                    mensagemVazia = document.createElement('div');
                    mensagemVazia.id = 'pesquisa-vazia';
                    mensagemVazia.className = 'alert alert-info';
                    mensagemVazia.textContent = 'Nenhum registro encontrado para a busca.';
                    if (listGroup) {
                        listGroup.parentNode.insertBefore(mensagemVazia, listGroup.nextSibling);
                    }
                } else {
                    mensagemVazia.style.display = '';
                }
                if (listGroup) {
                    listGroup.style.display = 'none';
                }
            } else {
                if (mensagemVazia) {
                    mensagemVazia.style.display = 'none';
                }
                if (listGroup) {
                    listGroup.style.display = '';
                }
            }
        }

        if (inputNome) {
            inputNome.addEventListener('input', filtrarAlunos);
            inputNome.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    filtrarAlunos();
                }
            });

            if (btnLimpar) {
                btnLimpar.addEventListener('click', function () {
                    inputNome.value = '';
                    filtrarAlunos();
                    inputNome.focus();
                });
            }

            if (inputNome.value) {
                filtrarAlunos();
            }
        }
    </script>
@endsection
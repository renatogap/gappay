@extends('layouts.default')
@section('conteudo')
<style>
    .pedidos-lista {
        max-width: 100%;
        padding: 0;
    }

    .pedido-item {
        display: grid;
        grid-template-columns: 1fr;
        /* gap: 1.5em; */
        align-items: center;
        padding: 0.8em 1.2em;
        background: white;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        border-left: 4px solid #3153e7;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        margin-bottom: 1em;
    }

    .pedido-item:hover {
        background: #f8f9ff;
        padding-left: 1.5em;
        box-shadow: 0 2px 8px rgba(49, 83, 231, 0.1);
        text-decoration: none;
    }


    .pedido-numero {
        font-weight: 700;
        color: #3153e7;
    }

    .pedido-numero a {
        color: #3153e7;
        text-decoration: none;
    }

    .pedido-numero a:hover {
        text-decoration: underline;
    }

    .pedido-data {
        font-size: 0.9em;
        color: #666;
    }

    .pedido-aluno {
        font-size: 1em;
        font-weight: bold;
    }

    .pedido-tempo {
        /* text-align: right; */
        /* font-weight: 700; */
        font-size: 0.9em;
        color: #666;
    }

    .tempo-alerta {
        color: #666;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .alerta {
        padding: 1em;
        border-radius: 8px;
        margin-bottom: 1.5em;
        display: flex;
        align-items: flex-start;
        gap: 0.8em;
    }

    .alerta.sucesso {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #2e7d32;
    }

    .alerta.erro {
        background: #ffebee;
        color: #c62828;
        border-left: 4px solid #c62828;
    }

    .alerta .material-icons {
        font-size: 1.3em;
        flex-shrink: 0;
        margin-top: 0.1em;
    }

    .alerta-vazio {
        background: #f0f4ff;
        border: 2px dashed #3153e7;
        border-radius: 8px;
        padding: 2em;
        text-align: center;
        color: #666;
    }

    .alerta-vazio .material-icons {
        font-size: 3em;
        color: #3153e7;
        margin-bottom: 0.5em;
        opacity: 0.7;
    }

    .alerta-vazio h3 {
        color: #333;
        font-size: 1.1em;
        margin: 0.5em 0;
    }

    .alerta-vazio p {
        margin: 0;
        font-size: 0.9em;
    }

    .pesquisa-container {
        position: relative;
        margin-bottom: 1.5em;
    }

    .pesquisa-input {
        width: 100%;
        padding: 0.9em 2.5em 0.9em 1.2em;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95em;
        transition: all 0.3s;
        font-family: inherit;
    }

    .pesquisa-input:focus {
        outline: none;
        border-color: #3153e7;
        box-shadow: 0 0 0 3px rgba(49, 83, 231, 0.1);
    }

    .pesquisa-input::placeholder {
        color: #999;
    }

    .pesquisa-limpar {
        position: absolute;
        right: 1em;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
        font-size: 1.2em;
        transition: all 0.3s;
        display: none;
        background: none;
        border: none;
        padding: 0;
        line-height: 1;
    }

    .pesquisa-limpar:hover {
        color: #3153e7;
    }

    .pesquisa-input:not(:placeholder-shown)~.pesquisa-limpar {
        display: block;
    }

    .pedido-item.hidden {
        display: none;
    }

    .btn-qrcode {
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        border: none;
        padding: 0.8em 1.5em;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        margin-bottom: 1.5em;
        transition: all 0.3s;
        font-size: 0.95em;
    }

    .btn-qrcode:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(49, 83, 231, 0.3);
    }

    .btn-qrcode .material-icons {
        font-size: 1.2em;
    }

    /* Modal QR Code */
    .modal-qrcode {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-qrcode.ativo {
        display: flex;
    }

    .modal-qrcode-conteudo {
        background: white;
        border-radius: 12px;
        padding: 2em;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-qrcode-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5em;
        padding-bottom: 1em;
        border-bottom: 2px solid #f0f0f0;
    }

    .modal-qrcode-header h3 {
        margin: 0;
        color: #333;
        font-size: 1.3em;
        display: flex;
        align-items: center;
        gap: 0.8em;
    }

    .modal-qrcode-header .material-icons {
        color: #3153e7;
        font-size: 1.5em;
    }

    .modal-qrcode-fechar {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
        color: #999;
        transition: color 0.3s;
    }

    .modal-qrcode-fechar:hover {
        color: #333;
    }

    #preview {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        margin-bottom: 1.5em;
        background: #000;
    }

    .modal-qrcode-info {
        background: #e3f2fd;
        color: #1565c0;
        padding: 1em;
        border-radius: 8px;
        font-size: 0.9em;
        display: flex;
        gap: 0.8em;
        align-items: flex-start;
    }

    .modal-qrcode-info .material-icons {
        flex-shrink: 0;
        margin-top: 0.2em;
    }

    .btn-trocar-camera {
        position: absolute;
        top: 1em;
        right: 1em;
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .btn-trocar-camera:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(255, 152, 0, 0.4);
    }

    .btn-trocar-camera .material-icons {
        color: white;
        font-size: 1.5em;
    }

    .video-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    @media (max-width: 768px) {
        .pedido-item {
            grid-template-columns: 1fr;
            /* gap: 0.5em; */
            padding: 0.8em 1em;
            border: 1px solid #f0f0f0;
            border-left: 6px solid #3153e7;
        }

        .pedido-valor {
            font-size: 1.3em;
        }

        .pedido-numero {
            font-size: 0.9em;
        }

        .pedido-tempo {
            text-align: left;
        }
    }
</style>

<h5>
    <i class="material-icons">restaurant</i>
    Pedidos Pendentes

    <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em !important; color: #333;">
        keyboard_backspace
    </a>
</h5>

<hr>

<div class="pedidos-lista">
    @if(session('sucesso'))
    <div class="alerta sucesso">
        <i class="material-icons">check_circle</i>
        <p>{!! session('sucesso') !!}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="alerta erro">
        <i class="material-icons">error</i>
        <p>{!! session('error') !!}</p>
    </div>
    @endif

    @if(count($pedidos) > 0)
    <div class="pesquisa-container">
        <input
            type="text"
            id="pesquisaPedido"
            class="pesquisa-input"
            placeholder="Buscar...">
        <button class="pesquisa-limpar" onclick="document.getElementById('pesquisaPedido').value = ''; filtrarPedidos();" title="Limpar pesquisa">
            <i class="material-icons">close</i>
        </button>
    </div>
    @endif

    <button class="btn-qrcode" onclick="abrirModalQRCode()">
        <i class="material-icons">qr_code_scanner</i>
        Ler QR Code do Pedido
    </button>

    @if(count($pedidos) > 0)
    @foreach($pedidos as $pedido)

    <?php
    $datetime1 = new DateTime($pedido->dt_pedido);
    $datetime2 = new DateTime(date('Y-m-d H:i:s'));
    $interval = date_diff($datetime1, $datetime2);
    $minutos_total = ($interval->h * 60) + $interval->i;
    $alerta_tempo = $minutos_total > 30 ? 'tempo-alerta' : '';
    ?>
    <a href="{{ url('pedido/historico-pedido-gerente/'.$pedido->id) }}" class="pedido-item">
        <div class="pedido-numero">Pedido #{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }} <span class="float-right pedido-valor">R$ {{ $pedido->valor_total }}</span> </div>
        <!-- <div class="pedido-data">{{ date('d/m H:i', strtotime($pedido->dt_pedido)) }}h</div> -->
        <div class="pedido-tempo {{ $alerta_tempo }}">Tempo: {{ $interval->h }}h {{ $interval->i }}min</div>
        <div class="pedido-aluno">{{ $pedido->nome_cliente }}</div>
    </a>

    @endforeach
    @else
    <div class="alerta-vazio">
        <i class="material-icons">check_circle</i>
        <h3>Nenhum pedido pendente</h3>
        <p>Todos os pedidos foram processados.</p>
    </div>
    @endif
</div>

<!-- Modal para ler QR Code -->
<div id="modalQRCode" class="modal-qrcode">
    <div class="modal-qrcode-conteudo">
        <div class="modal-qrcode-header">
            <h3>
                <i class="material-icons">qr_code_scanner</i>
                Ler QR Code do Pedido
            </h3>
            <button class="modal-qrcode-fechar" onclick="fecharModalQRCode()">
                <i class="material-icons">close</i>
            </button>
        </div>
        <div class="video-container">
            <button class="btn-trocar-camera" onclick="trocarCameraQR()" title="Trocar câmera">
                <i class="material-icons">flip_camera_ios</i>
            </button>
            <video id="preview" style="width: 100%;"></video>
        </div>
        <div class="modal-qrcode-info">
            <i class="material-icons">info</i>
            <p style="margin: 0;">Aponte para o QR code para lê-lo. Ao detectar, o pedido será finalizado automaticamente.</p>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{url('js/instascan.min.js')}}"></script>
<script>
    let scanner;
    let cameraAtiva = false;
    let indexCameraQR = 0;
    let todasAsCameras = [];
    let modalQrCode = document.getElementById('modalQRCode');

    function abrirModalQRCode() {
        modalQrCode.classList.add('ativo');
        inicializarScanner();
    }

    function fecharModalQRCode() {
        modalQrCode.classList.remove('ativo');
        pararScanner();
    }

    function inicializarScanner() {
        if (cameraAtiva) return;

        scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            mirror: false
        });

        scanner.addListener('scan', function(content) {
            scanner.stop();
            cameraAtiva = false;
            fecharModalQRCode();

            // Redirecionar para a URL decodificada (que é a URL de entrega do pedido)
            window.location.href = content;
        });

        Instascan.Camera.getCameras().then(cameras => {
            todasAsCameras = cameras;
            if (cameras.length > 0) {
                // Prefere câmera traseira se disponível
                indexCameraQR = cameras.length > 1 ? 1 : 0;
                scanner.start(cameras[indexCameraQR]);
                cameraAtiva = true;
            } else {
                alert('Nenhuma câmera encontrada no dispositivo!');
            }
        }).catch(err => {
            console.error('Erro ao acessar câmera:', err);
            alert('Erro ao acessar a câmera do dispositivo.');
        });
    }

    function pararScanner() {
        if (scanner && cameraAtiva) {
            scanner.stop();
            cameraAtiva = false;
        }
    }

    function trocarCameraQR() {
        if (todasAsCameras.length <= 1) {
            alert('Apenas uma câmera disponível');
            return;
        }

        scanner.stop();
        indexCameraQR++;

        if (indexCameraQR >= todasAsCameras.length) {
            indexCameraQR = 0;
        }

        scanner.start(todasAsCameras[indexCameraQR]);
    }

    // Fechar modal ao pressionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fecharModalQRCode();
        }
    });

    // Fechar modal ao clicar fora
    modalQrCode.addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModalQRCode();
        }
    });
</script>
<script>
    const inputPesquisa = document.getElementById('pesquisaPedido');

    function filtrarPedidos() {
        const texto = inputPesquisa.value.toLowerCase().trim();
        const items = document.querySelectorAll('.pedido-item');
        let encontrados = 0;

        items.forEach(item => {
            const numero = item.querySelector('.pedido-numero').textContent.toLowerCase();
            const aluno = item.querySelector('.pedido-aluno').textContent.toLowerCase();

            if (numero.includes(texto) || aluno.includes(texto)) {
                item.classList.remove('hidden');
                encontrados++;
            } else {
                item.classList.add('hidden');
            }
        });

        // Mostrar/ocultar mensagem de sem resultados
        let mensagemVazia = document.getElementById('pesquisa-vazia');
        if (texto && encontrados === 0) {
            if (!mensagemVazia) {
                mensagemVazia = document.createElement('div');
                mensagemVazia.id = 'pesquisa-vazia';
                mensagemVazia.style.cssText = 'background: #f0f4ff; border: 2px dashed #3153e7; border-radius: 8px; padding: 2em; text-align: center; color: #666; margin-top: 1em;';
                mensagemVazia.innerHTML = '<i class="material-icons" style="font-size: 3em; color: #3153e7; margin-bottom: 0.5em; opacity: 0.7; display: block;">search_off</i><h3 style="color: #333; font-size: 1.1em; margin: 0.5em 0;">Nenhum resultado</h3><p style="margin: 0;">Nenhum pedido encontrado para "' + inputPesquisa.value + '"</p>';
                document.querySelector('.pedidos-lista').appendChild(mensagemVazia);
            } else {
                mensagemVazia.innerHTML = '<i class="material-icons" style="font-size: 3em; color: #3153e7; margin-bottom: 0.5em; opacity: 0.7; display: block;">search_off</i><h3 style="color: #333; font-size: 1.1em; margin: 0.5em 0;">Nenhum resultado</h3><p style="margin: 0;">Nenhum pedido encontrado para "' + inputPesquisa.value + '"</p>';
                mensagemVazia.style.display = 'block';
            }
        } else if (mensagemVazia) {
            mensagemVazia.style.display = 'none';
        }
    }

    if (inputPesquisa) {
        inputPesquisa.addEventListener('input', filtrarPedidos);
        inputPesquisa.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                filtrarPedidos();
            }
        });
    }
</script>
@endsection
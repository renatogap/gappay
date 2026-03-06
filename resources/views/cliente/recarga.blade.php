@extends('layouts.default')
@section('conteudo')
<style>
    .recarga-container {
        max-width: 100%;
        padding: 0;
    }

    .recarga-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2em;
        border-radius: 10px;
        margin-bottom: 2em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .recarga-header h2 {
        margin: 0;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1.8em;
    }

    .recarga-header .material-icons {
        margin-right: 15px;
        font-size: 2em;
    }

    .voltar-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        color: #667eea;
        text-decoration: none;
        margin-bottom: 1.5em;
        font-weight: 500;
        transition: color 0.3s;
    }

    .voltar-btn:hover {
        color: #764ba2;
    }

    .voltar-btn .material-icons {
        font-size: 1.2em;
    }

    .formulario-container {
        background: white;
        padding: 2em;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        max-width: 500px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.8em;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.8em;
        color: #333;
        font-size: 0.95em;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }

    .form-group label .material-icons {
        font-size: 1.2em;
        color: #667eea;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 1em;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1em;
        transition: all 0.3s ease;
        background: white;
        color: #333;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group input::placeholder {
        color: #999;
    }

    .valor-info {
        background: #f8f9fa;
        padding: 1.2em;
        border-radius: 8px;
        margin-top: 0.8em;
        border-left: 4px solid #667eea;
    }

    .valor-info p {
        margin: 0;
        color: #666;
        font-size: 0.9em;
    }

    .valor-info strong {
        color: #333;
        font-size: 1.1em;
    }

    .forma-selecionada {
        background: #f8f9fa;
        padding: 1.2em;
        border-radius: 8px;
        margin-top: 0.8em;
        border-left: 4px solid #667eea;
        display: none;
    }

    .forma-selecionada p {
        margin: 0;
        color: #666;
        font-size: 0.9em;
    }

    .forma-selecionada strong {
        color: #333;
        font-size: 1.1em;
        display: flex;
        align-items: center;
        gap: 0.5em;
        margin-top: 0.5em;
    }

    .forma-selecionada .material-icons {
        color: #667eea;
        font-size: 1.3em;
    }

    .btn-solicitar {
        width: 100%;
        padding: 1.1em;
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1em;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-solicitar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-solicitar:active {
        transform: scale(0.98);
    }

    .btn-solicitar:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-solicitar .material-icons {
        font-size: 1.2em;
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


    .btn-modal {
        padding: 0.9em 1.5em;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95em;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5em;
    }



    .alerta {
        padding: 1em;
        border-radius: 8px;
        margin-bottom: 1.5em;
        display: flex;
        align-items: flex-start;
        gap: 0.8em;
    }

    .alerta.info {
        background: #e3f2fd;
        color: #1565c0;
        border-left: 4px solid #1565c0;
    }

    .alerta .material-icons {
        font-size: 1.3em;
        flex-shrink: 0;
        margin-top: 0.1em;
    }

    .alerta p {
        margin: 0;
        font-size: 0.95em;
    }

    @media (max-width: 600px) {
        .formulario-container {
            padding: 1.5em;
        }

        .recarga-header h2 {
            font-size: 1.4em;
        }

        .modal-content {
            padding: 1.5em;
        }

        .modal-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<h5>
    <i class="material-icons icone">currency_exchange</i>
    Recarregar Crédito

    <a href="{{ url('cliente/home') }}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>
<hr>

<div class="recarga-container">


    <div class="alerta info">
        <i class="material-icons">info</i>
        <p>
            Informe o valor que deseja creditar em sua conta e escolha a forma de pagamento.
            Você receberá uma confirmação após a transação.
        </p>
    </div>

    <form action="{{ url('cliente/recarga/store') }}" class="formulario-container" id="formRecarga" method="post">
        @csrf
        <input type="hidden" id="product_name" name="product_name" value="Recarga de Crédito">
        <div class="form-group">
            <label for="price">
                <i class="material-icons">attach_money</i>
                Valor da Recarga
            </label>
            <input
                type="text"
                id="price"
                name="price"
                placeholder="R$ 0,00"
                required>

        </div>

        <button type="submit" class="btn-solicitar" id="btnSolicitar">
            <i class="material-icons">check_circle</i>
            Confirmar Recarga
        </button>

        @if(session('error'))
        <div class="alerta info" style="margin-top: 1.5em; background: #ffebee; color: #c62828; border-color: #c62828;">
            <i class="material-icons">error</i>
            <p>{{ session('error') }}</p>
            @endif
    </form>
</div>


<script src="https://js.stripe.com/clover/stripe.js"></script>
<script>
    const formRecarga = document.getElementById('formRecarga');
    const inputValor = document.getElementById('price');


    // Aplicar máscara ao input de preço
    inputValor.addEventListener('input', function(e) {
        let valor = e.target.value;
        valor = valor.replace(/\D/g, '');

        if (valor) {
            let valorFormatado = (parseInt(valor) / 100).toFixed(2);
            valorFormatado = valorFormatado.replace('.', ',');
            const partes = valorFormatado.split(',');
            partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            e.target.value = partes.join(',');
        }
    });


    // Converter para número antes de enviar
    formRecarga.addEventListener('submit', function(e) {
        let valor = inputValor.value.replace(/\D/g, '');
        if (valor) {
            inputValor.value = (parseInt(valor) / 100).toFixed(2);
        }
    });
</script>
@endsection
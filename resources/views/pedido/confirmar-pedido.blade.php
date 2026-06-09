@extends('layouts.default')
<style>
    .boas-vindas {
        background: linear-gradient(135deg, #3153e7 0%, #043795 100%);
        color: white;
        padding: 2em;
        border-radius: 10px;
        margin-bottom: 2em;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .boas-vindas h2 {
        margin: 0 0 0.5em 0;
        font-size: 1.6em;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .boas-vindas .material-icons {
        font-size: 2em;
    }

    .boas-vindas p {
        margin: 0;
        opacity: 0.95;
        font-size: 0.95em;
    }
</style>

@section('conteudo')

<div>
    <h4>
        <span class="material-icons icone" style="color: green;">check_circle_outline</span>
        Confirmar pedido
        <a href="{{url('pedido/cardapio/1?aluno='.session('cliente')->id)}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h4>
    <br>

    <div class="boas-vindas">
        <h2>
            <i class="material-icons">person</i>
            {{ session('cliente')->nome }} <br/>
            
        </h2>
        <p style="display: flex; justify-content: space-between; align-items: center; margin: 0;">
            <span>Responsável: {{ session('cliente')->responsavel->nome }}</span>
            <span style="font-weight: 600; color: {{ (session('cliente')->valor_atual > 0 ? '#4caf50' : '#f44336') }};">
                R$ {{ number_format(session('cliente')->valor_atual, 2, ',', '.') }}
            </span>
        </p>
    </div>

    @if (session('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session('error') !!}
    </div>
    @endif

    <?php $valorTotal = array_sum(array_column($pedido, 'valor')); ?>

    <table class="table" width="100%">
        @foreach($pedido as $indice => $p)
        <?php $cardapio = App\Models\Entity\Cardapio::find($p->id_cardapio) ?>

        <tr>
            <td width="1%" style="padding: 0;">
                <a href="{{ url('pedido/confirmar-pedido?remove='.$indice) }}" class="btn btn-sm float-left" title="Remover pedido">
                    <i class="material-icons" style="color: darkred;">delete</i>
                </a>
            </td>
            <td width="20%" style="padding-top: 4px;">
                <b>
                    {{ $p->quantidade }} 
                    <!-- {{ $cardapio->categoria->nome }}:  -->
                    {{ $cardapio->nome_item }}</b>
                @if(isset($p->observacao))
                <br>{{ $p->observacao }}
                @endif
            </td>
            <td width="2%" align="right" style="padding-top: 4px;">
                {{ number_format($p->valor, 2, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </table>

    <div style="font-size: 1.2em; text-align: right;">
        <div>Subtotal: R$ {{ number_format($valorTotal, 2, ',', '.') }}</div>
    </div>

    <?php
        $taxaServico = (in_array(3, $perflUsuario) ? ($valorTotal * (10 / 100)) : 0);
    ?>

    <div style="font-size: 1.2em; text-align: right;">
        <input type="checkbox" name="taxaServico" id="taxaServico" value="1" {{ (in_array(3, $perflUsuario) ? 'checked' : 'disabled') }} onclick="changeTaxa(this)">

        Comissão de venda: R$
        <span id="valorTaxaServico">{{ number_format($taxaServico, 2, ',', '.') }}</span>
        <span class="d-none" id="zeroTaxa">0,00</span>
    </div>

    <div style="font-size: 1.5em; text-align: right;">
        <strong>
            Total Geral: R$ <span id="valorComTaxa">{{ number_format(($valorTotal + $taxaServico), 2, ',', '.') }}</span>
            <span id="valorSemTaxa" class="d-none">{{ number_format(($valorTotal), 2, ',', '.') }}</span>
        </strong>
    </div>

    <br><br><br>
    <div style="clear: both;">
        {{-- <a href="#" data-url="{{ url('pedido/finalizar/leitor') }}" onclick="abrirLeitorCartao(this)" class="btn btn-parque btn-lg btn-block">Finalizar pedido</a> --}}
        <a href="#" data-url="{{ url('pedido/finalizar/'.session('cliente')->cartao->codigo) }}" onclick="abrirLeitorCartao(this)" class="btn btn-parque btn-lg btn-block">Finalizar pedido</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var valorTaxaServico = document.getElementById('valorTaxaServico');
    var zeroTaxa = document.getElementById('zeroTaxa');
    var valorComTaxa = document.getElementById('valorComTaxa');
    var valorSemTaxa = document.getElementById('valorSemTaxa');


    function abrirLeitorCartao(e) {
        window.location = e.dataset.url + '?taxaServico=' + document.getElementById('taxaServico').checked;
    }

    function changeTaxa(e) {
        if (e.checked) {
            valorTaxaServico.classList.remove('d-none');
            zeroTaxa.classList.add('d-none');

            valorSemTaxa.classList.add('d-none');
            valorComTaxa.classList.remove('d-none');
        } else {
            valorTaxaServico.classList.add('d-none');
            zeroTaxa.classList.remove('d-none');

            valorSemTaxa.classList.remove('d-none');
            valorComTaxa.classList.add('d-none');
        }
    }
</script>
@endsection
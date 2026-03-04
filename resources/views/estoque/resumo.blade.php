


@if($estoquePdvs)
<html>
    <head>
        <title>Resumo do Estoque</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
        table {
            border-collapse: collapse;
        }
        </style>
    </head>
    <body>

        <h3 style="margin-bottom: 0;">RETÓRIO DO ESTOQUE</h3>
        <h4>Período: {{ date('d/m/Y', strtotime($dtInicio)) }} {{ $horaInicio }} à {{ date('d/m/Y', strtotime($dtTermino)) }} {{ $horaTermino }}</h4>

        <table border="1" width="100%">
        <tr>
            <th>PDV</th>
            <th>Produto</th>
            <th width="5%">Estoque Inicial</th>
            <th width="5%">Entrada Estoque</th>
            <th width="5%">Estoque Total</th>
            <th width="5%">Venda</th> <!-- Saída -->
            <th width="5%">Saída Manual</th> <!-- Saída Manual -->
            <th width="5%">Sobra</th>
            <th width="10%">Valor Unitário</th>
            <th width="10%">Valor Total</th>
        </tr>

        <?php $totalGeral = 0; ?>

        @foreach($estoquePdvs as $pdv => $produtos)
            @foreach($produtos as $produto => $item)
                <?php
                    $EI = 0;
                    $E = 0;
                    $S = 0;
                    $SM = 0;
                    $qtd = 0;
                    $vunit = 0;
                    $vtotal = 0;
                    

                    if(isset($item['EI'])) {
                        $EI = $item['EI'];
                    }
                    
                    if(isset($item['E'])) {
                        list($qtd, $vunit, $vtotal) = explode('_', $item['E']);
                        $E = $qtd;
                    }

                    if(isset($item['S'])) {
                        list($qtd, $vunit, $vtotal) = explode('_', $item['S']);
                        $S = $qtd;
                    }

                    if(isset($item['SM'])) {
                        list($qtd, $vunit, $vtotal) = explode('_', $item['SM']);
                        $SM = $qtd;
                    }

                    $saldo = ($EI + $E - $S - $SM);

                    $vtotal = (($S + $SM) * $vunit);

                ?>
                <tr>
                    <td>{{ mb_strtoupper($pdv) }}</td>
                    <td>{{ mb_strtoupper($produto) }}</td>
                    <td align="center" style="color: <?= $EI > 0 ? 'green' : '#000' ?>">{{ $EI }}</td>
                    <td align="center" style="color: <?= $E > 0 ? 'green' : '#000' ?> ">{{ $E }}</td>
                    <td align="center" style="color: <?= ($EI + $E) > 0 ? 'green' : '#000' ?>">{{ ($EI + $E) }}</td>
                    <td align="center" style="color: <?= $S > 0 ? 'red' : '#000' ?> ">{{ $S }}</td>
                    <td align="center" style="color: <?= $SM > 0 ? 'red' : '#000' ?> ">{{ $SM }}</td>
                    <td align="center" style="color: <?= $saldo > 0 ? 'green' : $saldo < 0 ? 'red' : '#000' ?>">{{ $saldo }}</td>
                    <td align="right">R$ {{ number_format($vunit, 2, ',', '.') }}</td>
                    <td align="right">R$ {{ number_format($vtotal, 2, ',', '.') }}</td>
                </tr>

                <?php $totalGeral = ($totalGeral + $vtotal); ?>
            @endforeach
        @endforeach

            <tr>
                <td colspan="9" align="right"><b>TOTAL</b></td>
                <td align="right"><b>R$ {{ number_format($totalGeral, 2, ',', '.') }}</b></td>
            </tr>
        </table>

    
    </body>
</html>
@else        
    <div class="alert alert-info">Nenhum registro para o período informado.</div>
    <br>
    <a href="{{url('estoque/relatorio')}}" class="btn btn-parque">Voltar</a>
@endif

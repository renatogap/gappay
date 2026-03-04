<?php

use App\Models\Entity\CardapioTipo;
use App\Models\Entity\UsuarioTipoCardapio;
use GapPay\Seguranca\Models\Entity\SegGrupo;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getTiposDeCardapio')) {
     function getTiposDeCardapio()
     {
        $perfilUsuario = SegGrupo::where('usuario_id', Auth::user()->id)->get()->pluck('perfil_id')->toArray();
        
        //Para o perfil PDV e Cozinha
        if(in_array(4, $perfilUsuario) || in_array(5, $perfilUsuario)) {
            $tiposCardapios = UsuarioTipoCardapio::where('fk_usuario', Auth::user()->id)->get()->pluck('fk_tipo_cardapio')->toArray();
        }else {
            $tiposCardapios = CardapioTipo::orderBy('nome')->where('status',  1)->get()->pluck('id')->toArray();
        }

        return $tiposCardapios;
     }
 }

 if (!function_exists('getTiposDeCardapioAdmin')) {
    function getTiposDeCardapioAdmin()
    {
        $perfilUsuario = SegGrupo::where('usuario_id', Auth::user()->id)->get()->pluck('perfil_id')->toArray();

        //Para o perfil PDV e Cozinha
        if (in_array(4, $perfilUsuario) || in_array(5, $perfilUsuario)) {
            $tiposCardapios = UsuarioTipoCardapio::where('fk_usuario', Auth::user()->id)->get()->pluck('fk_tipo_cardapio')->toArray();
        } else {
            
            $tiposCardapios = CardapioTipo::orderBy('nome')->where('status', 1)->get()->pluck('id')->toArray();
        }

        return $tiposCardapios;
    }
}

 if (!function_exists('arredondar')) {
    function arredondar($valorTotalPedido)
    {
        $taxaServico = ($valorTotalPedido * (10 / 100));

        $arr = explode('.', $taxaServico);

        if(count($arr) > 1) {

            $arr[1] = (strlen($arr[1]) < 2) ? intval($arr[1].'0') : $arr[1];

            if($arr[1] >= 51) {
                $taxaServico = ($arr[0] + 1);
            }else {
                $taxaServico = $arr[0];
            }
        }

       return $taxaServico;
    }
}


if (!function_exists('getQuantidadeGarrafas')) {
    function getQuantidadeGarrafas($qtd, $dose, $tipo)
    {
        $qtdGarrafas = $qtd / $dose; 

        $aGarrafas = explode(',', $qtdGarrafas);

        if(isset($aGarrafas[1]) && $aGarrafas[1] > 0) {
            $qtdGarrafas = $aGarrafas[0] + 1;
        }else {
            $qtdGarrafas = $aGarrafas[0];
        }

        if($tipo == 'E'){
            $quantidade = $qtdGarrafas;
        }else {
            $quantidade = $qtd;
        }       

       return $quantidade;
    }
}

if (!function_exists('formatarCpfCnpj')) {
    function formatarCpfCnpj($doc) {
    
        $doc = preg_replace("/[^0-9]/", "", $doc);
        $qtd = strlen($doc);

        if($qtd >= 11) {

            if($qtd === 11 ) {

                $docFormatado = substr($doc, 0, 3) . '.' .
                                substr($doc, 3, 3) . '.' .
                                substr($doc, 6, 3) . '-' .
                                substr($doc, 9, 2);
            } else {
                $docFormatado = substr($doc, 0, 2) . '.' .
                                substr($doc, 2, 3) . '.' .
                                substr($doc, 5, 3) . '/' .
                                substr($doc, 8, 4) . '-' .
                                substr($doc, -2);
            }

            return $docFormatado;

        } else {
            return 'Documento invalido';
        }
    }
}

if (!function_exists('todosMeses')) {
    function todosMeses()
    {
        return [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];
    }
}

if (!function_exists('formatarMoedaDB')) {
    function formatarMoedaDB($value)
    {
        $newValue = str_replace('.', '', $value);
        $newValue = str_replace(',', '.', $newValue);
        return $newValue;
    }
}

if (!function_exists('mesPorExtenso')) {
    function mesPorExtenso($mes)
    {
        switch ($mes) {
            case 1:
                return 'Janeiro';
                break;
            case 2:
                return 'Fevereiro';
                break;
            case 3:
                return 'Março';
                break;
            case 4:
                return 'Abril';
                break;
            case 5:
                return 'Maio';
                break;
            case 6:
                return 'Junho';
                break;
            case 7:
                return 'Julho';
                break;
            case 8:
                return 'Agosto';
                break;
            case 9:
                return 'Setembro';
                break;
            case 10:
                return 'Outubro';
                break;
            case 11:
                return 'Novembro';
                break;
            case 12:
                return 'Dezembro';
                break;
            default:
                return 'Valor Inválido';
        }
    }
}
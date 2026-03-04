<?php

if (!function_exists('printvar')) {
    function printvar($args)
    {
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha = $dbt[0]['line'];
        $arquivo = $dbt[0]['file'];
        echo "<fieldset style='border:1px solid; border-color:#F00;background-color:#FFF000;legend'><b>Arquivo:</b>$arquivo<b><br>Linha:</b><legend><b>Debug On : printvar()</b></legend> $linha</fieldset>";

        foreach ($args as $key => $arg) {
            echo "<fieldset style='background-color:#CBA; border:1px solid; border-color:#00F;'><legend><b>ARG[$key]</b><legend>";
            echo "<pre style='background-color:#CBA; width:100%; heigth:100%;'>";
            print_r($arg);
            echo "</pre>";
            echo "</fieldset><br />";
        }
    }
}

if (!function_exists('printvardie')) {
    function printvardie($args)
    {
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha = $dbt[0]['line'];
        $arquivo = $dbt[0]['file'];
        echo "<fieldset style='border:1px solid; border-color:#F00;background-color:#FFF000;legend'><b>Arquivo:</b>$arquivo<b><br>Linha:</b><legend><b>Debug On : printvar()</b></legend> $linha</fieldset>";

        foreach ($args as $key => $arg) {
            echo "<fieldset style='background-color:#CBA; border:1px solid; border-color:#00F;'><legend><b>ARG[$key]</b><legend>";
            echo "<pre style='background-color:#CBA; width:100%; heigth:100%;'>";
            print_r($arg);
            echo "</pre>";
            echo "</fieldset><br />";
        }
        exit();
    }
}

if (!function_exists('printVarAjax')) {
    /**
     * Mesma funcao do printvar mas não imprime com formatacao html
     * facilitando a exibicao no firebug
     * @param <type> $args
     * @since 27/05/2009
     * @author Philipe Barra
     */
    function printVarAjax($args)
    {
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha = $dbt[0]['line'];
        $arquivo = $dbt[0]['file'];
        echo "=================================================================================\n";
        echo "Arquivo:" . $arquivo . "\nLinha:$linha\nDebug On : printvarajax ( )\n";
        echo "=================================================================================\n";

        foreach ($args as $idx => $arg) {
            echo "-----  ARG[$idx]  -----\n";
            print_r($arg);
            echo "\n \n";
        }
    }
}
if (!function_exists('printVarDieAjax')) {
    /**
     * Mesma funcao do printdie mas não imprime com formatacao html
     * facilitando a exibicao no firebug
     * @param <type> $args
     * @since 25/05/2009
     * @author Philipe Barra
     */
    function printVarDieAjax($args)
    {
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha = $dbt[0]['line'];
        $arquivo = $dbt[0]['file'];
        echo "=================================================================================\n";
        echo "Arquivo:" . $arquivo . "\nLinha:$linha\nDebug On : printvardieajax ( )\n";
        echo "=================================================================================\n";

        foreach ($args as $idx => $arg) {
            echo "-----  ARG[$idx]  -----\n";
            print_r($arg);
            echo "\n \n";
        }
        exit();
    }
}

if (!function_exists('exibirErro')) {

    function exibirErro(\Exception $e, string $mensagem): array {

        if (config('app.debug')) {
            return ['message' => $e->getMessage()];
        } else {
            return ['message' => $mensagem];
        }
    }

}

if (!function_exists('isDebug')) {

    function debugEstaAtivo(): bool {
        return config('app.debug');
    }
}

//if (!function_exists('semAcentoMinusculo')) {
//
//    /**
//     * Substitui acentuação por letras simples e todas em minúsculo
//     *
//     * @param string $string
//     * @return string
//     */
//    function semAcentoMinusculo($string): string
//    {
//        //removendo acentuação
//        $keys = array();
//        $values = array();
//        preg_match_all('/./u', 'áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ', $keys);
//        preg_match_all('/./u', 'aaaaeeiooouucAAAAEEIOOOUUC', $values);
//        $mapping = array_combine($keys[0], $values[0]);
//
//        $string = strtr($string, $mapping);
//        $string = strtolower($string);
//        return trim($string);
//    }
//}
//if (!function_exists('simplificarString')) {
//
//    /**
//     * Simplifica uma string substituindo espaços por traço -
//     * acentuação por letras simples e tudo minúsculo
//     *
//     * @param string $string
//     * @return string
//     */
//    function simplificarString($string): string
//    {
//        //removendo acentuação
//        $string = semAcentoMinusculo($string);
//        $string = preg_replace('/\s/', '-', $string);
//        return trim($string);
//    }
//}

//if(!function_exists('permissaoAcesso')) {
//    /**
//     * Verifica se um usuário pode acessar um endereço
//     * @param $url
//     * @param \GapPay\Seguranca\App\Models\Entity\Usuario|null $usuario
//     * @return bool
//     */
//    function permissaoAcesso($url, \GapPay\Seguranca\App\Models\Entity\Usuario $usuario = null): bool {
//        if(!$usuario) {
//            $usuario = auth()->user();
//        }
//
//        return \GapPay\Seguranca\App\Models\Regras\SegAcaoRegras::permissaoDeAcesso($url, $usuario);
//    }
//}

if(!function_exists('schema')) {
    /**
     * Retorna o schema de uma determinada conexão
     * @param string $nome
     * @return string
     */
    function schema(string $nome): string {
        return config("database.connections.$nome.search_path");
    }
}

if (!function_exists('isLogAtivo')) {
    /**
     * Retorna True
     * @return bool
     */
    function isLogAtivo(): bool {
        return config("policia.registrar_log");
    }
}

if (!function_exists('isControleAcessoAtivo')) {
    /**
     * Retorna True
     * @return bool
     */
    function isControleAcessoAtivo(): bool {
        return config("policia.controle_acesso");
    }
}



//if (!function_exists('primeiroUltimoNome')) {
//    function primeiroUltimoNome($nomeCompleto)
//    {
//        $nomes = explode(' ', $nomeCompleto);
//        $ultimoNome = $nomes[count($nomes) -1];
//
//        return ucwords("$nomes[0] " . $ultimoNome);
//    }
//}

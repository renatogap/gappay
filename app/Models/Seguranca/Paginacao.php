<?php

/**
 * Classe para ajudar na manipulação dos dados que serão exibidos no DataTables
 */

namespace App\Models\Seguranca;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;

class Paginacao
{

    /**
     * Retorna um array no formato aceito pelo DataTables
     * Recebe um objeto sql ainda não paginado pelo laravel
     * @param Builder $x
     * @param bool $dataTablesID Se true pesquisa por um campo chamado id que será atribuido como id da linha
     * do DataTables
     * @return array
     */
    public static function dataTables(Builder $x, $dataTablesID = false)
    {
        $x = $x->paginate(request('length', 10)); //variável criada automaticamente pelo DataTables
        $a = $x->toArray();
        $c = [];
        foreach ($a['data'] as $item) {
            $colunas = [];

            if ($dataTablesID && isset($item->id)) {
                $colunas['DT_RowId'] = $item->id;
            }

            foreach ($item as $k => $v) {
                $colunas[$k] = $v;
            }
            $c[] = $colunas;
        }

        return [
            'recordsTotal' => $a['total'],
            'recordsFiltered' => $a['total'],
            'draw' => intval(request('draw')),
            'data' => $c,
        ];
    }

    /**
     * Retorna um array no formato aceito pelo DataTables
     * Rece um array já paginado e pronto para ser exibido na tela, faltando apenas formatação
     * formato do array aceito:
     * array(
     *       '0' => ....qualquer coisa
     *       '1' => ....qualquer coisa
     *          .........
     *      'total' => 123 ->total de registros da paginação
     * );
     *
     * esta função também aceita um array vazio para nenhum resultado encontrado
     *
     * @param array $a
     * @param bool $dataTablesID
     * @return array
     */
    public static function dataTablesArray(array $a, $dataTablesID = false)
    {
        if (!empty($a)) {
            $total = $a['total'];
            unset($a['total']);

            foreach ($a as $item) {
                $colunas = [];

                if ($dataTablesID && isset($item['id'])) {
                    $colunas['DT_RowId'] = $item['id'];
                }

                foreach ($item as $k => $v) {
                    $colunas[$k] = $v;
                }
                $c[] = $colunas;
            }
        }

        if ($total > 1000) {
            $total = 1000;
        }
//limite padrão do elasticsearch

        return [
            'recordsTotal' => $total ? $total : 0,
            'recordsFiltered' => $total ? $total : 0,
            'draw' => intval(request('draw')),
            'data' => isset($c) ? $c : [],
        ];
    }

    public static function paginarArray(array $array, $perPage, $pageStart = 1)
    {
        $offset = ($pageStart * $perPage) - $perPage;
        return new Paginator(array_slice($array, $offset, $perPage, true), $perPage, $pageStart,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    public static function paginaElasticSearch($resultado, $length)
    {
        $aaData = array();
        foreach ($resultado['hits']['hits'] as $a) {
            $colunas = array();

            foreach ($a['_source'] as $k => $l) {
                $colunas[$k] = $l;
            }
            $aaData[] = $colunas;
        }

        $aGrid = array(
            "sEcho" => $length, //página atual
            "iTotalRecords" => $resultado['hits']['total'], //último elemento da página (contador contínuo)ex: exibindo de x até ->20<- elementos
            //            "iTotalRecords" => $pagina * $limite, //último elemento da página (contador contínuo)ex: exibindo de x até ->20<- elementos
            "iTotalDisplayRecords" => $resultado['hits']['total'], //total de resultados sem paginacao
            //            "iTotalDisplayRecords" => $resultado['hits']['total'], //total de resultados sem paginacao
            "aaData" => $aaData,
        );

        return $aGrid;
    }
}

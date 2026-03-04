<?php

namespace GapPay\Seguranca\Models\Regras;

use GapPay\Seguranca\Models\Entity\SegAcao;

class TelaRegras
{
    /**
     * Cadastra uma tela do frontend (destaque = true)
     * @param string $url
     * @param string $nomeAmigavel
     * @param string $grupo
     * @return SegAcao
     */
    public static function cadastrar(string $url, string $nomeAmigavel, string $grupo): SegAcao
    {
//        $urlTratada = preg_replace("@/\d+/@", '/:id/', $url);

        return SegAcao::create([
            'nome' => $url,
            'nome_amigavel' => $nomeAmigavel,
            'grupo' => $grupo,
            'destaque' => true,
        ]);
    }

    public static function analisar(array $rotas)
    {
        //estão no banco, mas não no front
        $lixo = SegAcao::whereNotIn('nome', $rotas)
            ->select(['id', 'nome'])
//            ->where('id', '>', 1000)//menor que 1000 são rotas do segurança
            ->where('method', 'like', 'GET')//todas as telas são GET
            ->where('nome', 'not like', "api/%");//eliminado as rotas da api

        //cadastradas no banco e não são lixo
        $cadastradasNoBancoMenosLixo = SegAcao::whereNotIn('id', $lixo->pluck('id'))
            //->where('id', '>', 1000)//menor que 1000 são rotas do segurança
            ->where('method', 'GET')//todas as telas são GET
            ->where('nome', 'not like', "api/%")//eliminado as rotas da api
            ->get(['id', 'nome']);

        //filtrando somente as que estão na tela (para cadastrar)
        $resposta = [];
        foreach($rotas as $key => $r) {
            if (!$cadastradasNoBancoMenosLixo->contains('nome', '=', $r['nome'])) {
                $r['id'] = $key;//key chave única para funcionar o grid do vuetify
                $resposta[] = $r;
            }
        }

        return [
            'lixo' => $lixo->get(),
            'novas' => $resposta
        ];


    }
}

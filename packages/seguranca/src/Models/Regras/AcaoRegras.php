<?php

namespace GapPay\Seguranca\Models\Regras;

use GapPay\Seguranca\Models\Entity\SegAcao;
use GapPay\Seguranca\Models\Entity\SegDependencia;
use GapPay\Seguranca\Models\Entity\SegPerfil;
use GapPay\Seguranca\Models\Entity\SegPermissao;
use GapPay\Seguranca\Models\Facade\PerfilDB;
use GapPay\Seguranca\Models\Facade\SegAcaoDB;
use GapPay\Seguranca\Models\Form\AcaoForm;
use GapPay\Seguranca\Models\Form\PerfilForm;

class AcaoRegras
{
    public static function criar(AcaoForm $p): SegAcao
    {
        $segAcao = SegAcao::create([
            'nome' => $p->nome,
            'method' => $p->method,
            'descricao' => $p->descricao,
            'destaque' => $p->isDestaque(),
            'nome_amigavel' => $p->nome_amigavel,
            'obrigatorio' => $p->isObrigatorio(),
            'grupo' => $p->grupo,
            'log_acesso' => $p->isLogAcesso(),
            'rota_front' => $p->isRotaFront(),
        ]);

        //adicionando todas as ações que estão na tela
        foreach ($p->dependencia as $d) {
            SegDependencia::create([
                'acao_atual_id' => $segAcao->id,
                'acao_dependencia_id' => $d
            ]);
        }

        return $segAcao;
    }

    public static function atualizar(AcaoForm $p): SegAcao
    {
        $segAcao = SegAcao::findOrFail($p->id);
        $segAcao->nome = $p->nome;
        $segAcao->method = $p->method;
        $segAcao->descricao = $p->descricao;
        $segAcao->destaque = $p->destaque;
        $segAcao->nome_amigavel = $p->destaque ? $p->nome_amigavel : null;
        $segAcao->obrigatorio = $p->obrigatorio;
        $segAcao->grupo = $p->grupo;
        $segAcao->log_acesso = $p->log_acesso;
        $segAcao->save();

        $segAcao->dependencias()->sync($p->dependencia);

        // Percorrendo todos os perfis que tem acesso a esta ação e atualizando suas respectivas dependências
        $segAcao->perfis()->each(function (SegPerfil $perfil) {
            $acoesDestaque = PerfilDB::permissoesDestaqueConcedidas($perfil->id)->toArray();
            $perfilForm = PerfilForm::create([
                'id' => $perfil->id,
                'nome' => $perfil->nome,
                'permissoes' => $acoesDestaque,
            ]);
            PerfilRegras::atualizar($perfilForm);
        });

        return $segAcao;
    }

    public static function excluir(int $acao_id): void
    {
        //excluindo permissões em que esta ação aparece
        SegPermissao::where('acao_id', $acao_id)
            ->each(function ($permissao) {
                $permissao->delete();
            });

        //excluindo todas as dependências desta ação
        SegDependencia::where('acao_atual_id', $acao_id)
            ->each(function ($dependencia) {
                $dependencia->delete();
            });

        //excluindo vínculo de dependência que os outros têm com esta ação
        SegDependencia::where('acao_dependencia_id', $acao_id)
            ->each(function ($dependencia) {
                $dependencia->delete();
            });

        SegAcao::destroy($acao_id);
    }

    public static function telaCadastro(): array
    {
        return [
            'acoes' => SegAcaoDB::comboAcoes(),
            'grupos' => SegAcaoDB::comboGrupo()
        ];
    }

    public static function telaEdicao(int $acao_id): array
    {
        $segAcao = SegAcao::find($acao_id);
        $dependencias = SegAcaoDB::dependencias($acao_id);

        return [
            'acao' => $segAcao,
            'acoes' => SegAcaoDB::comboAcoes($acao_id),
            'dependencias' => $dependencias,
            'grupos' => SegAcaoDB::comboGrupo()
        ];
    }
}

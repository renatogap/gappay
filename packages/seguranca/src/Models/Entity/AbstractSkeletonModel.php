<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Model;
use GapPay\Seguranca\Models\Regras\Historico;
use GapPay\Seguranca\Models\Regras\ModeloLog;

abstract class AbstractSkeletonModel extends Model
{
    public bool $log = true;

    protected static function booted(): void
    {
        /*
         * Tenta obter a variável LOG dentro de Model::create([ 'LOG' => true/false ])
         * (se o programador usar)
         */


        static::creating(function ($o) {
            $o->log = isLogAtivo();
//            unset($o->attributes['log']);//remove, pois, essa coluna nao existe no banco
        });

        static::created(function ($o) {

            if (!isLogAtivo() && !$o->log) {//só continua se $this->log for true (padrão é true)
                return;
            }

            if (!$o->log) {
                return;
            }

            $modeloLog = new ModeloLog();
            $modeloLog->insert($o);

            $h = Historico::getInstance();
            $h->insert($modeloLog);
        });

        static::updated(function ($o) {
            if (!isLogAtivo() && !$o->log) {//só continua se $this->log for true (padrão é true)
                return;
            }

            if (!$o->log) {
                return;
            }

            $modeloLogAntes = new ModeloLog();
            $modeloLogAntes->update($o);

            $modeloLogDepois = new ModeloLog();
            $modeloLogDepois->update($o, false);

            $h = Historico::getInstance();
            $h->update($modeloLogAntes, $modeloLogDepois);
        });

        static::deleted(function ($o) {
            if (!isLogAtivo() && !$o->log) {//só continua se $this->log for true (padrão é true)
                return;
            }

            if (!$o->log) {
                return;
            }


            $modeloLog = new ModeloLog();
            $modeloLog->delete($o);

            $h = Historico::getInstance();
            $h->delete($modeloLog);
        });
    }

    /**
     * Desabilita log. A ideia é que seja usado apenas na parte pública de um sistema, pois teoricamente, o usuário
     * não estará logado e não será possível saber quem fez a modificação na tabela.
     * Este método deve ser chamado antes de chamar save() ou delete()/destroy() em um objeto
     */
    public function desabilitarLog(): void
    {
        $this->log = false;
    }

    /**
     * Habilita log. Deve ser chamado apenas se o desabilita foi usado alguma vez, pois o padrão é gerar log sempre
     * Este método deve ser chamado antes de chamar save() ou delete()/destroy() em um objeto
     */
    public function habilitarLog(): void
    {
        $this->log = true;
    }
}

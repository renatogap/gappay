<?php

namespace GapPay\Seguranca\Models\Regras;

trait HasLog
{
    public bool $log = true;
    protected static function booted(): void
    {
        /*
         * Tenta obter a variável LOG dentro de Model::create([ 'LOG' => true/false ])
         * (se o programador usar)
         */


        static::creating(function ($o) {
            $o->log = isLogAtivo() && isset($o->attributes['log']);
            unset($o->attributes['log']);//remove, pois, essa coluna nao existe no banco
        });

        static::created(function ($o) {

            if (!isLogAtivo() && !$o->log) {//só continua se $this->log for true (padrão é true)
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

            $modeloLog = new ModeloLog();
            $modeloLog->delete($o);

            $h = Historico::getInstance();
            $h->delete($modeloLog);
        });
    }
}
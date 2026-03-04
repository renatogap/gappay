<?php

namespace GapPay\Seguranca\Models\Regras;

use Illuminate\Support\Facades\DB as DBLaravel;


class DB extends DBLaravel
{
    public static function beginTransaction(): void
    {
        parent::beginTransaction();
    }

    /**
     * @throws \Exception
     */
    public static function commit(): void
    {
        $oHistorico = Historico::getInstance();
        $oHistorico->criarLog();

        parent::commit();
    }

    public static function rollBack(int $toLevel = null): void
    {
        parent::rollBack($toLevel);
    }
}

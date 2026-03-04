<?php

namespace GapPay\Seguranca\Models\Facade;

use Illuminate\Support\Facades\DB;

class AcessoDB
{
    public static function ultimasAutenticacoesValidas($ip, $user_agent)
    {
        $tempo = config('session.lifetime');
        $timezone = config('app.timezone');

        return DB::table('seguranca.acesso')
            ->where('ip', $ip)
            ->whereNull('logout')
            ->where('user_agent', $user_agent)
            ->whereRaw("timezone('$timezone', current_timestamp) between login and login + ($tempo || ' minutes')::interval")
            ->get();
    }
}

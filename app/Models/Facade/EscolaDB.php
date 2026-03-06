<?php

namespace App\Models\Facade;

use App\Models\Entity\Escola;

class EscolaDB extends Escola
{
    public static function ativas()
    {
        return parent::where('status', 1)->get();
    }
    
}

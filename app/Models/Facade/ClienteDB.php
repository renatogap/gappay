<?php

namespace App\Models\Facade;

use App\Models\Entity\Cliente;
use Illuminate\Support\Facades\DB;

class ClienteDB extends Cliente
{
    public static function find($id)
    {
        return DB::table('cliente as cli')
                    ->join('cartao as c', 'c.id', 'cli.fk_cartao')
                    ->select([
                        'cli.*', 
                        'c.codigo',
                        DB::raw("(SELECT COUNT(*) FROM dependente AS d WHERE d.fk_cliente = cli.id) as dependentes"),
                        ])
                    ->where('cli.id', $id)
                    ->first();
    }

    public static function todos()
    {
        return DB::table('cliente as cli')
                    ->join('tipo_cliente as tc', 'tc.id', 'cli.fk_tipo_cliente')
                    ->select([
                        'cli.*', 
                        DB::raw("(SELECT COUNT(*) FROM dependente AS d WHERE d.fk_cliente = cli.id) as dependentes"),
                        'tc.nome as tipo_cliente'
                    ])
                    ->get();
    }
    
    public static function grid($texto = null)
    {
        $sql = DB::table('cliente as cli')
                    ->join('tipo_cliente as tc', 'tc.id', 'cli.fk_tipo_cliente')
                    ->select([
                        'cli.*', 
                        DB::raw("(SELECT COUNT(*) FROM dependente AS d WHERE d.fk_cliente = cli.id) as dependentes"),
                        'tc.nome as tipo_cliente'
                    ]);
        if($texto) {
            $sql->where('cli.nome', 'like', "%$texto%");
            $sql->orWhere('cli.telefone', 'like', "%$texto%");
            $sql->orWhere('cli.cpf', 'like', "%$texto%");
            $sql->orWhere('tc.nome', 'like', "%$texto%");
        }
        return $sql->get();
    }

    public static function tiposDeCliente()
    {
        return DB::table('tipo_cliente')->whereNotIn('id', [1,4])->get();
    }
}

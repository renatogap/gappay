<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //create a view
        $sql = <<<SQL
CREATE OR REPLACE VIEW vw_menu AS
WITH RECURSIVE menu_recursivo AS (SELECT m.id,
                                         m.nome,
                                         m.pai,
                                         m.ordem,
                                         m.icone,
                                         m.configuracoes,
                                         m.ativo,
                                         a.nome AS acao,
                                         a.obrigatorio,
                                         p.perfil_id
                                  FROM seg_menu AS m
                                           LEFT JOIN seg_acao AS a ON a.id = m.acao_id
                                           LEFT JOIN seg_permissao AS p ON p.acao_id = m.acao_id
                                  WHERE m.pai IS NULL
                                    AND m.ativo = TRUE

                                  UNION ALL

                                  SELECT m.id,
                                         m.nome,
                                         m.pai,
                                         m.ordem,
                                         m.icone,
                                         m.configuracoes,
                                         m.ativo,
                                         a.nome AS acao,
                                         a.obrigatorio,
                                         p.perfil_id
                                  FROM seg_menu AS m
                                    JOIN menu_recursivo AS mr ON mr.id = m.pai
                                    JOIN seg_acao AS a ON a.id = m.acao_id
                                    LEFT JOIN seg_permissao as p ON p.acao_id = m.acao_id
                                  WHERE m.ativo = TRUE
)
SELECT id,
       nome,
       pai,
       ordem,
       icone,
       configuracoes,
       ativo,
       acao,
       obrigatorio,
       perfil_id
FROM menu_recursivo
SQL;
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //remove view
        DB::statement('DROP VIEW IF EXISTS vw_menu');
    }
};
<?php

namespace GapPay\Seguranca\Models\Regras;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use GapPay\Seguranca\Mail\NovoUsuarioMail;
use GapPay\Seguranca\Models\Entity\Acesso;
use GapPay\Seguranca\Models\Entity\SegGrupo;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;
use GapPay\Seguranca\Models\Facade\UsuarioDB;

class UsuarioSistemaRegras
{
    public static function editar(UsuarioForm $form): Usuario
    {
        /** @var Usuario $usuario */
        $usuario = Usuario::find($form->id);
        $usuario->nome = $form->nome;
        $usuario->email = $form->email;
        $usuario->cpf = $form->cpf;
        $usuario->excluido = false;

        if ($form->senha) {
            $usuario->senha = $form->senha;
        }

        $usuario->save();

        //Dá permissão no sistema
        $usuarioSistema = UsuarioSistema::firstOrCreate([
            'sistema_id' => config('policia.codigo'),
            'usuario_id' => $usuario->id,
        ]);

        //atualiza último acesso do usuário
        $usuarioSistema->status = 1;
        $usuarioSistema->ultimo_acesso = date('Y-m-d H:i:s');
        $usuarioSistema->save();

        //removendo todos os perfis já atribuídos que não estão na tela
        SegGrupo::where('usuario_id', $usuario->id)
            ->whereNotIn('perfil_id', $form->perfil)
            ->each(function ($grupo) {
                $grupo->delete();
            });


        //adiciona os perfis que só estão na tela e ainda não foram salvos no banco
        foreach ($form->perfil as $perfil) {

            //foi usando firstOrCreate para não haver erro caso o usuário insira o mesmo perfil novamente
            $segGrupo = SegGrupo::firstOrCreate([//localiza o perfil no banco, caso contrario cria um objeto novo pronto para inserção
                'usuario_id' => $usuario->id,
                'perfil_id' => $perfil
            ]);
            $segGrupo->save();//salva elemento novo no banco

//            if ($perfisJaAtribuidos->has($perfil) === true) {
//                $segGrupo = new SegGrupo();
//                $segGrupo->usuario_id = $usuario->id;
//                $segGrupo->perfil_id = $perfil;
//                $segGrupo->save();
//            }
        }
        return $usuario;
    }
}

<?php

namespace App\Models\Regras;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use GapPay\Seguranca\Mail\AlteracaoSenhaMail;
use GapPay\Seguranca\Mail\LinkTemporarioMail;
use GapPay\Seguranca\Mail\NovoUsuarioMail;
use GapPay\Seguranca\Models\Entity\LinkTemporario;
use GapPay\Seguranca\Models\Entity\Usuario;

class UsuarioEmailRegras
{
    public static function emailCadastroUsuario(Usuario $usuario): bool
    {
        try {
            //notificar usuário por e-mail
            $hash = Str::uuid()->toString();
            LinkTemporario::create([
                'fk_usuario' => $usuario->id,
                'data_expiracao' => date('Y-m-d H:i:s', strtotime('+2 day')),
                'usado' => false,
                'hash' => $hash,
                'data_gerado' => date('Y-m-d H:i:s')
            ]);

            $dadosDoEmail = [
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'endereco' => config('app.url'),
                'hash' => $hash
            ];

            Mail::to($usuario->email)->send(new NovoUsuarioMail($dadosDoEmail));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function emailAtualizacaoSenha(Usuario $usuario): bool
    {
        try {
            $dadosDoEmail = [
                'nome' => $usuario->nome,
                'endereco' => config('app.url'),
            ];

            Mail::to($usuario->email)->send(new AlteracaoSenhaMail($dadosDoEmail));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function emailEqueciSenha(Usuario $usuario): bool
    {
        try {
            $link = LinkTemporario::create([
                'fk_usuario' => $usuario->id,
                'data_expiracao' => date('Y-m-d H:i:s', strtotime('+1 day')),
                'usado' => false,
                'hash' => Str::uuid()->toString(),
                'data_gerado' => date('Y-m-d H:i:s')
            ]);


            $detalhes = [
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'endereco' => config('policia.url_front'),
                'link' => $link
            ];

            Mail::to($usuario->email)->send(new LinkTemporarioMail($detalhes));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
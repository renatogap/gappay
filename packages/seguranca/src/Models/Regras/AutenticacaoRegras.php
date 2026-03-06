<?php

namespace GapPay\Seguranca\Models\Regras;

use App\Models\Regras\UsuarioEmailRegras;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use GapPay\Seguranca\Mail\LinkTemporarioMail;
use GapPay\Seguranca\Models\Entity\Acesso;
use GapPay\Seguranca\Models\Entity\LinkTemporario;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;
use GapPay\Seguranca\Models\Facade\AutorizacaoDB;
use GapPay\Seguranca\Models\Facade\UsuarioDB;
//use PoliciaCivil\Sso\CookieSSO;

class AutenticacaoRegras
{
    /**
     * @param string $email
     * @param string $senha
     * @param string|null $ip
     * @param string|null $agente
     * @return void
     * @throws ValidationException|Exception
     */
    public static function autenticacao(string $email, string $senha, ?string $ip, ?string $agente): void
    {
        //verifica se usuário está cadastrado no segurança
        $usuario = UsuarioDB::validarAcesso($email, $senha);
        if (!$usuario) {
            throw new Exception('Os dados enviados estão incorretos.');
            // throw ValidationException::withMessages([
            //     'email' => ['Os dados enviados estão incorretos'],
            // ]);
        }

        //verifica se usuário possui acesso ao sistema
        if ($usuario->id !== 1) {//usuário Root não possui limitação de acesso
            $usuarioSistema = AutorizacaoDB::possuiPermissaoSistema($usuario, config('policia.codigo'));
            if (!$usuarioSistema) {
                throw new Exception('Seu usuário não possui acesso a este sistema. Entre em contato com a administração do sistema para solicitar acesso');
            }

            /**
             * Verifica se o usuário é root (seg_perfil.id = 1) ou Root (usuario.id=1)
             * Usuário root não expira
             */
            
            if (self::isUsuarioExpirado($usuario, $usuarioSistema)) {
                throw new Exception('Seu acesso expirou. Entre em contato com a administração do sistema para renovar seu acesso');
            }
        }

        //autentica usuário
        Auth::login($usuario);
        \Log::info('AutenticacaoRegras: user logged in', [
            'user_id' => $usuario->id,
            'email' => $usuario->email,
            'session_id' => session()->getId()
        ]);
        UsuarioDB::registrarLogin($usuario, $ip, $agente);
        UsuarioDB::renovarAcesso($usuario);
    }

    /**
     * Verifica se o usuário está expirado neste sistema
     * @param Usuario $usuario
     * @param UsuarioSistema $usuarioSistema
     * @return bool
     * @throws Exception
     */
    public static function isUsuarioExpirado(Usuario $usuario, UsuarioSistema $usuarioSistema): bool
    {
        if (!UsuarioDB::isRoot($usuario)) {

            //verifica se login está expirado
            $hoje = new \DateTime();
            $ultimoAcesso = new \DateTime($usuarioSistema->ultimo_acesso);
            $numeroDias = $hoje->diff($ultimoAcesso);

            //usuários que não possuem e-mail funcional expiram em 60 dias
            if ($numeroDias->days > (90)) {
                return true;
            }

            if ($numeroDias->days > config('policia.expiracao_login')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    public static function logout(): void
    {
        $acesso_id = Session::get('acesso_id');

        //registrando logout na tabela acesso
        if ($acesso_id) {
            /**
             * @var Acesso $oAcesso
             */
            $oAcesso = Acesso::find($acesso_id);
            if ($oAcesso) {
                $oAcesso->ultimo_acesso = date('Y-m-d H:i:s');
                $oAcesso->fk_sistema_logout = config('policia.codigo');
                $oAcesso->save();
            }
        }

        //faz logoff
        Auth::logout();

        //limpa sessão
        Session::flush();
    }

    /**
     * @throws ValidationException
     */
    public static function enviarEmail(string $emailCpf): Usuario
    {
        if (preg_match('/.*@.*/', $emailCpf)) {//é email
            $usuario = UsuarioDB::localizarUsuarioPorEmailOuCpf($emailCpf, null);
        } else {//cpf
            $usuario = UsuarioDB::localizarUsuarioPorEmailOuCpf(null, $emailCpf);
        }

        if (!$usuario) {
            throw ValidationException::withMessages([
                'email' => ['Dados incorretos.'],// mensagem genérica para não expor informações
            ]);
        }

        UsuarioEmailRegras::emailEqueciSenha($usuario);
        return $usuario;
    }

    public static function ocultarEmail($email): string
    {
        $maskedEmail = '';
        $atIndex = strpos($email, '@');

        if ($atIndex !== false) {
            $username = substr($email, 0, $atIndex);
            $domain = substr($email, $atIndex + 1);

            $usernameLength = strlen($username);
            $maskedUsername = substr($username, 0, 4) . str_repeat('*', $usernameLength - 4);

            $domainParts = explode('.', $domain);
            $maskedDomain = '';
            foreach ($domainParts as $part) {
                $partLength = strlen($part);
                $maskedPart = ($partLength <= 3) ? $part : substr($part, 0, 1) . str_repeat('*', $partLength - 1);
                $maskedDomain .= $maskedPart . '.';
            }
            $maskedDomain = rtrim($maskedDomain, '.');

            $maskedEmail = $maskedUsername . '@' . $maskedDomain;
        }

        return $maskedEmail;
    }
}

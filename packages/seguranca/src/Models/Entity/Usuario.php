<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use GapPay\Seguranca\database\factories\UsuarioFactory;
use GapPay\Seguranca\Models\Facade\UsuarioDB;

/**
 * Class Usuario
 * @package App\Models\Entity
 *
 * @property int id
 * @property string nome
 * @property string email
 * @property string senha Evite usar esta variável use senha2
 * @property string login
 * @property string dt_cadastro
 * @property bool excluido
 * @property bool primeiro_acesso
 * @property string cpf
 * @property string nascimento
 * @property string remember_token
 * @property string unidade Campo usado livremente pelo usuário
 * @property string foto
 * @property int status Coluna deprecated. Use excluido ou em usuario_sistema na coluna status
 * @property int fk_usuario_correicao
 * @property string senha2
 * @property bool diretor
 * @property int fk_unidade
 * @property string mime_foto
 */
class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // protected $connection = 'conexao_seguranca';
    protected $table = 'usuario';
    public $timestamps = false;
    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'senha',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return UsuarioFactory
     */
    protected static function newFactory(): UsuarioFactory
    {
        return UsuarioFactory::new();
    }

    public bool $log = true;

    protected static function booted(): void
    {
        static::created(function (Usuario $o) {

            if (!isLogAtivo()) {
                return;
            }

            $usuarioAutenticado = Auth()->id();

            if (!$usuarioAutenticado && config('app.env')) {
                $usuarioAutenticado = 1;
            }

            UsuarioLog::create([
                'usuario_id' => $o->id,
                'responsavel' => $usuarioAutenticado,
                'sistema_id' => config('policia.codigo'),
                'depois' => json_encode($o->toArray()),
                'ip' => implode(', ', request()->ips())
            ]);
        });

        static::updated(function (Usuario $o) {
            if (!isLogAtivo()) {
                return;
            }

            $usuarioAutenticado = Auth()->id();

            if (!$usuarioAutenticado && config('app.env')) {
                $usuarioAutenticado = 1;
            }

            UsuarioLog::create([
                'usuario_id' => $o->id,
                'responsavel' => $usuarioAutenticado,
                'sistema_id' => config('policia.codigo'),
                'antes' => json_encode($o->getOriginal()),
                'depois' => json_encode($o->getChanges()),
                'ip' => implode(', ', request()->ips())
            ]);
        });

        static::deleted(function (Usuario $o) {
            if (!isLogAtivo()) {
                return;
            }

            $usuarioAutenticado = Auth()->id();
            if (!$usuarioAutenticado && config('app.env')) {
                $usuarioAutenticado = 1;
            }

            UsuarioLog::create([
                'usuario_id' => $o->id,
                'responsavel' => $usuarioAutenticado,
                'sistema_id' => config('policia.codigo'),
                'antes' => json_encode($o->getOriginal()),
                'ip' => implode(', ', request()->ips())
            ]);
        });
    }

    /**
     * @param Collection|null $perfis_id
     * @return bool
     */
    public function isRoot(?Collection $perfis_id = null): bool
    {
        if ($this->id === 1) {//usuário root
            return true;
        } else {
            if ($perfis_id) {//quando perfis_id é passado como parâmetro
                return $perfis_id->contains('id', 1);
            } else {//quando perfis_id não é passado como parâmetro
                $perfisID = UsuarioDB::perfisIDUsuarioLogado($this);
                return $perfisID->contains('id', 1);
            }
        }
    }

    public function cadastroIncompleto(): bool
    {
        return !$this->nascimento || !$this->cpf;
    }

    public function deveTrocarSenha(): bool
    {
        //é convertido para bool, pois o campo também pode ser null
        return !!$this->primeiro_acesso;
    }

    //=== Mutators da classe Usuário ===

    /**
     * Regras para email
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }

    /**
     * Regras para os campos senha
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function senha(): Attribute
    {
        return Attribute::set(function ($value) {
            return [
                'senha' => sha1($value), // Campo senha agora usa o hash padrão
                'senha2' => Hash::make($value),
            ];
        });
    }
    public function getAuthPassword()
    {
        return $this->senha2;
    }
}

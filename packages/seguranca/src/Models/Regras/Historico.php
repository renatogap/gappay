<?php

namespace GapPay\Seguranca\Models\Regras;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use GapPay\Seguranca\Models\Entity\SegHistorico;

class Historico
{
    static Historico $historico;
    private array $antes;
    private array $depois;

    /**
     * Historico constructor.
     */
    private function __construct()
    {
        $this->antes = [];
        $this->depois = [];
    }

    /**
     * Retorna uma instância de Histórico.
     * @return Historico
     */
    public static function getInstance(): Historico
    {
        if (!isset(self::$historico)) {
            $c = __CLASS__;
            self::$historico = new $c;
        }
        return self::$historico;
    }

    /**
     *
     * @param ModeloLog $log
     * @throws \Exception
     */
    public function insert(ModeloLog $log): void
    {
        $this->depois[] = $log;//__toString da classe será chamado automaticamente
        $this->persistirSeNaoHouverTransacao();
    }

    /**
     * @throws \Exception
     */
    public function update(ModeloLog $antes, ModeloLog $depois): void
    {
        $this->antes[] = $antes;
        $this->depois[] = $depois;
        $this->persistirSeNaoHouverTransacao();
    }

    /**
     * @throws \Exception
     */
    public function delete(ModeloLog $antes): void
    {
        $this->antes[] = $antes;
        $this->persistirSeNaoHouverTransacao();
    }

    /**
     * @throws \Exception
     */
    public function persistirSeNaoHouverTransacao(): void
    {
        if (!DB::transactionLevel()) {//Se não houver transação ativa, então gera um registro de log imediatamente no banco
            $this->criarLog();
        }
    }

    /**
     * @throws \Exception
     */
    public function criarLog(): void
    {
        if (empty($this->antes) && empty($this->depois)) {
            return;
        }

        //obtendo o id da url no banco
        if(config('app.env') === 'testing') {//durante testes TDD os logs não serão salvos
            $this->criarLogDeTeste();
            return;
        }

        $acao_id = AcaoSolicitada::id();

        if (!$acao_id) {
            throw new \Exception('Não foi possível criar log desta ação. Ação não cadastrada no banco');
        }

        $this->log(Auth::id(), request()->path(), $acao_id);
    }

    /**
     * @throws \Exception
     */
    public function criarLogDeTeste(): void
    {
        $acao_id = AcaoSolicitada::id() ?? 5;// 5 - /home, será usada para teste
        $usuario_id = Auth::id() ?? 1;// 1 - Root, será usado para teste
        $url = Route::current() ? Route::current()->uri() : '/home-de-teste';

        $this->log($usuario_id, $url, $acao_id);
    }

    /**
     * @throws \Exception
     */
    private function log(int $usuario_id, string $url, int $acao_id): void
    {
        try {
            SegHistorico::create([
                'usuario_id' => $usuario_id,
                'url' => $url,
                'acao_id' => $acao_id,
                'ip' => Request::ip(),
                'antes' => !empty($this->antes) ? $this->antes : null,
                'depois' => !empty($this->depois) ? $this->depois : null
            ]);

        } catch (\Exception $e) {
            throw new \Exception('Não foi possível criar log desta ação ' . $e->getMessage());
        }
    }

    /**
     * @param int $acao_id
     * @param string $url_completa
     * @return void
     * @throws \Exception
     */
    public function criarLogVisualizacao(int $acao_id, string $url_completa): void
    {
        try {
            SegHistorico::create([
                'usuario_id' => Auth::id(),
                'url' => $url_completa,
                'acao_id' => $acao_id,
                'ip' => Request::ip(),
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Não foi possível criar log desta ação ' . $e->getMessage());
        }

    }
}

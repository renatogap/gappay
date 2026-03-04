<?php

namespace GapPay\Seguranca\Models\Regras;

use Illuminate\Database\Eloquent\Model;

class ModeloLog
{
    public string $action;
    public string $table;
    public array $data;

    public function insert(Model $model): void
    {
        $this->action = 'I';
        $this->logPadrao($model);
    }

    public function update(Model $model, bool $original = true): void
    {
        $this->action = 'U';

        $this->table = $model->getTable();
        $this->data = $original ? $model->getOriginal() : $model->getChanges();

        /**
         * Numa alteração o usuário pode estar tentando atualizar uma imagem, que fica como tipo
         * "resource" que atrapalha o log, pois um resource não pode ser transformado em string para ser inserido no log.
         *
         */
        foreach ($this->data as $key => $d) {
            if (is_resource($d)) {
                unset($this->data[$key]);
            }
        }
    }

    public function delete(Model $model): void
    {
        $this->action = 'D';
        $this->logPadrao($model);
    }

    private function logPadrao(Model $model): void
    {
        $this->table = $model->getTable();

        $this->data = $model->getAttributes();

        foreach ($this->data as $key => $d) {
            if (is_resource($d)) {
                unset($this->data[$key]);
            }
        }
    }

    /**
     * Cria uma versão string desta classe no formato json
     * @return string
     */
    public function __toString(): string
    {
        return json_encode(get_object_vars($this));
    }
}

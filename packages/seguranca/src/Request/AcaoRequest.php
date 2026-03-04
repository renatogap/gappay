<?php

namespace GapPay\Seguranca\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use GapPay\Seguranca\Models\Entity\SegAcao;
use GapPay\Seguranca\Models\Form\AcaoForm;

class AcaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required',
            'nome_amigavel' => 'required_if:destaque,true',
            'dependencia' => 'array|nullable',
            'method' => 'nullable',
            'descricao' => 'nullable',
            'destaque' => 'nullable',
            'obrigatorio' => 'nullable',
            'grupo' => 'nullable',
            'log_acesso' => 'nullable',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (!$this->isEdicao()) {
                    $repetido = SegAcao::where('nome', $this->request->get('nome'))
                        ->where('method', $this->request->get('method'))
                        ->exists();

                    if ($repetido) {
                        $validator->errors()->add(
                            'nome',
                            'Rota já está cadastrada'
                        );
                    }
                }
            }
        ];
    }

    private function isEdicao(): bool
    {
        return !!$this->route('acao');
    }

    public function validatedForm(): AcaoForm
    {
        $dados = $this->validated();
        if ($this->isEdicao()) {
            $dados['id'] = $this->route('acao');
        }

        return AcaoForm::create($dados);
    }
}

<?php

namespace App\Http\Requests;

use App\Rules\CPFValidacao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Regras\UsuarioForm;

class UsuarioLocalRequest extends FormRequest
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
            // 'cpf' => ['required', new CPFValidacao()],
            'nome' => 'required',
            'email' => 'required|email',
            'senha' => 'required|min:6',
            // 'nascimento' => 'nullable|date|before:tomorrow',
            'perfil' => 'required',
            'cardapio' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'nascimento.before' => 'Data de nascimento não pode ser uma data no futuro',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {

            if ($validator->errors()->isEmpty()) {// testa se já houve erro de validação

                $edicao = $this->request->get('id');

                if (!$edicao) {//tela de cadastro

                    $usuario = Usuario::where('email', 'like', request('email'))->first();

                    if ($usuario) {

                        //incluí o "id" do usuário encontrado para que a regra seja de edição
                        request()->merge(['id' => $usuario->id]);
                        //                    $validator->errors()->add('email', 'Este email já está em uso por outro usuário.');
                    }
                }
            }
        });
    }

    public function validatedForm(string $id = null): UsuarioForm
    {
        $dados = $this->validated();
        if ($id) {
            $dados['id'] = $id;
        }

        return UsuarioForm::create($dados);
    }
}

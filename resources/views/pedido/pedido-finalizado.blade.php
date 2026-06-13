@extends('layouts.default')
@section('conteudo')
    <div class="text-center" style="padding: 8em 0 10em 0;">
        <div
            style="background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 12px; padding: 30px 20px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.08);">
            <div
                style="display: inline-flex; align-items: center; justify-content: center; width: 70px; height: 70px; background: #c8e6c9; border-radius: 50%; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.15);">
                <span class="material-icons" style="font-size: 40px; color: #2e7d32;">done_all</span>
            </div>
            <h4 style="color: #1b5e20; font-weight: bold; margin-bottom: 10px;">Pedido finalizado!</h4>
            <div style="color: #2e7d32; font-size: 16px; line-height: 1.6; font-weight: 500;">
                Saldo: R$
                {{ number_format(($params->cartaoCliente->valor_atual - $params->valorTotalPedido), 2, ',', '.') }}
            </div>
        </div>

        <div>
            <strong>Aluno(a):</strong> {{ $params->cartaoCliente->nome }}
        </div>

        <div class="mt-3">
            <a href="{{url('pedido/selecionar-aluno')}}" class="btn btn btn-parque"
                style="width: 100%; padding: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin: 0; border-radius: 8px; border: 1px solid #ccc;">
                <span class="material-icons" style="font-size: 20px;">shopping_cart</span>
                Fazer outro pedido
            </a>

            <a href="{{url('')}}" class="btn btn-light mt-3"
                style="width: 100%; padding: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin: 0; border-radius: 8px; border: 1px solid #ddd; background: #fafafa; color: #333;">
                <span class="material-icons" style="font-size: 20px;">home</span>
                Voltar para o Home
            </a>
        </div>




    </div>
@endsection
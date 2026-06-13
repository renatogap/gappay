@extends('layouts.default')

@section('conteudo')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h5 style="margin: 0; display: inline-flex; align-items: center; gap: 8px;">
            <span class="material-icons"
                style="color: #28a745; font-size: 28px; vertical-align: middle;">check_circle</span>
            <strong>Crédito Confirmado</strong>
        </h5>
        <a href="{{url('')}}" class="material-icons" style="font-size: 1.5em; color: #333; text-decoration: none;"
            title="Voltar">
            keyboard_backspace
        </a>
    </div>
    <hr style="margin-top: 0; margin-bottom: 25px;">

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 text-center">

            <!-- Success Message Card -->
            <div
                style="background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 12px; padding: 30px 20px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.08);">
                <div
                    style="display: inline-flex; align-items: center; justify-content: center; width: 70px; height: 70px; background: #c8e6c9; border-radius: 50%; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.15);">
                    <span class="material-icons" style="font-size: 40px; color: #2e7d32;">done_all</span>
                </div>
                <h4 style="color: #1b5e20; font-weight: bold; margin-bottom: 10px;">Recarga Efetuada!</h4>
                <div style="color: #2e7d32; font-size: 16px; line-height: 1.6; font-weight: 500;">
                    {!! session('sucesso') !!}
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: 12px; max-width: 400px; margin: 0 auto;">
                <a href="{{url('cartao-cliente/leitor')}}" class="btn btn-parque"
                    style="width: 100%; padding: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin: 0; border-radius: 8px;">
                    <span class="material-icons" style="font-size: 20px;">qr_code_scanner</span>
                    Fazer outra recarga
                </a>

                <a href="{{url('pedido/selecionar-aluno')}}" class="btn btn-secondary"
                    style="width: 100%; padding: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin: 0; border-radius: 8px; border: 1px solid #ccc;">
                    <span class="material-icons" style="font-size: 20px;">shopping_cart</span>
                    Fazer um pedido
                </a>

                <a href="{{url('')}}" class="btn btn-light"
                    style="width: 100%; padding: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin: 0; border-radius: 8px; border: 1px solid #ddd; background: #fafafa; color: #333;">
                    <span class="material-icons" style="font-size: 20px;">home</span>
                    Voltar para o Home
                </a>
            </div>

        </div>
    </div>

@endsection
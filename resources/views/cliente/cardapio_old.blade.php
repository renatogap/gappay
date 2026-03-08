@extends('layouts.default-cliente')

<style>
    a {text-decoration: none !important;}
    a:hover {background: #e1e1e1e1;}
</style>

@section('conteudo')
    <?php $locale = session('locale'); ?>
    <h5>
        <span class="material-icons icone">receipt_long</span>
        Cardápio

        <a href="#" class="material-icons float-right" onclick="history.go(-1)" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
        </a>
    </h5>
    <hr>
    @if (session('sucesso'))
        <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session('sucesso') !!}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session('error') !!}
        </div>
    @endif


    @if(count($myCardapio) > 0)
        <div class="list-group">
            @foreach($myCardapio as $tipo => $categorias)
                <h5>{{ $tipo }}</h5>
                
                @foreach($categorias as $categoria => $itens)
                    <a href="#" class="list-group-item bg-dark" style="cursor: default;">
                        <div style="text-align: center; color: #fff;">
                            <span style="font-size: 14px;"><strong>{{ $categoria }}</strong></span>
                        </div>
                    </a>

                    @foreach($itens as $item)
                        <a href="{{ url('cliente/cardapio/item/'.$item->id) }}" class="list-group-item" style="padding: 4px 3px 9px 6px;">
                            <div style="color: #666;">
                                <span style="float: right; color: #666; font-size: 13px; font-weight: bold;">
                                    R$ {{ $item->valor }}
                                </span>
                                <span style="font-size: 14px;"><strong>{{ $locale=='PT' ? $item->nome_item : $item->nome_item_en }}</strong></span>
                                <br>
                                
                                @if($item->detalhe_item)
                                    {{ $locale=='PT' ? $item->detalhe_item : $item->detalhe_item_en }}
                                    <br>
                                @endif
                            </div>
                        </a>

                    @endforeach
                @endforeach
                <br><br>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">Nenhum registro encontrado.</div>
    @endif

    <script>
            document.querySelector('.navbar').remove();
            document.getElementById('container-fluid').style = "margin-top: 1em";
        </script>

@endsection
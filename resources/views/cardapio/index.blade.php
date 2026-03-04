@extends('layouts.default')

<style>
   
</style>

@section('conteudo')
    <h5>
        Gerenciar Cardápio
        <a href="{{url('cardapio/create')}}" class="btn btn-primary btn-circulo btn-flutuante">
            <span class="material-icons icone" style="font-size: 2em;">receipt_long</span>
        </a>
        <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
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

            <?php $key = 1; ?>

            @foreach($myCardapio as $tipo => $categorias)

                <h4 onclick="detalhesCardapio(<?= $key ?>)" class="show mt-0 mb-0 text-center p-3 text-white bg-dark" style="font-size: 17px; font-weight: bold; border-radius: 5px; cursor: pointer;">
                    {{ $key }}. {{ $tipo }}
                </h4>

                <div id="detalhes_cardapio_<?= $key ?>">
                
                    @foreach($categorias as $categoria => $itens)
                        <a href="#" class="list-group-item bg-info" style="cursor: default;">
                            <div style="text-align: center; color: #666;">
                                <span style="font-size: 16px; color: #fff;"><strong>{{ $categoria }}</strong></span>
                            </div>
                        </a>

                        @foreach($itens as $item)
                            <div id="item_<?= $item->id ?>" style="cursor: pointer; margin: 0; padding: 0;" class="list-group-item">
                              <div style="width: 100%;">
                                <div 
                                    style="display: inline-block; color: #666; width: 93%; margin: 0; padding: 15px;"
                                    onclick="window.location='<?= url('cardapio/edit/'.$item->id) ?>'"
                                >
                                    <span style="float: right; color: #666; font-size: 13px; font-weight: bold;">
                                        R$ {{ $item->valor }}
                                    </span>
                                    <span style="font-size: 14px;">
                                        <span class="badge {{ $item->status == 1 ? 'badge-success' : 'badge-danger' }}" style="height: 8px; float: left; margin-left: -1.2em; margin-top: 5px;"> </span>
                                        <strong>{{ $item->id }} - {{ $item->nome_item }}</strong>
                                    </span>
                                    <br>
                                    
                                    @if($item->detalhe_item)
                                        {{ $item->detalhe_item }}
                                        <br>
                                    @endif
                                </div>

                                <!-- Somente perfil diretor enxerga o botao de excluir -->

                                <div style="display: inline-block; width: 5%; vertical-align: middle; margin: 0;">
                                    <span 
                                        id="btn_excluir_item_<?= $item->id ?>"
                                        class="material-icons" 
                                        style="font-size: 1.8em; color: darkred; float: right;"
                                        onclick="deleteItem(<?= $item->id ?>, '<?= $item->nome_item ?>')"
                                    >
                                        delete
                                    </span>

                                    <span id="load_item_<?= $item->id ?>" class="spinner-border text-success d-none" role="status"></span>
                                </div>

                              </div>
                            </div>

                        @endforeach

                    @endforeach

                </div>
                <br />

                <?php $key++; ?>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">Nenhum registro encontrado.</div>
    @endif

@endsection


@section('scripts')
<script>
    function detalhesCardapio(key) {
        if(!$('#detalhes_cardapio_'+key).hasClass('show')){
            $('#detalhes_cardapio_'+key).fadeIn();
            $('#detalhes_cardapio_'+key).addClass('show');
        }else {
            $('#detalhes_cardapio_'+key).fadeOut();
            $('#detalhes_cardapio_'+key).removeClass('show');
        }
    }

    function deleteItem(item_id, produto) {
        var btnExcluirItem = document.getElementById('btn_excluir_item_'+item_id);
        var loadItem = document.getElementById('load_item_'+item_id);

        if(confirm('Deseja realmente excluir o item "'+produto+'" ?')) {
            btnExcluirItem.classList.add('d-none');
            loadItem.classList.remove('d-none');

            fetch("<?= url('cardapio/item/delete') ?>/"+item_id)
            .then((r) => r.json())
            .then((r) => {
                if(r.message) {
                    document.getElementById('item_'+item_id).remove();
                    alert('O item "'+produto+'" foi excluído do cardápio.');
                } else {
                    alert(r.error);

                    loadItem.classList.add('d-none');
                    btnExcluirItem.classList.remove('d-none');
                }
            });
        }
    }
</script>
@endsection
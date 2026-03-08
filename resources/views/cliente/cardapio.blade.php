@extends('layouts.default')

<style>
    a {
        text-decoration: none !important;
    }

    a:hover {
        background: #e1e1e1e1;
    }

    .categoria-cardapio {
        background: <?= config('policia.background') ?>;
    }
</style>

@section('conteudo')
<h4>
    <span class="material-icons icone">receipt_long</span>
    Cardápio

    <a href="{{url('/cliente/home')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h4>
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


<div id="grid-cardapio">

    <div v-if="carregando" class="alert alert-warning">Carregando...</div>

    <div
        v-if="cardapioPDV.length > 0"
        v-for="categorias in cardapioPDV"
        class="list-group">

        <!-- <h4 class="mt-3">@{{ nomePDV }}</h4> -->


        <div class="input-group mb-3">
            <input
                type="text"
                class="form-control"
                placeholder="Pesquisar item do cardápio"
                aria-label="Pesquisar item do cardápio"
                aria-describedby="basic-addon2"
                v-model="filtro"
                @input="filtrarItensCardapio">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2">
                    <span class="material-icons" style="font-size: 20px; color: #666;">search</span>
                </span>
            </div>
        </div>

        <div class="mt-2 ">
            <div v-for="(itens, categoria) in categorias">

                <div class="list-group-item text-light text-center categoria-cardapio">
                    <strong>@{{ categoria }}</strong>
                </div>

                <div
                    v-for="item in itens"
                    :key="item.id"
                    class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">

                        <!-- Dados do item -->
                        <div>
                            <strong>@{{ item.nome_item }}</strong><br>
                            <span class="text-muted">
                                R$ @{{ item.valor }}
                            </span>
                            <small v-if="item.detalhe_item">
                                @{{ item.detalhe_item }}
                            </small><br>
                        </div>

                        <!-- Controle de quantidade -->
                        <div class="input-group" style="width: 130px;">
                            <button
                                class="btn btn-danger"
                                @click="removerItem(item)">
                                −
                            </button>

                            <input
                                type="number"
                                min="0"
                                class="form-control text-center"
                                v-model.number="quantidades[item.id]" />
                            <button
                                class="btn btn-success"
                                @click="adicionarItem(item)">
                                +
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br>

    <a v-if="Object.keys(itensSelecionados).length > 0" href="{{ url('cliente/confirmar-pedido') }}" class="btn btn-primary btn-lg pull-right btn-circulo btn-flutuante" title="Finalizar pedido">
        <i class="material-icons">local_grocery_store</i>
        <div style="margin-top: 0; font-size: 20px; font-weight: bold;">@{{ Object.keys(itensSelecionados).length }}</div>
    </a>

    <div v-if="!carregando && cardapioPDV.length==0" class="alert alert-info">Nenhum registro encontrado.</div>
</div>
</div>


<script src="{{ asset('js/vue.js') }}"></script>
<script>
    const {
        createApp,
        ref,
        onMounted
    } = Vue;

    createApp({
        setup() {
            const carregando = ref(false);
            const itensCardapioFiltro = ref([]);
            const nomePDV = ref('');
            const cardapioPDV = ref([]);
            const filtro = ref('');
            const itensSelecionados = ref([]);
            const quantidades = ref({});

            const carregarCardapio = async (id_tipo_cardapio) => {
                cardapioPDV.value = [];
                carregando.value = true;

                let URL = '{{ url("cliente/cardapio/show") }}/' + id_tipo_cardapio;

                try {
                    const response = await fetch(URL);

                    if (!response.ok) throw new Error('Ocorreu um erro ao tentar carregar o cardápio.');

                    const cardapio = await response.json();
                    nomePDV.value = Object.keys(cardapio)[0]; // Pega o nome do PDV
                    cardapioPDV.value = Object.values(cardapio); // Pega os itens do cardápio

                    carregando.value = false; // Finaliza o carregamento

                } catch (error) {
                    carregando.value = false;
                    console.error('Um erro ocorreu ao carregar o cardápio:', error);
                }
            }

            const filtrarItensCardapio = () => {

                // Se a lista do cardápio estiver vazia, inicializa com a lista completa
                if (itensCardapioFiltro.value.length == 0) {
                    itensCardapioFiltro.value = cardapioPDV.value;
                }

                const filtroMinusculo = retirarAcentosCedilha(filtro.value).toLowerCase();

                const cardapioFiltrado = {};

                for (const categoria in itensCardapioFiltro.value[0]) {

                    const itensFiltrados = itensCardapioFiltro.value[0][categoria].filter(item =>
                        retirarAcentosCedilha(item.nome_item).toLowerCase().includes(filtroMinusculo) ||
                        (item.detalhe_item && retirarAcentosCedilha(item.detalhe_item).toLowerCase().includes(filtroMinusculo)));

                    if (itensFiltrados.length > 0) {
                        cardapioFiltrado[categoria] = itensFiltrados;
                    }
                }

                cardapioPDV.value = [cardapioFiltrado];
            }

            const retirarAcentosCedilha = (str) => {
                return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/ç/g, "c").replace(/Ç/g, "C");
            }

            const adicionarItem = (item) => {
                // if (!quantidades.value[item.id] || quantidades.value[item.id] == 0) {
                //     item.quantidade = 0;
                // }
                quantidades.value[item.id]++;

                let itemSelecionadoParams = {
                    _token: '{{ csrf_token() }}',
                    id_cardapio: item.id,
                    quantidade: quantidades.value[item.id],
                    id_tipo_cardapio: <?= $id_tipo_cardapio ?>,
                    valorCardapio: item.valor,
                    unidade: item.unidade ?? 1
                }

                //atualizando a quantidade de itens selecionados do carrinho
                itensSelecionados.value[item.id] = itemSelecionadoParams;


                fetch('{{ url("cliente/cardapio/add-pedido-cliente") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(itemSelecionadoParams)
                }).then(response => {
                    if (!response.ok) alert('Erro ao adicionar item ao pedido.');

                    return response.json();
                }).then(data => {
                    //console.log('Item adicionado com sucesso:', data);
                }).catch(error => {
                    alert('Erro:', error);
                });
            }

            const removerItem = (item) => {
                if (quantidades.value[item.id] <= 0) {
                    quantidades.value[item.id] = 0;
                    return;
                }
                quantidades.value[item.id]--;

                //console.log(itensSelecionados.value[item.id]);

                itensSelecionados.value[item.id].quantidade--;

                if (itensSelecionados.value[item.id].quantidade == 0) {
                    //remover o item do array se estiver com quantidade 0
                    delete itensSelecionados.value[item.id];
                }

                let itemSelecionadoParams = {
                    _token: '{{ csrf_token() }}',
                    itemId: item.id,
                    quantidade: quantidades.value[item.id]
                }

                //remover o item da sessão
                fetch('{{ url("cliente/cardapio/remove-item-pedido-cliente") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(itemSelecionadoParams)
                }).then(response => {
                    if (!response.ok) alert('Erro ao adicionar item ao pedido.');

                    return response.json();
                }).then(data => {
                    //console.log('Item adicionado com sucesso:', data);
                }).catch(error => {
                    alert('Erro:', error);
                });
            }

            onMounted(async () => {
                // Chama a função para carregar o cardápio quando a página for carregada
                await carregarCardapio(<?= $id_tipo_cardapio ?>);

                // Pega os itens selecionados da session vinda do backend
                <?php if (session()->has('pedido')): ?>
                    itensSelecionados.value = @json(session('pedido'))
                <?php endif; ?>

                //inicializando a quantidade dos inputs
                cardapioPDV.value.forEach(categorias => {
                    Object.keys(categorias).forEach(itens => {
                        categorias[itens].forEach(item => {
                            quantidades.value[item.id] = itensSelecionados.value[item.id] ? itensSelecionados.value[item.id].quantidade : 0;
                        })
                    })
                })
            });

            return {
                nomePDV,
                filtro,
                cardapioPDV,
                carregando,
                carregarCardapio,
                filtrarItensCardapio,
                itensSelecionados,
                quantidades,
                adicionarItem,
                removerItem
            }
        }
    }).mount('#grid-cardapio');
</script>

@endsection
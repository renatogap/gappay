@extends('layouts.default')
@section('conteudo')
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

<h5>
    Dashboard
    <a href="{{url('cliente/list')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>  
</h5>
<hr>

<div id="app">
    <section>
        <b style="font-size: 16px;">Vendas realizadas por hora</b>

        <div class="row mb-3 mt-3">
            <div class="col-6">
                <input 
                    type="date" 
                    v-model="dataPedidosHora" 
                    class="form-control"
                    @change="loadTotalPedidosPorHora"
                />
            </div>
            <div class="col-6">
                <h5
                    v-if="!carregando"
                    class="float-right"
                >
                    Vendas: @{{ totalVendasPorHora }}
                </h5>
            </div>
        </div>

        <div v-if="pedidos.length == 0" class="alert alert-info">
            <div v-if="carregando" class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>

            @{{ textoAlertPedidosPorHora }}
        </div>

    
        <v-simple-table v-if="pedidos.length > 0" dense>
            <template v-slot:default>
                <tbody>
                    <tr 
                        v-for="pedido in pedidos"
                        :key="pedido.hora"
                    >
                        <td width="5%" style="vertical-align: top;">
                            <b>@{{pedido.hora}}h</b>
                        </td>
                        <td>
                            <div class="progress"style="margin-bottom: 10px;">
                                <div
                                    class="progress-bar" 
                                    role="progressbar" 
                                    :style="tamanhoProgressBar(pedido.total_pedidos)"
                                    :aria-valuenow="percentual(pedido.total_pedidos)" 
                                    :aria-valuemin="0" 
                                    :aria-valuemax="totalVendasPorHora"
                                >
                                    <b>@{{ pedido.total_pedidos }}</b>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>

    </section>        
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script>
    
    new Vue({
      el: "#app",
      vuetify: new Vuetify(),
      data: {
        dataPedidosHora: '<?= date('Y-m-d') ?>',
        pedidos: [],
        totalVendasPorHora: 0,
        carregando: false
      },

      created() {

        this.loadTotalPedidosPorHora();

      },

      methods: {

        loadTotalPedidosPorHora() {
            this.pedidos = [];

            if(this.dataPedidosHora) {
                this.carregando = true;

                axios.get(BASE_URL+'dashboard/pedidos-por-hora/'+this.dataPedidosHora)
                    .then(response => {
                        this.pedidos = response.data;
                        this.carregando = false;
                    })
            }
        },

        percentual(valor) {
            return ((valor * 100) / this.totalVendasPorHora);
        },

        tamanhoProgressBar(valor) {
            return 'width: ' + ((valor * 100) / this.totalVendasPorHora) + '%';
        }

      },

      computed: {
        textoAlertPedidosPorHora() {
            return this.carregando ? 'Aguarde, carregando...' : 'Nenhum registro encontrado nesta data';
        }
      },

      watch: {
        pedidos() {
            const initialValue = 0;
            this.totalVendasPorHora = this.pedidos.reduce((accumulator, currentValue) => accumulator + currentValue.total_pedidos, initialValue);
        }
      },
  })

</script>
@endsection
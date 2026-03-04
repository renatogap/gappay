@extends('layouts.default')
@section('conteudo')
<h5>
    Dashboard
    <a href="{{url('cliente/list')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>  
</h5>
<hr>

<div id="app">
    <div class="row mb-3 mt-3">
        <div class="col-6">
            <input 
                type="date" 
                v-model="dataFiltro" 
                class="form-control"
                @change="loadDashboards"
            />
        </div>
    </div>
        
    <section>
        <div class="row mb-3 mt-3">
            <div class="col-6">
                <h5>Vendas por hora</h5>
            </div>
            <div class="col-6">
                <h5
                    v-if="!carregando"
                    class="float-right"
                >
                    Total: @{{ totalVendas }}
                </h5>
            </div>
        </div>

        <div v-if="pedidos.length == 0" class="alert alert-info">
            <div v-if="carregando" class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>

            @{{ textoAlertPedidosPorHora }}
        </div>
    
        <table v-if="pedidos.length > 0" width="100%">
            <tr v-for="pedido in pedidos">
                <td width="5%" style="vertical-align: top;">
                    <b>@{{pedido.hora}}h</b>
                </td>
                <td>
                    <div class="progress" style="height: 25px; margin-bottom: 10px;">
                        <div
                            class="progress-bar" 
                            role="progressbar" 
                            :style="tamanhoProgressBar(pedido.total_pedidos)"
                            :aria-valuenow="percentual(pedido.total_pedidos)" 
                            :aria-valuemin="0" 
                            :aria-valuemax="100"
                        >
                            <b>@{{ pedido.total_pedidos }}</b>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </section>   
    
    <hr />

    <section>
        <div class="row mb-3 mt-3">
            <div class="col-6">
                <h5>Recargas por hora</h5>
            </div>
            <div class="col-6">
                <h5
                    v-if="!carregando"
                    class="float-right"
                >
                    Total: @{{ totalRecargas }}
                </h5>
            </div>
        </div>

        <div v-if="recargas.length == 0" class="alert alert-info">
            <div v-if="carregando" class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>

            @{{ textoAlertPedidosPorHora }}
        </div>
    
        <table v-if="recargas.length > 0" width="100%">
            <tr v-for="recarga in recargas">
                <td width="5%" style="vertical-align: top;">
                    <b>@{{recarga.hora}}h</b>
                </td>
                <td>
                    <div class="progress" style="height: 25px; margin-bottom: 10px;">
                        <div
                            class="progress-bar bg-info" 
                            role="progressbar" 
                            :style="tamanhoProgressBarCredito(recarga.total_recargas)"
                            :aria-valuenow="percentualCredito(recarga.total_recargas)" 
                            :aria-valuemin="0" 
                            :aria-valuemax="100"
                        >
                            <b>@{{ recarga.total_recargas }}</b>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </section>
    
    <hr />

    <section>
        <div class="row mb-3 mt-3">
            <div class="col-6">
                <h5>Recargas por mês</h5>
            </div>
            <div class="col-6">
                <h5
                    v-if="!carregando"
                    class="float-right"
                >
                    Total: @{{ totalRecargasPorMes }}
                </h5>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <input type="text" class="form-control" v-model="anoFiltro" placeholder="2023">
            </div>
        </div>

        <div v-if="recargasNoMes.length == 0" class="alert alert-info">
            <div v-if="carregando" class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>

            @{{ textoAlertPedidosPorHora }}
        </div>
    
        <table v-if="recargasNoMes.length > 0" width="100%">
            <tr v-for="recargaMes in recargasNoMes">
                <td width="5%" style="vertical-align: top;">
                    <b>@{{recargaMes.mes}}</b>
                </td>
                <td>
                    <div class="progress" style="height: 25px; margin-bottom: 10px;">
                        <div
                            class="progress-bar bg-success" 
                            role="progressbar" 
                            :style="tamanhoProgressBarRecargaNoMes(recargaMes.total_recargas)"
                            :aria-valuenow="percentualRecargaNoMes(recargaMes.total_recargas)" 
                            :aria-valuemin="0" 
                            :aria-valuemax="100"
                        >
                            <b>R$ @{{ recargaMes.total_recargas }}</b>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </section>
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script> -->
<script>
    
    new Vue({
        el: "#app",
        //vuetify: new Vuetify(),
        data: {
            dataFiltro: '<?= date('Y-m-d') ?>',
            anoFiltro: '<?= date('Y') ?>',
            pedidos: [],
            recargas: [],
            recargasNoMes: [],
            totalVendas: 0,
            totalRecargas: 0,
            totalRecargasPorMes: 0,
            maiorTotalVendasPorHora: 0,
            maiorTotalRecargaPorHora: 0,
            maiorTotalRecargaPorMes: 0,
            carregando: false
        },

        created() {

            this.loadDashboards();

        },

        methods: {

            loadDashboards() {
                this.loadTotalPedidosPorHora();

                this.loadTotalRecargasPorHora();

                this.loadTotalRecargasPorMes();
            },

            loadTotalRecargasPorHora() {
                this.recargas = [];

                if(this.dataFiltro) {
                    this.carregando = true;

                    axios.get(BASE_URL+'dashboard/recargas-por-hora/'+this.dataFiltro)
                        .then(response => {
                            this.recargas = response.data.recargas;
                            this.maiorTotalRecargaPorHora = response.data.maior;
                            this.carregando = false;
                        })
                }
            },

            loadTotalRecargasPorMes() {
                this.recargasNoMes = [];

                if(this.anoFiltro) {
                    this.carregando = true;

                    axios.get(BASE_URL+'dashboard/recargas-por-mes/'+this.anoFiltro)
                        .then(response => {
                            this.recargasNoMes = response.data.recargas;
                            this.maiorTotalRecargaPorMes = response.data.maior;
                            this.totalRecargasPorMes = response.data.total;
                            this.carregando = false;
                        })
                }
            },

            loadTotalPedidosPorHora() {
                this.pedidos = [];

                if(this.dataFiltro) {
                    this.carregando = true;

                    axios.get(BASE_URL+'dashboard/pedidos-por-hora/'+this.dataFiltro)
                        .then(response => {
                            this.pedidos = response.data.pedidos;
                            this.maiorTotalVendasPorHora = response.data.maior;
                            this.carregando = false;
                        })
                }
            },

            percentual(valor) {
                return ((valor * 100) / this.maiorTotalVendasPorHora);
            },

            tamanhoProgressBar(valor) {
                return 'width: ' + ((valor * 100) / this.maiorTotalVendasPorHora) + '%';
            },

            percentualCredito(valor) {
                return ((valor * 100) / this.maiorTotalRecargaPorHora);
            },

            tamanhoProgressBarCredito(valor) {
                return 'width: ' + ((valor * 100) / this.maiorTotalRecargaPorHora) + '%';
            },

            percentualRecargaNoMes(valor) {
                return ((valor * 100) / this.maiorTotalRecargaPorMes);
            },

            tamanhoProgressBarRecargaNoMes(valor) {
                return 'width: ' + ((valor * 100) / this.maiorTotalRecargaPorMes) + '%';
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
                this.totalVendas = this.pedidos.reduce((accumulator, currentValue) => accumulator + currentValue.total_pedidos, initialValue);
            },

            recargas() {
                const initialValue = 0;
                this.totalRecargas = this.recargas.reduce((accumulator, currentValue) => accumulator + currentValue.total_recargas, initialValue);
            }
        }
    });


</script>
@endsection
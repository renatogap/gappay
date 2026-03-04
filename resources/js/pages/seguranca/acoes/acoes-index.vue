<script setup lang="ts">
import PcPesquisa from '@/components/PcPesquisa.vue'
import { useDisplay } from 'vuetify'
import {onMounted, reactive, ref} from 'vue'
import { BotoesTabela, ItensAcoes } from '@/types/App'
import { gerarUrl, possuiPermissao } from '@/helpers/seguranca'
import api from '@/api'
import { useTabelaStore } from '@/stores/tabela'
import PcBotaoTabela from '@/components/PcBotaoTabela.vue'
import pcAlert from '@/components/PcAlert.vue'
import {AxiosResponse} from "axios";

const headers: any = [
  { title: 'ID', value: 'id' },
  { title: 'Tipo', value: 'tipo', align: 'center' },
  { title: 'Método', value: 'method' },
  { title: 'Nome', value: 'nome' },
  { title: 'Descrição', value: 'descricao' },
  { title: '#Ações', value: 'acoes', align: 'center' },
]

interface Item {
  id: number;
  tipo: string;
  method: string;
  nome: string;
  descricao: string;
  acoes: string;
}
const items = ref<Item[]>([])
const dialogExcluir = ref(false)
const storeTabela = useTabelaStore()

interface GridResponse {
  id: number,
  nome: string,
  method: string,
  descricao: string,
}

const ocupado = ref(false)
const menuTabela: BotoesTabela = {
  // visualizar: {
  //   nome: 'Visualizar',
  //   link: `/acao/:id`,
  // },
  editar: {
    icone: 'mdi-pencil',
    nome: 'Editar',
    link: `/seguranca/acoes/:id/edit`,
  },
  excluir: {
    nome: 'Excluir',
    acao: (item: ItensAcoes) => excluir(item),
  },
}
const tela = reactive({
  destaque: [
    { title: 'Todas', value: false },
    { title: 'Destaque (somente telas)', value: true },
  ],
  item: {},
  snackbar: false,
  mensagem: '',
  corMensagem: 'error',
})

const form = reactive({
  nome: '',
  method: '',
  descricao: '',
  destaque: false,
  grupo: '',
  obrigatorio: false,
  log: false,
})

// const isBackend = (url: GridResponse): boolean => {
//   return url.rota_front
//   // return url.startsWith('api/') || url.startsWith('/api/')
// }

const pesquisar = () => {
  ocupado.value = true
  api.get('/acao/grid', {params: form})
      .then((response: AxiosResponse<Item[]>) => {
        items.value = response.data
      })
      .finally(() => {
        ocupado.value = false
      })
}

// === Ações de Exclusão ===
const excluir = (item: ItensAcoes) => {
  storeTabela.linhaSelecionada = item
  dialogExcluir.value = true
}

const confirmarExclusao = () => {
  api.delete(`/acao/${storeTabela.linhaSelecionada.id}`)
      .then(() => {
        pesquisar()
      })
      .catch(() => {
        alert('Não foi possível excluir usuário. Tente novamente.')
      })

  dialogExcluir.value = false
}

// === Ações na Tabela ===
const mobile = useDisplay().smAndDown
const menuAtivo = ref(false)

const abrirMenuTabela = (item: Item) => {
  storeTabela.linhaSelecionada = item
  menuAtivo.value = mobile.value ? !menuAtivo.value : false
}

onMounted(() => {
  pesquisar()
})
</script>

<template>
  <v-container>
    <pc-alert
      v-model="dialogExcluir"
      @close="dialogExcluir = false"
    >
      Remover a ação {{ storeTabela.linhaSelecionada.nome }}?
      <template #botoes>
        <v-btn
          @click="confirmarExclusao"
        >
          Sim
        </v-btn>
        <v-btn
          @click="dialogExcluir = false"
        >
          Não
        </v-btn>
      </template>
    </pc-alert>

    <pc-pesquisa @ao-enviar="pesquisar">
      <template #titulo>
        Pesquisar Ações
      </template>

      <v-row>
        <v-col>
          <v-text-field
            v-model="form.nome"
            label="Nome"
          />
        </v-col>
        <v-col>
          <v-select
            v-model="form.destaque"
            :items="tela.destaque"
            label="Tipo de Ação"
          />
        </v-col>
      </v-row>

      <template #botoesFormulario>
        <v-btn
          color="primary"
          :loading="ocupado"
          type="submit"
        >
          Pesquisar
        </v-btn>
      </template>
    </pc-pesquisa>

    <v-row class="mb-0">
      <v-col class="d-flex flex-row-reverse">
        <v-btn
          class="text-none"
          color="primary"
          prepend-icon="mdi-plus"
          :size="mobile ? 'large' : 'default'"
          to="acoes/create"
        >
          Novo
        </v-btn>
      </v-col>
    </v-row>

    <v-data-table
      class="elevation-1 rounded-lg"
      density="default"
      :headers="headers"
      hover
      :items="items"
      :loading="ocupado"
      :mobile="mobile"
    >
      <template #[`item.tipo`]="{ item }: {item: GridResponse}">
        <v-icon
          :color=" !item.nome.startsWith('/') ? 'blue-darken-1' : 'success'"
          size="large"
        >
          mdi-{{ !item.nome.startsWith('/') ? 'language-php' : 'language-html5' }}
        </v-icon>
      </template>
      <template #[`item.acoes`]="{ item }">
        <v-btn-group>
          <pc-botao-tabela
            v-if="menuTabela.outros"
            hover-color="primary"
            icon="mdi-dots-horizontal"
            :items="menuTabela.outros"
            title="Mais opções"
            @click="abrirMenuTabela(item)"
          />

          <pc-botao-tabela
            v-if="menuTabela.visualizar && possuiPermissao(menuTabela.visualizar?.link)"
            hover-color="primary"
            :icon="menuTabela.visualizar?.icone || 'mdi-eye'"
            :show="!mobile"
            :title="menuTabela.visualizar?.nome || 'Visualizar'"
            :to="`/acoes/${item.id}`"
          />
          <pc-botao-tabela
            v-if="menuTabela.editar && possuiPermissao(menuTabela.editar?.link)"
            hover-color="orange-lighten-1"
            :icon="menuTabela.editar?.icone || 'mdi-pencil'"
            :show="!mobile"
            :title="menuTabela.editar?.nome || 'Editar'"
            :to="gerarUrl(menuTabela.editar.link, item.id)"
          />
          <pc-botao-tabela
            v-if="menuTabela.excluir && possuiPermissao(`api/acao/{acao}`)"
            hover-color="red-lighten-1"
            :icon="menuTabela.excluir?.icone || 'mdi-delete'"
            :show="!mobile"
            :title="menuTabela.excluir?.nome || 'Excluir'"
            @click="excluir(item)"
          />
        </v-btn-group>
      </template>
    </v-data-table>
  </v-container>
</template>

<style scoped>

</style>

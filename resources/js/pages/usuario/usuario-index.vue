<script lang="ts" setup>
import api from '@/api'
import PcPesquisa from '@/components/PcPesquisa.vue'
import {computed, onMounted, reactive, ref} from 'vue'
import {useDisplay} from 'vuetify'
import {gerarUrl, possuiPermissao} from '@/helpers/seguranca'
import {BotoesTabela, Header, ItensUsuario} from '@/types/App'
import {useTabelaStore} from '@/stores/tabela'
import PcSnackbar from "@/components/PcSnackbar.vue";
import PcAlert from "@/components/PcAlert.vue";
import PcBotaoTabela from "@/components/PcBotaoTabela.vue";
import MenuTabelaCelular from "@/components/MenuTabelaCelular.vue";
import {abrirMenuTabela, excluir} from "@/helpers/menuTabela";
import {useRoute} from "vue-router";
import {AxiosResponse} from "axios";

const items = ref([])
const storeTabela = useTabelaStore()
const dialogExcluir = computed(() => storeTabela.acao === 'excluir')
const route = useRoute()
const tela = reactive({
  perfis: [],
  situacoes: [
    {title: 'Todas', value: 'todas'},
    {title: 'Ativo', value: 'ativo'},
    {title: 'Inativo', value: 'inativo'},
  ],
  item: {} as ItensUsuario,
  mensagem: '',
  corMensagem: 'error',
  snackbar: false, // exibir snackbar
})

const ocupado = ref(false)
const form = reactive({
  nome: '',
  email: '',
  perfil: [],
  situacao: 'todas',
})

const headers: Header[] = [
  {title: 'Id', value: 'id', key: 'id'},
  {title: 'Nome', value: 'nome', key: 'nome'},
  {title: 'E-mail', value: 'email', key: 'email'},
  {title: 'Perfil', value: 'perfil', key: 'perfil'},
  {title: 'Situação', value: 'ativo', key: 'ativo'},
  {title: '#Ações', value: 'acoes', key: 'acoes', align: 'center'},
]

const menuTabela: BotoesTabela = {
  // visualizar: {
  //   link: `/usuario/${tela.item.id}`,
  // },
  editar: {
    link: `/admin/usuario/:usuario/edit`,
  },
  excluir: {
    acao: (item: ItensUsuario) => excluir(item),
  },
  outros: [
    {
      icone: 'mdi-account-reactivate',
      nome: 'Reativar Usuário',
      acao: () => modificarSituacao(true),
    },
    {
      icone: 'mdi-account-off',
      nome: 'Desativar Usuário',
      acao: () => modificarSituacao(false),
    },
  ],
}

const confirmarExclusao = () => {
  api.delete(`/admin/usuario/${storeTabela.linhaSelecionada.id}`)
      .then(() => {
        pesquisar()
      })
      .catch(() => {
        alert('Não foi possível excluir usuário. Tente novamente.')
      })

  storeTabela.acao = ''
}

const pesquisar = () => {
  ocupado.value = true
  api.get('/admin/usuario/grid', {params: form})
      .then((r:AxiosResponse) => {
        items.value = r.data
      })
      .finally(() => {
        ocupado.value = false
      })
}

//= == Ações na Tabela ===
const mobile = useDisplay().smAndDown
const menuAtivo = ref(false)

const modificarSituacao = (ativo: boolean) => {
  const item: ItensUsuario = storeTabela.linhaSelecionada

  api.put(`/admin/usuario/modificar-situacao/${item.id}/${ativo ? 1 : 0}`)
      .then((response: AxiosResponse) => {
        tela.corMensagem = 'success'
        tela.mensagem = response.data.message
        tela.snackbar = true
        pesquisar()
      })
      .catch(() => {
        tela.corMensagem = 'error'
        tela.mensagem = 'Não foi possível atualizar usuário. Tente novamente.'
        tela.snackbar = true
      })
}
onMounted(() => {
  api.get('/admin/usuario')
      .then((r:AxiosResponse) => {
        tela.perfis = r.data.perfis
      })

  if (route.query?.perfil) {
    // form.perfil.// = [Number(route.query.perfil)]
  }
  pesquisar()
})
</script>

<template>
  <v-container>
    <pc-snackbar
      v-model="tela.snackbar"
      :cor="tela.corMensagem"
      :mensagem="tela.mensagem"
      @update:ativar="tela.snackbar = $event"
    />
    <pc-alert
      v-model="dialogExcluir"
      @close="storeTabela.limparAcao"
    >
      Remover o acesso do usuário {{ storeTabela.linhaSelecionada.nome }} ?
      <template #botoes>
        <v-btn
          @click="confirmarExclusao"
        >
          Sim
        </v-btn>
        <v-btn
          @click="storeTabela.limparAcao"
        >
          Não
        </v-btn>
      </template>
    </pc-alert>

    <pc-pesquisa @ao-enviar="pesquisar">
      <template #titulo>
        Pesquisar Usuários
      </template>
      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-text-field
            v-model="form.nome"
            density="comfortable"
            label="Nome"
          />
        </v-col>
        <v-col
          cols="12"
          md="6"
        >
          <v-text-field
            v-model="form.email"
            density="comfortable"
            label="E-mail"
            type="email"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-autocomplete
            v-model="form.perfil"
            :items="tela.perfis"
            chips
            clearable
            density="comfortable"
            item-title="text"
            label="Perfil"
            multiple
            autocomplete="off"
          />
        </v-col>
        <v-col
          cols="12"
          md="6"
        >
          <v-select
            v-model="form.situacao"
            :items="tela.situacoes"
            density="comfortable"
            label="Situação"
          />
        </v-col>
      </v-row>

      <template #botoesFormulario>
        <v-btn
          :loading="ocupado"
          color="primary"
          type="submit"
        >
          Pesquisar
        </v-btn>
      </template>
    </pc-pesquisa>

    <v-row class="mb-0">
      <v-col class="d-flex flex-row-reverse">
        <v-btn
          :size="mobile ? 'large' : 'default'"
          class="text-none"
          color="primary"
          prepend-icon="mdi-plus"
          to="/admin/usuario/create"
        >
          Novo
        </v-btn>
      </v-col>
    </v-row>

    <v-data-table
      :headers="headers"
      :items="items"
      :loading="ocupado"
      :mobile="mobile"
      class="elevation-1 rounded-lg"
      density="default"
      hover
    >
      <template #[`item.ativo`]="{ item }">
        <v-chip
          :color="(item as ItensUsuario).ativo ? 'success' : 'error'"
          text-color="white"
        >
          {{ (item as ItensUsuario).ativo ? 'Ativo' : 'Inativo' }}
        </v-chip>
      </template>

      <template #[`item.acoes`]="{ item }: {item: ItensUsuario}">
        <v-btn-group>
          <pc-botao-tabela
            v-if="menuTabela.outros"
            :items="menuTabela.outros"
            hover-color="primary"
            icon="mdi-dots-horizontal"
            title="Mais opções"
            @click="abrirMenuTabela(item); menuAtivo = mobile"
          />

          <pc-botao-tabela
            v-if="menuTabela.visualizar && possuiPermissao(menuTabela.visualizar?.link)"
            :icon="menuTabela.visualizar?.icone || 'mdi-eye'"
            :show="!mobile"
            :title="menuTabela.visualizar?.nome || 'Visualizar'"
            :to="`/admin/usuario/${(item as ItensUsuario).id}`"
            hover-color="primary"
          />
          <pc-botao-tabela
            v-if="menuTabela.editar && possuiPermissao(menuTabela.editar?.link)"
            :icon="menuTabela.editar?.icone || 'mdi-pencil'"
            :show="!mobile"
            :title="menuTabela.editar?.nome || 'Editar'"
            :to="gerarUrl(menuTabela.editar.link, item.id)"
            hover-color="orange-lighten-1"
          />
          <pc-botao-tabela
            v-if="menuTabela.excluir && possuiPermissao(`api/admin/usuario/{usuario}`)"
            :icon="menuTabela.excluir?.icone || 'mdi-delete'"
            :show="!mobile"
            :title="menuTabela.excluir?.nome || 'Excluir'"
            hover-color="red-lighten-1"
            @click="excluir(item)"
          />
        </v-btn-group>
      </template>
    </v-data-table>

    <menu-tabela-celular
      v-model="menuAtivo"
      :botoes-tabela="menuTabela"
      @update:menu-ativo="menuAtivo = $event"
    />
  </v-container>
</template>

<style scoped>

</style>

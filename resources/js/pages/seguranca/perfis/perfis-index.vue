<script lang="ts" setup>
import PcPesquisa from "@/components/PcPesquisa.vue";
import {onMounted, reactive, ref} from "vue";
import api from "@/api";
import {gerarUrl, possuiPermissao} from "@/helpers/seguranca";
import PcBotaoTabela from "@/components/PcBotaoTabela.vue";
import {useDisplay} from "vuetify/framework";
import {BotoesTabela, Header} from "@/types/App";
import {abrirMenuTabela} from "@/helpers/menuTabela";

interface Item {
  id: number;
  nome: string;
  total_usuarios: number;
  acoes: string;
}
const form = reactive({
  nome: '',
})
const ocupado = ref(false)
const items = ref<Item[]>([])


// === Ações na Tabela ===
const menuTabela: BotoesTabela = {
  // visualizar: {
  //   nome: 'Visualizar',
  //   link: `/acao/:id`,
  // },
  editar: {
    icone: 'mdi-pencil',
    nome: 'Editar',
    link: `/admin/perfil/:id/edit`,
  },
  excluir: {
    nome: 'Excluir',
    acao: (item: Item) => excluir(item),
  },
}
const mobile = useDisplay().smAndDown
const headers: Header[] = [
  {title: 'ID', value: 'id', key: 'id'},
  {title: 'Nome', value: 'nome', key: 'nome'},
  {title: 'Total de usuários', value: 'total_usuarios', key: 'total_usuarios'},
  {title: '#Ações', value: 'acoes', key: 'acoes', align: 'center'},
]

const pesquisar = () => {
  ocupado.value = true
  api.get('/perfil/grid', {params: form})
      .then((r) => {
        items.value = r.data
      })
      .finally(() => {
        ocupado.value = false
      })
}

const excluir = (item: Item) => {
  if (!confirm(`Deseja realmente excluir o perfil "${item.nome}"?`)) {
    return
  }
  ocupado.value = true
  api.delete(`/perfil/${item.id}`)
      .then(() => {
        items.value = items.value.filter(i => i.id !== item.id)
      })
      .catch((error) => {
        console.error('Erro ao excluir perfil:', error)
      })
      .finally(() => {
        ocupado.value = false
      })
}

onMounted(() => {
  pesquisar()
})
</script>

<template>
  <v-container>
    <pc-pesquisa>
      <template #titulo>
        Pesquisar Perfis
      </template>

      <v-row>
        <v-col>
          <v-text-field
            label="Nome"
            v-model="form.nome"
          />
        </v-col>
      </v-row>

      <template #botoesFormulario>
        <v-btn
          :loading="ocupado"
          color="primary"
          type="submit"
          @click="pesquisar"
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
          to="/admin/perfil/create"
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
      <template #[`item.total_usuarios`]="{ item }">
        <v-btn
          variant="text"
          :to="`/admin/usuario?perfil=${item.id}`"
          class="text-none"
        >
          {{ item.total_usuarios }}
        </v-btn>
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
            :to="`/perfis/${item.id}`"
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
            v-if="menuTabela.excluir && possuiPermissao(`/perfis/${item.id}`)"
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

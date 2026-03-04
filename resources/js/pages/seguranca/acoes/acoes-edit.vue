<script setup lang="ts">

import {useDisplay} from 'vuetify'
import {obrigatorio} from '@/helpers/validacoes'
import api from '@/api'
import {useRoute} from 'vue-router'
import {onMounted, reactive} from "vue";
import PcCadastro from "@/components/PcCadastro.vue";
import PcSnackbar from "@/components/PcSnackbar.vue";

const mobile = useDisplay().smAndDown

const route = useRoute()
const params = route.params

const tela = reactive({
  destaque: [
    {title: 'Destaque (é uma Tela)', value: true},
    {title: 'Outros (ajax, pdf, imagens...)', value: false},
  ],
  dependencias: [],
  metodos: [
    {title: 'GET', value: 'GET'},
    {title: 'POST', value: 'POST'},
    {title: 'PUT, PATCH', value: 'PUT'},
    {title: 'DELETE', value: 'DELETE'},
  ],
  grupos: [
    'Obrigatório',
    'Usuário',
  ],
  ocupado: false,
  acoes: [],
  snackbar: false,
  mensagem: '',
  corMensagem: 'success',
  tipoMensagem: 'error' as 'error' | 'success' | 'warning' | 'info',
})
const form = reactive({
  id: params.id,
  nome: '',
  metodo: 'GET',
  descricao: '',
  destaque: true,
  nome_amigavel: '',
  grupo: '',
  obrigatorio: false,
  rota_front: false,
  log: false,
  dependencia: [],
})

const salvar = () => {
  tela.snackbar = false
  tela.ocupado = true

  api.put(`/acao/${form.id}`, form)
      .then(() => {
        tela.mensagem = 'Ação atualizada com sucesso'
        tela.corMensagem = 'success'
        // tela.tipoMensagem = 'success'
        tela.snackbar = true
      })
      .catch(response => {
        tela.mensagem = response
        tela.corMensagem = 'error'
        // tela.tipoMensagem = 'error'
        tela.snackbar = true
      })
      .finally(() => {
        tela.ocupado = false
      })
}

onMounted(() => {
  api.get(`/acao/${form.id}/edit`)
      .then(({data}) => {
        form.nome = data.acao.nome
        form.metodo = data.acao.method
        form.descricao = data.acao.descricao
        form.destaque = data.acao.destaque
        form.nome_amigavel = data.acao.nome_amigavel
        form.grupo = data.acao.grupo
        form.obrigatorio = data.acao.obrigatorio
        form.rota_front = data.acao.rota_front
        form.log = data.acao.log_acesso
        form.dependencia = data.dependencias

        tela.grupos = data.grupos
        tela.dependencias = data.acoes
      })
})

</script>

<template>
  <v-container>
    <pc-cadastro @submit="salvar">
      <template #titulo>
        Edição de Ação
      </template>

      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-text-field
            v-model="form.nome"
            density="comfortable"
            label="Url*"
            placeholder="Ex: api/usuario"
            :rules="[obrigatorio]"
          />
        </v-col>

        <v-col
          cols="12"
          md="6"
        >
          <v-select
            v-model="form.metodo"
            density="comfortable"
            :items="tela.metodos"
            label="Método*"
            :rules="[obrigatorio]"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col>
          <v-text-field
            v-model="form.descricao"
            density="comfortable"
            label="Descrição"
            placeholder="Ex: Cadastro de Usuário"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-select
            v-model="form.destaque"
            density="comfortable"
            :items="tela.destaque"
            label="Tipo de Ação*"
          />
        </v-col>

        <v-col
          v-show="form.destaque"
          cols="12"
          md="6"
        >
          <v-text-field
            v-model="form.nome_amigavel"
            density="comfortable"
            label="Nome Amigável*"
            placeholder="ex: Cadastrar Usuário"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-combobox
            v-model="form.grupo"
            density="comfortable"
            :items="tela.grupos"
            label="Grupo"
            placeholder="Usuário"
          >
            <template #no-data>
              <v-sheet
                class="pa-2 text-caption"
                color="blue lighten-5"
              >
                Se não existir, apenas digite o nome desejado para criar
              </v-sheet>
            </template>
          </v-combobox>
        </v-col>
      </v-row>

      <v-row>
        <v-col>
          <v-switch
            v-model="form.obrigatorio"
            hide-details
            label="Obrigatório (qualquer usuário acessa)"
          />
        </v-col>
      </v-row>

      <v-row no-gutters>
        <v-col>
          <v-switch
            v-model="form.log"
            hide-details
            label="Gerar log ao acessar"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col>
          <v-autocomplete
            v-model="form.dependencia"
            chips
            item-title="nome"
            item-value="id"
            :items="tela.dependencias"
            label="Dependências"
            multiple
          />
        </v-col>
      </v-row>

      <template #botoesFormulario>
        <v-btn
          :block="mobile"
          color="primary"
          :loading="tela.ocupado"
          type="submit"
        >
          Salvar
        </v-btn>
        <v-btn
          :block="mobile"
          exact
          to="/seguranca/acoes"
        >
          Pesquisa
        </v-btn>
      </template>

      <pc-snackbar
        :ativar="tela.snackbar"
        :cor-mensagem="tela.corMensagem"
        :mensagem="tela.mensagem"
        @update:ativar="tela.snackbar = $event"
      />
    </pc-cadastro>
  </v-container>
</template>

<style scoped>

</style>

<script lang="ts" setup>
import {arrayNaoVazio, obrigatorio, tamanhoCPf} from '@/helpers/validacoes'
import PcCadastro from '@/components/PcCadastro.vue'
import {mascaraCpf} from '@/helpers/mascaras'
import {onMounted, reactive, watch} from 'vue'
import api, {apiNoPrefix} from '@/api'
import {useDisplay} from 'vuetify'
import PcSnackbar from "@/components/PcSnackbar.vue";
import {AxiosResponse} from "axios";

const mobile = useDisplay().smAndDown
const props = defineProps({
  usuario: {
    type: String,
    default: '',
  },
})

interface Tela {
  perfis: any[],
  exibirUsuarioExistente: boolean,
  snackbar: boolean,
  mensagem: string,
  pesquisandoCpf: boolean,
  ocupado: boolean,
  corMensagem: 'error' | 'success',
  exibirDetalhes: boolean,
  usuarioCadastro: string,
  dataCadastro: string,
  usuarioEdicao: string,
  dataEdicao: string,
}

const tela: Tela = reactive({
  perfis: [],
  exibirUsuarioExistente: false,
  snackbar: false,
  mensagem: '',
  pesquisandoCpf: false,
  ocupado: false,
  corMensagem: 'error' as 'error' | 'success',
  exibirDetalhes: false,
  usuarioCadastro: '',
  dataCadastro: '',
  usuarioEdicao: '',
  dataEdicao: '',
})

const nomeSistema = import.meta.env.VITE_NOME_SISTEMA

interface Form {
  id: string,
  cpf: string,
  nome: string,
  email: string,
  perfil: any[] | null,
}

const form: Form = reactive({
  id: '',
  cpf: '',
  nome: '',
  email: '',
  perfil: null,
})

interface InfoResponse {
  usuario: {
    id: string,
    cpf: string,
    nome: string,
    email: string,
  },
  perfis: any[],
  informacoesCadastro: {
    usuario_cadastro: string,
    data_cadastro: string,
    usuario_edicao: string,
    data_edicao: string,
  }
}

interface InfoEdicao {
  informacoesCadastro: {
    usuario_cadastro: string,
    data_cadastro: string,
    usuario_edicao: string,
    data_edicao: string,
  },
  perfis: any[],
  perfisUsuario: number[],
  usuario: {
    id: string,
    cpf: string,
    nome: string,
    email: string,
  }
}

watch(() => form.cpf, (newValue) => {

  form.cpf = mascaraCpf(newValue)

  // Se a única mudança detectada for a formatação do cpf então não faz nada
  if (newValue === form.cpf.replace(/\D/g, '')) {
    return;
  } else if (newValue && !/^\d{3}\.\d{3}\.\d{3}-\d{2}$/.test(newValue)) {
    form.id = ''
    form.email = ''
    tela.exibirUsuarioExistente = true
  }
})

const reiniciarTela = () => {
  form.id = ''
  form.nome = ''
  form.email = ''
  form.perfil = null
  tela.exibirUsuarioExistente = false
  tela.pesquisandoCpf = false
}

const info = () => {
  if (!form.cpf) {
    tela.snackbar = true
    tela.mensagem = 'Informe o CPF para buscar as informações do RH.'
    tela.corMensagem = 'error'
    return
  }

  tela.pesquisandoCpf = true
  api.get(`/admin/usuario/info?cpf=${form.cpf}`)
      .then((response: AxiosResponse<InfoResponse>) => {
        if (!response?.data) {//a api pode responder com http 204 caso não encontre resultado
          reiniciarTela()
          return
        }

        form.id = response.data.usuario.id
        form.nome = response.data.usuario.nome
        form.email = response.data.usuario.email
        form.perfil = response.data.perfis

        tela.usuarioCadastro = response.data.informacoesCadastro.usuario_cadastro
        tela.dataCadastro = response.data.informacoesCadastro.data_cadastro
        tela.usuarioEdicao = response.data.informacoesCadastro.usuario_edicao
        tela.dataEdicao = response.data.informacoesCadastro.data_edicao

        tela.exibirUsuarioExistente = response.status === 208
        // tela.corMensagem = response.status === 208 ? 'info' : 'success'
      })
      .catch(error => {
        tela.mensagem = error.response.data.message
        tela.snackbar = true
        tela.corMensagem = 'error'
      })
      .finally(() => {
        tela.pesquisandoCpf = false
      })
}

const salvar = () => {
  tela.snackbar = false
  tela.ocupado = true

  if (form.id) {
    editar()
    return
  }

  cadastrar()
}

const cadastrar = () => {
  tela.snackbar = false
  tela.ocupado = true

  api.post('/admin/usuario', form)
      .then(response => {
        tela.corMensagem = 'success'
        tela.mensagem = response.data.message
        form.id = response.data.usuario
      })
      .catch(error => {
        tela.mensagem = error
      })
      .finally(() => {
        tela.snackbar = true
        tela.ocupado = false
      })
}

const editar = () => {
  tela.snackbar = false
  tela.ocupado = true

  if (!form.id) {
    return
  }

  api.put(`/admin/usuario/${form.id}`, form)
      .then(response => {
        tela.corMensagem = 'success'
        tela.mensagem = response.data.message
      })
      .catch(error => {
        tela.corMensagem = 'error'
        tela.mensagem = error
      })
      .finally(() => {
        tela.snackbar = true
        tela.ocupado = false
      })
}

onMounted(() => {
  if (props.usuario) {
    form.id = props.usuario;

    apiNoPrefix.get(`/admin/usuario/${form.id}/edit`)
        .then((response: AxiosResponse<InfoEdicao>) => {
          // ignorarWatchCpf.value = true
          form.id = response.data.usuario.id
          form.cpf = response.data.usuario.cpf
          form.nome = response.data.usuario.nome
          form.email = response.data.usuario.email
          tela.perfis = response.data.perfis
          form.perfil = response.data.perfisUsuario

          tela.usuarioCadastro = response.data.informacoesCadastro.usuario_cadastro
          tela.dataCadastro = response.data.informacoesCadastro.data_cadastro

          tela.usuarioEdicao = response.data.informacoesCadastro.usuario_edicao
          tela.dataEdicao = response.data.informacoesCadastro.data_edicao
        })
        .finally(() => {
          return
        })
  } else {
      api.get('/admin/usuario')
          .then(response => {
            tela.perfis = response.data.perfis
          })
  }

})

</script>

<template>
  <v-container>
    <pc-cadastro @ao-enviar="salvar">
      <template #titulo>
        {{ form.id ? 'Edição' : 'Cadastro' }} de Usuário
      </template>

      <v-row>
        <v-col>
          <v-slide-x-transition>
            <v-alert
              v-show="form.id && tela.exibirUsuarioExistente"
              border="start"
              border-color="info"
              type="info"
              variant="tonal"
            >
              <b>{{ form.nome }}</b> já possui um cadastro em outro sistema.
              Sendo assim, preencha apenas os campos que deseja alterar. Ao salvar, este usuário passará a ter acesso ao
              sistema {{ nomeSistema }}.
            </v-alert>
          </v-slide-x-transition>
        </v-col>
      </v-row>
      <v-row>
        <v-col
          cols="12"
          md="4"
        >
          <v-text-field
            v-model="form.cpf"
            :loading="tela.pesquisandoCpf"
            :rules="[obrigatorio, tamanhoCPf]"
            label="CPF*"
            maxlength="14"
            type="tel"
          >
            <template #append-inner>
              <v-icon
                v-tooltip="'Clique para baixar informações do RH'"
                @click="info"
              >
                mdi-magnify
              </v-icon>
            </template>
          </v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-text-field
            v-model="form.nome"
            :rules="[obrigatorio]"
            label="Nome*"
          />
        </v-col>
        <v-col
          cols="12"
          md="6"
        >
          <v-text-field
            v-model="form.email"
            :rules="[obrigatorio]"
            autocomplete="email"
            label="E-mail*"
            type="email"
          />
        </v-col>
      </v-row>

      <!--      <v-row>-->
      <!--        <v-col-->
      <!--          cols="12"-->
      <!--          md="6"-->
      <!--        >-->
      <!--          <v-text-field-->
      <!--            v-model="form.senha"-->
      <!--            :label="exibirAsterisco('Senha')"-->
      <!--            :rules="calcularObrigatoriedade()"-->
      <!--            autocomplete="new-password"-->
      <!--            type="password"-->
      <!--          />-->
      <!--        </v-col>-->
      <!--        <v-col-->
      <!--          cols="12"-->
      <!--          md="6"-->
      <!--        >-->
      <!--          <v-text-field-->
      <!--            v-model="form.senha_confirmation"-->
      <!--            :label="exibirAsterisco('Confirmar Senha')"-->
      <!--            :rules="calcularObrigatoriedade()"-->
      <!--            autocomplete="new-password"-->
      <!--            type="password"-->
      <!--          />-->
      <!--        </v-col>-->
      <!--      </v-row>-->

      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-select
            v-model="form.perfil"
            :items="tela.perfis"
            :rules="[arrayNaoVazio]"
            chips
            item-title="text"
            label="Perfil*"
            multiple
          />
        </v-col>
      </v-row>

      <div v-if="form.id">
        <v-row>
          <v-col>
            <v-btn
              prepend-icon="mdi-account-details-outline"
              small
              variant="flat"
              @click="tela.exibirDetalhes = !tela.exibirDetalhes"
            >
              Detalhes do Cadastro
            </v-btn>
          </v-col>
        </v-row>

        <v-row>
          <v-col class="">
            <v-expand-transition>
              <v-card
                v-show="tela.exibirDetalhes"
                elevation="0"
              >
                <v-card-text>
                  <v-row>
                    <v-col
                      cols="12"
                      md="6"
                    >
                      <v-text-field
                        v-model="tela.usuarioCadastro"
                        disabled
                        filled
                        label="Criado por"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="6"
                    >
                      <v-text-field
                        v-model="tela.dataCadastro"
                        disabled
                        filled
                        label="Criado em"
                      />
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col
                      cols="12"
                      md="6"
                    >
                      <v-text-field
                        v-model="tela.usuarioEdicao"
                        disabled
                        filled
                        label="Editado por"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="6"
                    >
                      <v-text-field
                        v-model="tela.dataEdicao"
                        disabled
                        filled
                        label="Última edição em"
                      />
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-expand-transition>
          </v-col>
        </v-row>
      </div>

      <template #botoesFormulario>
        <v-btn
          :block="mobile"
          :loading="tela.ocupado"
          color="primary"
          type="submit"
        >
          Salvar
        </v-btn>
        <v-btn
          :block="mobile"
          :class="{'mt-2': mobile}"
          exact
          to="/admin/usuario"
        >
          Pesquisa
        </v-btn>
      </template>
    </pc-cadastro>

    <pc-snackbar
      :ativar="tela.snackbar"
      :cor-mensagem="tela.corMensagem"
      :mensagem="tela.mensagem"
      @update:ativar="tela.snackbar = $event"
    />
  </v-container>
</template>

<style scoped>

</style>

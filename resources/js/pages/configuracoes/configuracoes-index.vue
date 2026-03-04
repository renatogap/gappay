<script lang="ts" setup>
import {obrigatorio} from '@/helpers/validacoes'
import {onMounted, reactive, ref, watch} from 'vue'
import {useAppStore} from '@/stores/app'
import api from '@/api'
import Senha from './senha.vue'
import PcCadastro from '@/components/PcCadastro.vue'
import PcSnackbar from "@/components/PcSnackbar.vue";

const store = useAppStore()
const carregamentoInicial = ref(true)
const salvando = ref(false)
const modalSenha = ref(false)

const form = reactive({
  nome: '',
  email: '',
  cpf: '',
  nascimento: '',
  tema: '',
})

const snackbar = reactive({
  snackbar: false,
  corMensagem: 'success',
  mensagem: '',
})

const salvar = () => {
  salvando.value = true
  snackbar.snackbar = false
  api.patch('/usuario/configuracoes', form)
      .then(() => {
        snackbar.corMensagem = 'success'
        snackbar.mensagem = 'Configurações salvas com sucesso!'
      })
      .catch(() => {
        snackbar.corMensagem = 'error'
        snackbar.mensagem = 'Erro ao salvar as configurações.'
      })
      .finally(() => {
        snackbar.snackbar = true
        salvando.value = false
      })
}

const atualizarSenha = (info: {mensagem: string, corMensagem: string}) => {
  snackbar.snackbar = true
  snackbar.mensagem = info.mensagem
  snackbar.corMensagem = info.corMensagem
  if (snackbar.corMensagem !== 'error') {
    modalSenha.value = false
  }
}

watch(() => form.tema, novoTema => {
  store.setTema(novoTema)
})

onMounted(() => {
  api.get('/usuario/configuracoes')
      .then(r => {
        form.nome = r.data.nome
        form.email = r.data.email
        form.cpf = r.data.cpf
        form.nascimento = r.data.nascimento
        form.tema = r.data.tema
      })
      .finally(() => {
        carregamentoInicial.value = false
      })
})

</script>

<template>
  <v-container>
    <pc-cadastro
      @ao-enviar="salvar"
      :loading="carregamentoInicial"
    >
      <template #titulo>
        Informações de Usuário
      </template>

      <v-row>
        <v-col
          cols="12"
          md="8"
        >
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
                disabled
                v-model="form.email"
                :rules="[obrigatorio]"
                label="E-mail*"
                type="email"
              />
            </v-col>
          </v-row>

          <v-row>
            <v-col
              cols="12"
              md="3"
            >
              <v-text-field
                v-model="form.cpf"
                :rules="[obrigatorio]"
                label="CPF*"
              />
            </v-col>
            <v-col
              cols="12"
              md="3"
            >
              <v-text-field
                v-model="form.nascimento"
                label="Data de nascimento"
                type="date"
              />
            </v-col>

            <!--            <v-col>-->
            <!--              <v-select-->
            <!--                v-model="form.tema"-->
            <!--                :items="temas"-->
            <!--                label="Tema"-->
            <!--              />-->
            <!--            </v-col>-->
          </v-row>
        </v-col>
      </v-row>

      <template #botoesFormulario>
        <v-btn
          color="primary"
          type="submit"
          :loading="salvando"
        >
          Salvar
        </v-btn>

        <v-dialog
          max-width="400"
          v-model="modalSenha"
        >
          <template #activator="{ props: activatorProps }">
            <v-btn
              color="surface-variant"
              text="Alterar senha"
              v-bind="activatorProps"
              variant="flat"
            />
          </template>

          <!-- Botão de alterar senha -->
          <template #default="{ isActive }">
            <senha
              @fechar="isActive.value = false"
              @salvar="atualizarSenha"
            />
          </template>
        </v-dialog>
      </template>
    </pc-cadastro>

    <pc-snackbar
      :ativar="snackbar.snackbar"
      :cor-mensagem="snackbar.corMensagem"
      :mensagem="snackbar.mensagem"
      @update:ativar="snackbar.snackbar = $event"
    />
  </v-container>
</template>

<style scoped>

</style>

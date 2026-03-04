<script lang="ts" setup>
import {useDisplay} from 'vuetify';
import {computed, reactive, ref} from 'vue';
import api from '@/api';
import PcSnackbar from '@/components/PcSnackbar.vue';

const mobile = computed(() => useDisplay().smAndDown.value)

const sistema = import.meta.env.VITE_APP_NOME_SISTEMA
const email = ref<string>('')
const botaoDesabilitado = computed(() => {
  return email.value.length === 0
})
const tela = reactive({
  snackbar: false,
  corMensagem: '',
  mensagem: '',
  tempo: 10000
})

const enviar = () => {
  api.post('usuario/enviar-email', {
    email: email.value
  })
    .then((r) => {
      tela.corMensagem = 'success'
      tela.mensagem = r.data.message
      tela.snackbar = true
      email.value = ''
    })
    .catch(() => {
      tela.corMensagem = 'error'
      tela.mensagem = 'Erro ao enviar e-mail'
      tela.snackbar = true
    })
}

</script>

<template>
  <v-card
    :class="{'pa-10': !mobile, 'pa-4': mobile}"
    class="ma-4 rounded-lg mx-auto my-auto tamanho"
    elevation="4"
  >
    <pc-snackbar
      v-model="tela.snackbar"
      :cor-mensagem="tela.corMensagem"
      :mensagem="tela.mensagem"
      :tempo="tela.tempo"
      @update:ativar="tela.snackbar = $event"
    />
    <v-form @submit.prevent="enviar">
      <v-card-title
        class="text-center mb-6"
      >
        Recuperar senha
        <span v-show="mobile"> <br>{{ sistema }}</span>
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col>
            <v-text-field
              v-model="email"
              label="E-mail ou CPF"
            />
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <v-btn
              block
              color="grey-darken-4"
              size="large"
              type="submit"
              :disabled="botaoDesabilitado"
            >
              Enviar
            </v-btn>
          </v-col>
        </v-row>
        <v-row no-gutters>
          <v-col>
            <v-btn
              :block="mobile"
              :class="{'mb-2': mobile, 'pa-2': !mobile}"
              class="me-auto text-none mt-4"
              rounded="lg"
              variant="text"
              exact
              to="/login"
              prepend-icon="mdi-arrow-left"
            >
              Voltar
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-form>
  </v-card>
</template>

<style scoped>
.tamanho {
  min-width: 392px;
  min-height: 300px;
}
</style>

<script lang="ts" setup>
import {useDisplay} from 'vuetify'
import PcMensagem from '@/components/PcMensagem.vue'
import {computed, reactive, ref} from 'vue'
import api from '@/api'
import {converterAxiosParaAutenticacao} from '@/helpers/seguranca'
import {AxiosError} from 'axios'
import {useRouter} from 'vue-router'
import {useAppStore} from '@/stores/app'
import {LoginResponse} from '@/types/App';

const mobile = computed(() => useDisplay().smAndDown.value)
const sistema = import.meta.env.VITE_NOME_SISTEMA
const anoAtual = new Date().getFullYear()

const form = reactive({
  email: '',
  senha: '',
})
const mensagem = ref<any>()
const ocupado = ref(false)
const router = useRouter()
const store = useAppStore()

const autenticar = async () => {
  ocupado.value = true
  await enviarFormulario()
      .then(() => {
        router.push({name: 'home'})
      })
}

const enviarFormulario = async () => {
  await api.post('/login', form)
      .then(r => {
        const autenticacao: LoginResponse = converterAxiosParaAutenticacao(r)

        store.autenticarUsuario(autenticacao)
        router.push({name: 'home'})
      })
      .catch((e: AxiosError) => {
        mensagem.value = e
      })
      .finally(() => {
        ocupado.value = false
      })
}
</script>

<template>
  <div
    class="mx-auto my-auto"
  >
    <v-card
      :class="{'pa-10': !mobile, 'pa-3': mobile}"
      class="rounded-lg"
      elevation="4"
      :max-width="mobile ? '350px' : '450px'"
    >
      <v-card-title class="text-center mb-6">
        <v-img
          v-if="mobile"
          src="/images/logo_pcpa.png"
          class="ml-auto mr-auto"
          max-width="60"
        />
        <span v-show="!mobile">
          <h3>{{ sistema }}</h3>
          Sistema de Automação de Vendas
        </span>
        <span v-show="mobile"><br><h3>{{ sistema }}</h3></span>
      </v-card-title>

      <v-card-text>
        <v-form @submit.prevent="autenticar">
          <v-row>
            <v-col>
              <v-text-field
                id="email"
                v-model="form.email"
                label="E-mail"
                type="email"
                variant="outlined"
              />
            </v-col>
          </v-row>

          <v-row>
            <v-col>
              <v-text-field
                id="senha"
                v-model="form.senha"
                autocomplete="on"
                label="Senha"
                type="password"
                variant="outlined"
              />
            </v-col>
          </v-row>

          <pc-mensagem
            id="mensagem"
            v-show="mensagem"
            tipo="error"
            :mensagem="mensagem"
          />

          <v-row>
            <v-col>
              <v-btn
                id="entrar"
                block
                color="grey-darken-4"
                :loading="ocupado"
                rounded="lg"
                size="large"
                type="submit"
                variant="flat"
              >
                Entrar
              </v-btn>
            </v-col>
          </v-row>

          <v-row no-gutters>
            <v-col cols="auto">
              <v-btn
                class="text-none mt-4"
                rounded="lg"
                to="/login/esqueci-email-senha"
                variant="text"
              >
                Esqueceu senha ou e-mail?
              </v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>


    <div class="mt-4 d-flex justify-center">
      Desenvolvido por Rentec Digital - {{ anoAtual }}
    </div>
  </div>
</template>

<style scoped>

</style>

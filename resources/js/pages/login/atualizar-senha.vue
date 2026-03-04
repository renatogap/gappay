<script setup lang="ts">
import PcSnackbar from "@/components/PcSnackbar.vue";
import {computed, onBeforeMount, onMounted, reactive, ref} from "vue";
import {useDisplay} from "vuetify";
import api from "@/api";
import {obrigatorio} from "@/helpers/validacoes";
import {VForm} from "vuetify/components";
import {useRouter} from "vue-router";
import PcAlert from "@/components/PcAlert.vue";

const mobile = computed(() => useDisplay().smAndDown.value)
const ocupado = ref(false)
const sistema = import.meta.env.VITE_APP_NOME_SISTEMA
// const route = useRoute()
const router = useRouter()
const props = defineProps({
  hash: {
    type: String,
    default: '',
  },
})
const form = reactive({
  senha: '',
  senha_confirmation: ''
})
const tela = reactive({
  snackbar: false,
  corMensagem: '',
  mensagem: '',
  tempo: 10000,
  motrarSenha: false,
  mostrarSenhaConfirmation: false,
  ocupado: false,
  dialogErro: false
})
const formRef = ref<VForm>()

const enviar = async () => {
  const validacao = await formRef.value?.validate()
  if (!validacao?.valid) {
    return
  }

  ocupado.value = true
  api.post(`atualizar-senha/${props.hash}`, form)
      .then((r) => {
        tela.corMensagem = 'success'
        tela.mensagem = r.data.message
        tela.snackbar = true
      })
      .catch((e) => {
        tela.corMensagem = 'error'
        tela.mensagem = e
        tela.snackbar = true
      })
      .finally(() => {
        ocupado.value = false
        //redireciona para tela de login depois de 3 segundos
        setTimeout(() => {
          router.push({name: 'login'})
        }, 3000)

      })
}
onBeforeMount(() => {
  if (!props.hash) {
    tela.dialogErro = true
  }
})

onMounted(() => {
  api.get(`atualizar-senha/${props.hash}`)
      .catch(() => {
        tela.dialogErro = true
      })
})
</script>

<template>
  <v-card
    :class="{'pa-10': !mobile, 'pa-4': mobile}"
    class="ma-4 rounded-lg mx-auto my-auto tamanho"
    elevation="4"
  >
    <pc-alert
      v-model="tela.dialogErro"
      :persistente="true"
    >
      Link inválido
      <template #botoes>
        <v-btn
          color="grey-darken-4"
          size="large"
          @click="router.push({name: 'login'})"
        >
          Voltar para login
        </v-btn>
      </template>
    </pc-alert>
    <pc-snackbar
      v-model="tela.snackbar"
      :cor-mensagem="tela.corMensagem"
      :mensagem="tela.mensagem"
      :tempo="tela.tempo"
      @update:ativar="tela.snackbar = $event"
    />
    <v-form
      ref="formRef"
      @submit.prevent="enviar"
      v-if="!tela.dialogErro"
    >
      <v-card-title
        class="text-center mb-6"
      >
        Atualizar senha
        <span v-show="mobile"> <br>{{ sistema }}</span>
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col>
            <v-text-field
              v-model="form.senha"
              label="Nova senha"
              :type="tela.motrarSenha ? 'text' : 'password'"
              :append-inner-icon="tela.motrarSenha ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append-inner="tela.motrarSenha = !tela.motrarSenha"
              :rules="[obrigatorio]"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-text-field
              v-model="form.senha_confirmation"
              label="Confirmar senha"
              :type="tela.mostrarSenhaConfirmation ? 'text' : 'password'"
              :append-inner-icon="tela.mostrarSenhaConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append-inner="tela.mostrarSenhaConfirmation = !tela.mostrarSenhaConfirmation"
              :rules="[obrigatorio]"
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col>
            <v-btn
              block
              color="grey-darken-4"
              size="large"
              type="submit"
              :loading="ocupado"
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
              Tela de login
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

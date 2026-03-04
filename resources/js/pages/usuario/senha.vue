<script setup lang="ts">
import {computed, reactive, ref} from "vue";
import {obrigatorio} from "@/helpers/validacoes";
import {VForm} from "vuetify/components";
import {useDisplay} from "vuetify/framework";

const mobile = computed(() => useDisplay().smAndDown.value)
const sistema = import.meta.env.VITE_APP_NOME_SISTEMA
const form = reactive({
  senha: '',
  senha_confirmation: ''
})
const ocupado = ref(false)

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

  // api.post(`atualizar-senha/${route.query.hash}`, form)
  //     .then((r) => {
  //       tela.corMensagem = 'success'
  //       tela.mensagem = r.data.message
  //       tela.snackbar = true
  //     })
  //     .catch((e) => {
  //       tela.corMensagem = 'error'
  //       tela.mensagem = e
  //       tela.snackbar = true
  //     })
}

</script>

<template>
  <v-container>
    <v-card>
      <v-form
        ref="formRef"
        @submit.prevent="enviar"
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
  </v-container>
</template>

<style scoped>

</style>

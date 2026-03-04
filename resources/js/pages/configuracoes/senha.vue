<script lang="ts" setup>
import {obrigatorio} from '@/helpers/validacoes'
import {reactive, ref} from "vue";
import api from '@/api'
import {VForm} from "vuetify/components";

const emit = defineEmits(['salvar', 'fechar'])
// const props = defineProps<{
//   isActive: Ref<boolean>
// }>()
// const props = defineProps({
//   isActive: {
//     type: Boolean,
//     default: false,
//   },
// })
const salvando = ref(false)
const formRef = ref<VForm>()
const form = reactive({
  senha_atual: '',
  senha: '',
  senha_confirmation: ''
})

const salvar = async () => {
  const validacao = await formRef.value?.validate()
  if (!validacao?.valid) {
    return
  }
  api.put('/configuracoes/senha', form)
      .then(() => {
        emit('salvar', {
          mensagem: 'Senha alterada com sucesso!',
          corMensagem: 'success'
        })
        form.senha_atual = ''
        form.senha = ''
        form.senha_confirmation = ''
      })
      .catch((error) => {
        emit('salvar', {
          mensagem: error.response?.data?.message || 'Erro ao alterar a senha.',
          corMensagem: 'error'
        })
      })
      .finally(() => {
        salvando.value = false
      })
}
</script>

<template>
  <v-form
    @submit.prevent="salvar"
    ref="formRef"
  >
    <v-card title="Alteração de senha">
      <v-card-text>
        <v-row>
          <v-col>
            <v-text-field
              label="Senha atual"
              v-model="form.senha_atual"
              :rules="[obrigatorio]"
              type="password"
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col>
            <v-text-field
              label="Nova senha"
              v-model="form.senha"
              :rules="[obrigatorio]"
              type="password"
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col>
            <v-text-field
              label="Confirmar senha"
              v-model="form.senha_confirmation"
              :rules="[obrigatorio]"
              type="password"
            />
          </v-col>
        </v-row>
      </v-card-text>

      <v-card-actions>
        <v-spacer />

        <v-btn
          color="primary"
          text="Salvar"
          type="submit"
          :loading="salvando"
        />
        <v-btn
          text="Fechar"
          @click="$emit('fechar')"
        />
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<style lang="sass" scoped>

</style>

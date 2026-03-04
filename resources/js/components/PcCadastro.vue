<script lang="ts" setup>
  import { VForm } from 'vuetify/components'
  import {ref, useSlots} from "vue";

  const props = defineProps({
    titulo: {
      type: String,
      default: 'Título do formulário',
    },
    loading: {
      type: Boolean,
      default: false,
    },
  })
  const formRef = ref<VForm>()
  const emits = defineEmits(['aoEnviar', 'aoInvalidar'])
  const slots = useSlots()

  const salvar = async () => {
    const validacao = await formRef.value?.validate()
    if (validacao?.valid) {
      emits('aoEnviar')
    } else {
      emits('aoInvalidar')
    }
  }

  const haBotoesNoFormulario = () => {
    return slots.botoesFormulario !== undefined
  }
</script>

<template>
  <v-row>
    <v-col>
      <v-card
        :loading="loading"
        rounded="lg"
      >
        <v-card-title>
          <slot name="titulo">
            {{ props.titulo }}
          </slot>
        </v-card-title>
        <v-divider class="my-1 mx-4" />

        <v-card-text class="espaco-entre-botoes-formulario mb-2">
          <v-form
            ref="formRef"
            @submit.prevent="salvar"
            autocomplete="off"
          >
            <div class="v-container v-container--fluid">
              <slot />
            </div>

            <v-divider class="mb-4" />
            <slot name="botoesFormulario" />

            <v-btn
              v-if="!haBotoesNoFormulario"
              color="primary"
              type="submit"
            >
              Pesquisar
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<style>
.espaco-entre-botoes-formulario button {
  /*margin: 4px; !* 4px equivale ao ma-1 segundo a documentação do vuetify 3 "CSS spacing-helpers" *!*/
  margin-right: 10px;
}

@media (max-width: 600px) {
  .espaco-entre-botoes-formulario a, button {
    margin-top: 14px;
  }
}

</style>

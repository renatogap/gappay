<script lang="ts" setup>
  import { useAppStore } from '@/stores/app'
  import { ref, useSlots } from 'vue'
  import { VForm } from 'vuetify/components'

  const props = defineProps({
    titulo: {
      type: String,
      default: 'Título do formulário',
    },
    loading: {
      type: Boolean,
      default: false,
    },
    exibirFormulario: {
      type: Boolean,
      default: false,
    },
    exibirBotaoFiltro: {
      type: Boolean,
      default: true,
    },
  })

  const formRef = ref<VForm>()
  const emits = defineEmits(['aoEnviar', 'aoInvalidar'])
  const slots = useSlots()
  const store = useAppStore()

  /*
  Métodos
 */
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
      <v-card :loading="loading">
        <v-card-title>
          <div class="d-flex">
            <span class="me-auto">
              <slot
                name="titulo"
              >
                {{ props.titulo }}
              </slot>
            </span>
            <span>
              <v-btn
                density="compact"
                flat
                :icon="true"
                @click="store.exibirFiltros = !store.exibirFiltros"
              >
                <v-icon>
                  {{ store.exibirFiltros ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
                </v-icon>
                <v-tooltip activator="parent">
                  {{ store.exibirFiltros ? 'Ocultar filtros' : 'Exibir filtros' }}
                </v-tooltip>
              </v-btn>
            </span>
          </div>
        </v-card-title>

        <v-expand-transition>
          <div v-show="store.exibirFiltros">
            <v-divider class="my-1 mx-4" />
            <v-card-text class="espaco-entre-botoes-formulario mb-2">
              <v-form
                ref="formRef"
                @submit.prevent="salvar"
              >
                <div class="v-container v-container--fluid">
                  <slot />
                </div>
                <v-divider class="my-3 " />
                <slot name="botoesFormulario" />

                <div v-if="!haBotoesNoFormulario()">
                  <v-btn
                    color="primary"
                    type="submit"
                  >
                    Pesquisar
                  </v-btn>
                </div>
              </v-form>
            </v-card-text>
          </div>
        </v-expand-transition>

        <!--        <v-card-actions class="bg-grey-lighten-4">-->
        <!--          <v-spacer />-->
        <!--          <v-btn-->
        <!--            v-bind="props"-->
        <!--            @click="store.exibirFiltros = !store.exibirFiltros"-->
        <!--            density="compact"-->
        <!--            :icon="true"-->
        <!--          >-->
        <!--            <v-icon>-->
        <!--              {{ store.exibirFiltros ? 'mdi-chevron-up' : 'mdi-chevron-down' }}-->
        <!--            </v-icon>-->
        <!--            <v-tooltip activator="parent">-->
        <!--              {{ store.exibirFiltros ? 'Ocultar filtros' : 'Exibir filtros' }}-->
        <!--            </v-tooltip>-->
        <!--          </v-btn>-->
        <!--        </v-card-actions>-->
      </v-card>
    </v-col>
  </v-row>
</template>

<style>
.espaco-entre-botoes-formulario button {
  /*margin: 4px; !* 4px equivale ao ma-1 segundo a documentação do vuetify 3 "CSS spacing-helpers" *!*/
  margin-right: 10px;
}

@media (max-width: 900px) {
  .espaco-entre-botoes-formulario a, button {
    /*margin-top: 14px;*/
  }
}
</style>

<script lang="ts" setup>
  import { AxiosError } from 'axios'
  import { computed, PropType } from 'vue'

  const props = defineProps({
    tipo: {
      type: String as () => "error" | "success" | "warning" | "info" | undefined,
      default: undefined,
    },
    corMensagem: {
      type: String,
      default: undefined,
    },
    mensagem: {
      type: [String, Object] as PropType<string | AxiosError<any>>,
      default: '',
    },
  })

  /*
   *
   * <=============================>
   *  Resposta de sucesso do axios
   *  {
   *   config: {...},
   *   data: {...},
   *   headers: {...},
   *   request: {...},
   *   status: {...},
   *   statusText: {...},
   *  }
   * <=============================>
   *
   * <=============================>
   * Resposta de erro do axios
   * {
   *  config: {...},
   *  isAxiosError: true,
   *  ?request: XMLHttpRequest: {...},
   *  ?response: {
   *      config: {...},
   *      data: {...},
   *      headers: {...},
   *      request: {...},
   *      status: {...},
   *      statusText: {...},
   *  },
   *  toJSON: {...},
   * }
   * <===============================>
   */
  const mensagemTratada = computed(() => {
    if (typeof props.mensagem === 'string') {
      return tratarString(props.mensagem as string)
    } else if (props.mensagem instanceof AxiosError) {
      return tratarAxiosError(props.mensagem as AxiosError<any>)
    }

    return []
  })

  const tratarString = (mensagem: string): string[] => {
    return [mensagem]
  }

  const tratarAxiosError = (mensagem: AxiosError<any>): string[] => {
    const listaDeErro: string[] = []

    if (mensagem.response && mensagem.response.data) { // Na classe AxiosError response é opcional, logo data também é
      if (mensagem.response.data.errors) { // errors é gerado pelo laravel durante a validação
        /*
         * Padrão de mensagens de erro do laravel (objeto contendo um array de strings para cada campo):
         * errors: {
         *  campo1: ['mensagem1', 'mensagem2'],
         *  campo2: ['mensagem1', 'mensagem2'],
         * }
         */
        Object.keys(mensagem.response.data.errors).forEach((e: string) => {
          listaDeErro.push(mensagem.response?.data.errors[e].map((e: string) => e).join('<br>'))
        })
      } else if (mensagem.response.data?.message) {
        listaDeErro.push(mensagem.response.data.message)
      }
    } else {
      listaDeErro.push('Não foi possível identificar a resposta do servidor')
    }

    return listaDeErro
  }
</script>

<template>
  <v-slide-x-transition hide-on-leave>
    <v-alert
      v-if="mensagemTratada.length > 0"
      border="start"
      class="my-4"
      :color="corMensagem"
      :type="tipo"
      variant="tonal"
    >
      <ul>
        <li
          v-for="(m, index) in mensagemTratada"
          :key="index"
        >
          {{ m }}
        </li>
      </ul>
    </v-alert>
  </v-slide-x-transition>
</template>

<style scoped>

</style>

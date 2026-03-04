<script lang="ts" setup>
  import { AxiosError } from 'axios'
  import {computed, PropType, ref, watch} from 'vue'

  const props = defineProps({
    corMensagem: {
      // type: String as () => 'error' | 'success' | 'warning' | 'info' | undefined,
      type: String as () => string | undefined,
      default: undefined,
    },
    mensagem: {
      type: [String, Object] as PropType<string | AxiosError<any>>,
      default: '',
    },
    tempo: {
      type: Number,
      default: 5000,
    },
    ativar: {
      type: Boolean,
      default: false,
    },
  })

  const exibir = ref(false)
  const corMensagem = ref(props.corMensagem)
  const tempo = ref(props.tempo)
  defineEmits(['update:ativar'])

  const icone = () => {
    switch (corMensagem.value) {
      case 'error':
        return 'mdi-alert-circle'
      case 'success':
        return 'mdi-check-circle'
      case 'warning':
        return 'mdi-alert-circle'
      case 'info':
        return 'mdi-information'
      default:
        return 'mdi-information'
    }
  }

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

  watch(() => props.corMensagem, () => {
    corMensagem.value = props.corMensagem

    if (props.corMensagem === 'error') {
      tempo.value = -1
    } else {
      tempo.value = 5000
    }
  })

  watch(() => props.mensagem, () => {
    exibir.value = true
  })

  watch(() => props.ativar, () => {
    exibir.value = props.ativar
  })

  // onMounted(() => {
  //   corMensagem.value = props.corMensagem
  // })

</script>

<template>
  <v-snackbar
    v-model="exibir"
    :color="props.corMensagem"
    location="right"
    :timeout="tempo"
    variant="tonal"
  >
    <v-row>
      <v-col cols="1">
        <v-icon>{{ icone() }}</v-icon>
      </v-col>
      <v-col
        cols="11"
        v-html="mensagemTratada.join('br')"
      />
    </v-row>

    <template #actions>
      <v-btn
        icon="mdi-close"
        size="small"
        variant="text"
        @click="$emit('update:ativar', false)"
      />
    </template>
  </v-snackbar>

  <!--  <v-alert-->
  <!--    v-if="mensagemTratada.length > 0"-->
  <!--    class="my-4"-->
  <!--    border="end"-->
  <!--    :type="corMensagem"-->
  <!--  >-->
  <!--    <ul>-->
  <!--      <li-->
  <!--        v-for="(m, index) in mensagemTratada"-->
  <!--        :key="index"-->
  <!--      >-->
  <!--        {{ m }}-->
  <!--      </li>-->
  <!--    </ul>-->
  <!--  </v-alert>-->
</template>

<style scoped>

</style>

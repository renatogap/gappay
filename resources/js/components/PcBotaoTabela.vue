<script lang="ts" setup>
  import { useDisplay } from 'vuetify'
  import { PropriedadeBotaoTabela } from '@/types/App'
  import { PropType } from 'vue'
  import { useTabelaStore } from '@/stores/tabela'

  const prop = defineProps({
    show: {
      type: Boolean,
      default: true,
    },
    title: {
      type: String,
      default: 'Skeleton',
    },
    to: {
      type: String,
      default: '',
    },
    icon: {
      type: String,
      default: 'mdi-shield-outline',
    },
    hoverColor: {
      type: String,
      default: 'grey-darken-1',
    },
    items: {
      type: Array as PropType<PropriedadeBotaoTabela[]>,
      default: () => [] as PropriedadeBotaoTabela[],
    },
    // item: {
    //   type: Object,
    //   default: () => {},
    // },
  })

  const emits = defineEmits(['click', 'clickMenu', 'outros'])
  const smAndDown = useDisplay().smAndDown
  const storeTabela = useTabelaStore()

  const chamarFuncao = (configuracaoBotao: PropriedadeBotaoTabela) => {
    if (typeof configuracaoBotao.acao === 'undefined') { // se a ação não estiver definida, não faz nada
      return
    }
    configuracaoBotao.acao()
  }

  // const chamarFuncao = (evento: Function | undefined, item: any) => {
  //   if (typeof evento === 'undefined') {
  //     return
  //   }
  //
  //   // enviando para o componente pc-data-table um objeto contendo a funcao criada pelo usuário e o item atual da tabela
  //   emits('clickMenu', {
  //     evento,
  //     item,
  //   })
  // }

  // const tratarLink = (link: string | undefined, menu: any): string => {
  //   // quando evento está definido, não deve ser chamado o link
  //   if (typeof link === 'undefined') {
  //     return ''
  //   }
  //
  //   // quando o link é uma string, deve ser chamado o link
  //   if (typeof menu.id !== 'undefined') {
  //     return link.replace(/:[a-z_]+/, menu.id ?? '')
  //   }
  //
  //   return link
  // }

  const tratarLink = (botao: PropriedadeBotaoTabela): string => {
    if (typeof botao.link === 'undefined') {
      return ''
    }

    if (typeof storeTabela.linhaSelecionada.id !== 'undefined') {
      return botao.link.replace(/:[a-z_]+/, storeTabela.linhaSelecionada.id ?? '')
    }
    return botao.link
  }
</script>

<template>
  <v-hover v-slot="{ isHovering, props }">
    <v-btn
      v-if="prop.show"
      :icon="true"
      size="small"
      :to="prop.to"
      v-bind="props"
      @click="emits('click')"
    >
      <v-icon
        :color="isHovering ? prop.hoverColor : 'grey-darken-1'"
        size="large"
      >
        {{ prop.icon }}
      </v-icon>
      <v-tooltip
        activator="parent"
        location="bottom"
      >
        {{ prop.title }}
      </v-tooltip>

      <template v-if="prop.items.length > 0">
        <v-menu
          v-if="!smAndDown"
          activator="parent"
        >
          <v-list
            density="compact"
          >
            <v-list-item
              v-for="(menu, index) in prop.items"
              :key="index"
              :to="tratarLink(menu)"
              @click="chamarFuncao(menu)"
            >
              <template #prepend>
                <v-icon>{{ menu.icone ?? 'mdi-dot' }}</v-icon>
              </template>
              <v-list-item-title>
                {{ menu.nome }}
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
    </v-btn>
  </v-hover>
</template>

<style scoped>

</style>

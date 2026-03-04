<script lang="ts" setup>
  import { possuiPermissao } from '@/helpers/seguranca'
  import { BotoesTabela, PropriedadeBotaoTabela } from '@/types/App'
  import { PropType } from 'vue'
  import { useTabelaStore } from '@/stores/tabela'
  import { useRouter } from 'vue-router'

  const router = useRouter()

  defineProps({
    botoesTabela: {
      type: Object as PropType<BotoesTabela>,
      required: true,
    },
  })

  const emits = defineEmits(['update:menuAtivo'])
  const storeTabela = useTabelaStore()

  const chamarFuncao = (botao: PropriedadeBotaoTabela) => {
    if (typeof botao.acao === 'function') {
      botao.acao()
      // se for um link fecha antes de sair da página logo abaixo
      emits('update:menuAtivo', false)
    }

    if (typeof botao.link === 'string') {
      emits('update:menuAtivo', false)
      return router.push(botao.link.replace(/:[a-z_]+/, storeTabela.linhaSelecionada.id ?? ''))
    }
  }
  const chamarFuncaoExcluir = (botao: PropriedadeBotaoTabela) => {
    if (typeof botao.acao === 'function') {
      botao.acao(storeTabela.linhaSelecionada)
      emits('update:menuAtivo', false)
    }
  }
</script>

<template>
  <v-bottom-sheet>
    <v-card>
      <v-card-title>Ações disponíveis</v-card-title>
      <v-list>
        <!-- Botão visualizar -->
        <v-list-item
          v-if="botoesTabela.visualizar && possuiPermissao(`${botoesTabela?.visualizar?.link}`)"
          @click="chamarFuncao(botoesTabela?.visualizar)"
        >
          <v-list-item-title>
            <v-icon color="grey-darken-1">
              {{ botoesTabela?.visualizar?.icone ?? 'mdi-eye' }}
            </v-icon>
            {{ botoesTabela?.visualizar?.nome || 'Visualizar' }}
          </v-list-item-title>
        </v-list-item>

        <!-- Botão editar -->
        <v-list-item
          v-if="botoesTabela.editar && possuiPermissao(`${botoesTabela?.editar?.link}`)"
          @click="chamarFuncao(botoesTabela?.editar)"
        >
          <v-list-item-title>
            <v-icon color="grey-darken-1">
              {{ botoesTabela?.editar?.icone || 'mdi-pencil' }}
            </v-icon>
            {{ botoesTabela?.editar?.nome || 'Editar' }}
          </v-list-item-title>
        </v-list-item>

        <!-- Botão excluir -->
        <v-list-item
          v-if="botoesTabela.excluir && possuiPermissao(`${botoesTabela?.excluir?.link}`)"
          @click="chamarFuncaoExcluir(botoesTabela?.excluir)"
        >
          <v-list-item-title>
            <v-icon color="grey-darken-1">
              {{ botoesTabela?.excluir?.icone || 'mdi-delete' }}
            </v-icon>
            {{ botoesTabela?.excluir?.nome || 'Excluir' }}
          </v-list-item-title>
        </v-list-item>
        <v-divider />

        <!-- Botão outros -->
        <div v-if="botoesTabela.outros">
          <v-list-item
            v-for="(item, index) in botoesTabela.outros"
            :key="index"
            @click="chamarFuncao(botoesTabela?.outros[index])"
          >
            <v-list-item-title>
              <v-icon color="grey-darken-1">
                {{ item.icone }}
              </v-icon>
              {{ item.nome }}
            </v-list-item-title>
          </v-list-item>
        </div>
      </v-list>
    </v-card>
  </v-bottom-sheet>
</template>

<style scoped>

</style>

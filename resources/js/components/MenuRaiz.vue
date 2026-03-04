<script lang="ts" setup>
import {PropType} from 'vue';
import {Menu} from '@/types/App';
import {gerarLink} from '@/helpers/util';

defineProps({
    raiz: {
        type: Object as PropType<Menu[]>,
        required: true,
        default: () => [] as Menu[]
    }
})

/**
 * Retorna o link href ou to. O menu escolhido pode ser uma rota do vue ou uma url da api/monolítico
 * @param item
 * @param tipo
 */
const link = (item: Menu, tipo: string): string | undefined => {

    if (tipo === 'to') {//to = rotas do vue
        return item.acao.startsWith('/') ? gerarLink(item.acao) : undefined
    } else {//href = links api/monolítico
        return item.acao.startsWith('/') ? undefined : gerarLink(item.acao)
    }
}
</script>

<template>
  <template
    v-for="pai in raiz"
    :key="pai.id"
  >
    <v-btn
      v-if="typeof pai.submenus === 'undefined'"
      :href="link(pai, 'href')"
      :to="link(pai, 'to')"
      class="text-none"
    >
      {{ pai.nome }}
    </v-btn>
    <v-menu
      v-else
    >
      <template #activator="{props}">
        <v-btn
          append-icon="mdi-menu-down"
          class="text-none"
          v-bind="props"
        >
          {{ pai.nome }}
        </v-btn>
      </template>
      <v-list
        density="compact"
      >
        <v-list-item
          v-for="(item, index) in pai.submenus"
          :key="index"
          :href="link(item, 'href')"
          :to="link(item, 'to')"
          density="compact"
        >
          <v-list-item-title>{{ item.nome }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </template>
</template>

<style scoped>

</style>

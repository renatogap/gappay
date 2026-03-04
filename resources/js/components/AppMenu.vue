<script lang="ts" setup>
  import { useAppStore } from '@/stores/app'
  import { useDisplay } from 'vuetify'
  import { computed } from 'vue'
  import { Menu } from '@/types/App'

  const store = useAppStore()
  const display = useDisplay()
  // const menuExemplo: Menu[] = store.menu

  const icone = (config: string) => {
    return JSON.parse(config).icone
  }

  const menuExemplo: Menu[] = [
    // {
    //   nome: "Home",
    //   configuracoes: "{\"icone\": \"mdi-home\"}",
    //   acao: "/home",
    //   submenus: [] as Menu[],
    // },
    // {
    //   nome: "Agendamento",
    //   configuracoes: "{\"icone\": \"mdi-calendar\"}",
    //   acao: "",
    //   submenus: [
    //     {
    //       nome: "Agendar atendimento",
    //       configuracoes: "{\"icone\": \"mdi-circle-small\"}",
    //       acao: "/agendamento/agendar",
    //     },
    //     // {
    //     //   nome: "Consultar",
    //     //   configuracoes: "{\"icone\": \"mdi-circle-small\"}",
    //     //   acao: "/agendamento/consultar",
    //     // },
    //   ] as Menu[],
    // },
    // {
    //   nome: "Feriados",
    //   configuracoes: "{\"icone\": \"mdi-calendar-star\"}",
    //   acao: "/dae",
    //   submenus: [] as Menu[],
    // },
    // {
    //   nome: "Relatórios",
    //   configuracoes: "{\"icone\": \"mdi-file-chart\"}",
    //   acao: "/dae",
    //   submenus: [] as Menu[],
    // },
    // {
    //   nome: "Usuário",
    //   configuracoes: "{\"icone\": \"mdi-account\"}",
    //   acao: "/usuario",
    //   submenus: [] as Menu[],
    // },
    // {
    //   nome: "Sobre",
    //   configuracoes: "{\"icone\": \"mdi-information\"}",
    //   acao: "/sobre",
    //   submenus: [] as Menu[],
    // }
  ];

  const deveExibirRail = computed(() => {
    return store.somenteIcone && display.mdAndUp.value
  })

  const possuiFilhos = (menu: Menu): boolean => {
    return menu.submenus ? menu.submenus.length > 0 : false
  }

</script>

<template>
  <v-navigation-drawer
    v-model="store.exibirMenu"
    color="menu"
    :permanent="display.mdAndUp.value"
    :rail="deveExibirRail"
  >
    <!--    <v-list>-->
    <!--      <v-list-item-->
    <!--        prepend-icon="mdi-home"-->
    <!--        link-->
    <!--        density="comfortable"-->
    <!--        title="Início"-->
    <!--        to="/home"-->
    <!--      />-->
    <!--    </v-list>-->
    <v-divider />
    <v-list
      density="compact"
    >
      <template
        v-for="(m, index) in menuExemplo"
        :key="index"
      >
        <!-- Menus com filhos -->
        <v-list-group
          v-if="possuiFilhos(m)"
        >
          <template #activator="{ props }">
            <v-list-item
              density="comfortable"
              :prepend-icon="icone(m.configuracoes)"
              :title="m.nome"
              v-bind="props"
            />
          </template>

          <!-- Submenu -->
          <v-list-item
            v-for="(c, i) in m.submenus"
            :key="i"
            density="comfortable"
            link
            :title="c.nome"
            to="/seguranca/acao"
          >
            <v-tooltip
              activator="parent"
              location="end"
            >
              {{ c.nome }}
            </v-tooltip>
            <template #prepend>
              <v-icon
                class="mx-n14"
                color="yellow-darken-2"
              >
                {{ icone(c.configuracoes) }}
              </v-icon>
            </template>
          </v-list-item>
        </v-list-group>

        <!-- Menus sem filhos -->
        <v-list-item
          v-else
          density="comfortable"
          exact
          link
          :prepend-icon="icone(m.configuracoes)"
          :title="m.nome"
          :to="m.acao"
        />
      </template>

      <!-- Botão sair -->
      <!--    <template #append>-->
      <!--      <v-list-item-->
      <!--        class="elevation-2"-->
      <!--        link-->
      <!--        @click="sair"-->
      <!--      >-->
      <!--        <template #prepend>-->
      <!--          <v-icon color="red">-->
      <!--            mdi-logout-->
      <!--          </v-icon>-->
      <!--        </template>-->
      <!--        Sair-->
      <!--      </v-list-item>-->
      <!--    </template>-->
    </v-list>
  </v-navigation-drawer>
</template>

<style scoped>

</style>

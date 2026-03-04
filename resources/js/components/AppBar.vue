<script lang="ts" setup>
  import { useAppStore } from '@/stores/app'
  import { computed } from 'vue'
  import MenuRaiz from "./MenuRaiz.vue";

  // interface MenuType {
  //   id: number;
  //   nome: string;
  //   pai: number | null;
  //   ordem: number;
  //   acao_id: number | null;
  //   rota_front: boolean;
  //   url: string;
  //   submenus?: MenuType[];
  // }

  const store = useAppStore()
  const menus = store.menu

  const sistema = import.meta.env.VITE_NOME_SISTEMA

  const usuario = computed(() => {
    return store.usuario
  })

  const expandirMenu = () => {
    store.somenteIcone = !store.somenteIcone
  }

  const sair = async () => {
    await store.logout()
  }
</script>

<template>
  <v-app-bar
    color="menu"
    :elevation="2"
  >
    <v-app-bar-title>
      <v-icon
        icon="mdi-shield-outline"
        @click="expandirMenu"
      />
      {{ sistema }}
    </v-app-bar-title>

    <menu-raiz :raiz="menus" />

    <!--    <v-spacer />-->
    <v-menu>
      <template #activator="{ props }">
        <v-btn
          :icon="true"
          v-bind="props"
        >
          <v-avatar
            color="avatar"
          >
            <span class="text-h7">{{ usuario.iniciais }}</span>
          </v-avatar>
        </v-btn>
      </template>

      <v-card>
        <v-card-text>
          <div class="mx-auto text-center">
            <v-avatar
              color="avatar"
            >
              <span class="text-h7">{{ usuario.iniciais }}</span>
            </v-avatar>
            <h3>{{ usuario.nome }}</h3>
            <p class="text-caption mt-1">
              {{ usuario.email }}
            </p>
            <v-divider class="my-3" />
            <v-btn
              rounded
              to="/configuracoes"
              variant="text"
            >
              Editar Usuário
            </v-btn>
            <v-divider class="my-3" />
            <v-btn
              rounded
              variant="text"
              @click="sair"
            >
              Sair do sistema
            </v-btn>
          </div>
        </v-card-text>
      </v-card>
      <!--      &lt;!&ndash;      <v-btn size="large">&ndash;&gt;-->
      <!--      &lt;!&ndash;        <v-avatar class="ml-1">&ndash;&gt;-->
      <!--      &lt;!&ndash;          <v-img&ndash;&gt;-->
      <!--      &lt;!&ndash;            src="https://cdn.vuetifyjs.com/images/john.jpg"&ndash;&gt;-->
      <!--      &lt;!&ndash;            alt="John"&ndash;&gt;-->
      <!--      &lt;!&ndash;          />&ndash;&gt;-->
      <!--      &lt;!&ndash;        </v-avatar>&ndash;&gt;-->
      <!--      &lt;!&ndash;        <span class="ml-1">&ndash;&gt;-->
      <!--      &lt;!&ndash;          {{ store.usuario.nome }}&ndash;&gt;-->
      <!--      &lt;!&ndash;        </span>&ndash;&gt;-->
      <!--      &lt;!&ndash;      </v-btn>&ndash;&gt;-->
    </v-menu>
  </v-app-bar>
</template>

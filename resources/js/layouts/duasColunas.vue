<script setup lang="ts">

import SemLogin from './semLogin.vue';

// const mobile = useDisplay().smAndDown.value
// const steop = ref(0)
// const sistema = import.meta.env.VITE_APP_NOME_SISTEMA
// const anoAtual = new Date().getFullYear()

</script>

<template>
  <sem-login>
    <router-view v-slot="{ Component }">
      <v-slide-x-transition hide-on-leave>
        <component :is="Component" />
      </v-slide-x-transition>
    </router-view>
  </sem-login>
</template>

<style scoped>
</style>

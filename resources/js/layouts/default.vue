<template>
  <v-app>
    <app-bar />
    <v-main>
      <router-view v-slot="{ Component }">
        <v-slide-x-transition hide-on-leave>
          <component :is="Component" />
        </v-slide-x-transition>
      </router-view>
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import AppBar from "../components/AppBar.vue";
// import AppMenu from "../components/AppMenu.vue";
</script>

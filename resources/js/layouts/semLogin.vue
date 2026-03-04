<script setup lang="ts">
import {useDisplay} from "vuetify";

const sistema = import.meta.env.VITE_NOME_SISTEMA
const display = useDisplay()
</script>

<template>
  <div>
    <v-row
      no-gutters
      style="height: 100vh;"
    >
      <v-col
        v-show="!display.smAndDown.value"
        md="8"
        class="bg-grey-darken-3"
        :class="{'d-flex': !display.smAndDown.value}"
      >
        <v-card
          class="glass-card my-auto mx-auto elevation-2 d-flex rounded-xl"
          min-width="90%"
          min-height="90%"
        >
          <v-card-text class="d-flex align-center">
            <v-row
              no-gutters
              class="d-flex"
            >
              <v-col class="d-flex justify-center">
                <v-img
                  src="/images/logo_pcpa.png"
                  :max-width="display.mdAndDown.value ? '150' : '200'"
                />
              </v-col>
              <v-col
                :cols="display.mdAndUp.value ? 8 : 9"
                class="text-h1 text-start text-white d-flex align-center sombra-texto"
                :class="{'text-h1': display.mdAndUp.value, 'text-h2': display.mdAndDown.value}"
              >
                <v-scroll-x-reverse-transition>
                  <div>{{ sistema }}</div>
                </v-scroll-x-reverse-transition>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col
        cols="12"
        md="4"
        class="d-flex bg-grey-lighten-2"
      >
        <slot />
        <!--          <router-view />-->
        <!--          <router-view v-slot="{ Component }">-->
        <!--            <v-slide-x-transition hide-on-leave>-->
        <!--              <component :is="Component" />-->
        <!--            </v-slide-x-transition>-->
        <!--          </router-view>-->
      </v-col>
    </v-row>
  </div>
</template>

<style scoped>
.glass-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}
.grad {
  background-image: linear-gradient(to right, rgba(33,33,33,1), rgba(224,224,224,1));
}
.sombra-texto {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, .2);
}
.sombra-imagem {
  box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
}
</style>

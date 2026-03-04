<script setup lang="ts">

</script>

<template>
  <v-container class="align-center"
    style="height: 100vh;"
  >
    <v-row>
      <v-col cols="12" class="d-flex justify-center">
        <h2>
          A página que você está tentando acessar não existe.
        </h2>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" class="d-flex justify-center">
        <v-btn
            color="primary"
            @click="$router.go(-1)"
        >
          Voltar
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>

</style>

<script setup>
const sistema = import.meta.env.VITE_NOME_SISTEMA || 'Skeleton';
</script>

<template>
  <v-container>
    <h1>Bem-Vindo ao {{ sistema }}</h1>
  </v-container>
</template>
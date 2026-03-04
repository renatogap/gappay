<script lang="ts" setup>
  const props = defineProps({
    titulo: {
      type: String,
      default: 'Alerta',
    },
    persistente: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])
</script>

<template>
  <v-dialog
    max-width="500"
    :persistent="persistente"
  >
    <v-card>
      <v-card-title class="bg-grey-darken-3">
        {{ props.titulo }}
      </v-card-title>
      <v-card-text>
        <slot />
      </v-card-text>
      <v-card-actions>
        <template v-if="!$slots.botoes">
          <v-btn
            @click="emit('close')"
          >
            Fechar
          </v-btn>
        </template>
        <slot name="botoes" />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped>

</style>

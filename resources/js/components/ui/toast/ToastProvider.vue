<script setup lang="ts">
import { ToastProvider, type ToastProviderProps } from 'reka-ui'

const props = defineProps<ToastProviderProps>()
</script>

<template>
  <div class="fixed inset-0 pointer-events-none z-[9999]">
    <ToastProvider v-bind="props">
      <slot />
    </ToastProvider>
  </div>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils'
import { ToastViewport as RekaToastViewport, type ToastViewportProps } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<ToastViewportProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props
  return delegated
})
</script>

<template>
  <RekaToastViewport v-bind="delegatedProps"
    :class="cn('fixed top-0 z-[9999] flex max-h-screen w-full flex-col-reverse p-4 sm:fixed sm:right-0 sm:top-0 sm:flex-col md:max-w-[420px]', props.class)">
    <slot />
  </RekaToastViewport>
</template>

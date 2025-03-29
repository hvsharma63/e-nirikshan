<script setup lang="ts">
import { isVNode } from 'vue'
import { Toast, ToastClose, ToastDescription, ToastProvider, ToastTitle, ToastViewport } from '.'
import { useToast } from './use-toast'
import { AlertCircle, CheckCircle2, XCircle, AlertTriangle } from 'lucide-vue-next'

const { toasts } = useToast()

const getIcon = (variant?: string) => {
  switch (variant) {
    case 'success':
      return CheckCircle2
    case 'warning':
      return AlertTriangle
    case 'error':
      return XCircle
    default:
      return AlertCircle
  }
}
</script>

<template>
  <ToastProvider>
    <Toast v-for="toast in toasts" :key="toast.id" v-bind="toast">
      <div class="flex w-full items-start gap-3">
        <component :is="getIcon(toast.variant)" class="mt-0.5 h-5 w-5 shrink-0" />
        <div class="grid flex-1 gap-1">
          <ToastTitle v-if="toast.title">
            {{ toast.title }}
          </ToastTitle>
          <template v-if="toast.description">
            <ToastDescription v-if="isVNode(toast.description)">
              <component :is="toast.description" />
            </ToastDescription>
            <ToastDescription v-else>
              {{ toast.description }}
            </ToastDescription>
          </template>
        </div>
      </div>
      <ToastClose />
      <component :is="toast.action" />
    </Toast>
    <ToastViewport />
  </ToastProvider>
</template>

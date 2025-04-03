<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useToast } from './ui/toast';
import Toaster from './ui/toast/Toaster.vue';

const { toast } = useToast();
const page = usePage<{ flash: { success?: string; warning?: string; error?: string } }>();

watch(
    () => page.props.flash,
    (flash) => {
        if (flash.success) {
            toast({
                title: flash.success,
                variant: "success",
            });
        }
        if (flash.warning) {
            toast({
                title: flash.warning,
                variant: "warning",
            });
        }
        if (flash.error) {
            toast({
                title: flash.error,
                variant: "error",
            });
        }
    },
    { immediate: true }
);
</script>

<template>
    <Toaster />
</template>

<script setup lang="ts">
import AppGlobalToastr from '@/components/AppGlobalToastr.vue';
import { Toaster } from '@/components/ui/toast';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <AppGlobalToastr />
        <Toaster />
    </AppLayout>
</template>

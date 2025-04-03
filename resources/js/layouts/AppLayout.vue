<script setup lang="ts">
import AppGlobalToastr from '@/components/AppGlobalToastr.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import AppFooterLayout from './app/AppFooterLayout.vue';

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
        <AppFooterLayout />
    </AppLayout>
</template>

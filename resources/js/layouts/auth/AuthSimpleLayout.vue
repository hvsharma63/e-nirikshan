<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';
import AuthSimpleFooter from './AuthSimpleFooter.vue';
defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center bg-background p-6 md:p-10">
        <div class="flex-1 flex items-center justify-center w-full">
            <div class="w-full max-w-sm">
                <div class="flex flex-col gap-8">
                    <div class="flex flex-col items-center gap-4">
                        <Link :href="route('dashboard')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md">
                            <AppLogoIcon class="size-9 fill-current text-[var(--foreground)] dark:text-white" />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                        </Link>
                        <div class="space-y-2 text-center">
                            <h1 class="text-xl font-medium">{{ title }}</h1>
                            <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
                        </div>
                    </div>
                    <slot />
                </div>
            </div>
        </div>
        <AuthSimpleFooter class="mt-6" />
    </div>
</template>

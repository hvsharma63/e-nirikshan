<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <footer class="border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-center relative">
                <div class="flex flex-col sm:flex-row items-center gap-1 sm:gap-2 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Developed & Designed by IT Cell, RJT Division, WR.
                    </p>
                    <span class="hidden sm:inline text-gray-400">•</span>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        &copy; {{ new Date().getFullYear() }} Ghaat-Nirikshan. All rights reserved.
                    </p>
                </div>
                <div class="flex items-center sm:absolute sm:right-0">
                    <span class="text-xs text-gray-400 dark:text-gray-500">Version 1.0.0</span>
                </div>
            </div>
        </div>
    </footer>
</template>

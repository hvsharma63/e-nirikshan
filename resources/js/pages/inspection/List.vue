<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Calendar,
    Eye,
} from 'lucide-vue-next';
import { onMounted } from 'vue';
import AppDataTable from '@/components/AppDataTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Inspections',
        href: '/inspections',
    },
];
const page = usePage<SharedData>();
const columns = [
    { accessorKey: 'id', header: 'ID' },
    { accessorKey: 'location', header: 'Location' },
    { accessorKey: 'deficiencies_count', header: 'Total Deficiencies' },
    { accessorKey: 'date', header: 'Date' },
    { accessorKey: 'time', header: 'Time' },
    { accessorKey: 'day_period', header: 'Period' },
    { accessorKey: 'status', header: 'Status' },
];
// Add a utility function for responsive layout
const isMobile = ref(window.innerWidth < 768);
window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth < 768;
});

onMounted(() => {
    // Add any additional setup if needed
    console.log(page.props.inspections);
});
</script>

<template>

    <Head title="My Inspections" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">My Inspections</h2>
                <Link href="/inspections/create">
                <Button class="gap-2">
                    <Calendar class="h-4 w-4" />
                    Diarise Inspection
                </Button>
                </Link>
            </div>

            <AppDataTable apiUrl="/inspections/list" :columns="columns" empty-message="No inspections found"
                empty-description="When you create Inspections, they will appear here.">
                <template #actions="{ row }">
                    <Link :href="`/inspections/${row.id}`"
                        class="inline-flex items-center gap-2 text-primary hover:text-primary/80">
                    <Eye class="h-4 w-4" />
                    View
                    </Link>
                </template>
            </AppDataTable>
        </div>
    </AppLayout>
</template>

<style scoped>
@media (max-width: 768px) {
    :deep(td) {
        @apply rounded-lg bg-white dark:bg-gray-800;
    }

    :deep(tr:not(:last-child) td) {
        @apply mb-2;
    }
}
</style>

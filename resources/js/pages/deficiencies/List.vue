<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Calendar, Eye, LocateFixed, UserRound, CheckCircle2, Clock, AlertCircle, Hash } from 'lucide-vue-next';
import AppDataTable from '@/components/AppDataTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Received Deficiencies',
        href: '/deficiencies',
    },
];

const page = usePage<SharedData>();

const columns = [
    { accessorKey: 'id', header: 'ID' },
    { accessorKey: 'location', header: 'Location' },
    { accessorKey: 'note', header: 'Note' },
    { accessorKey: 'action_date', header: 'Action Date' },
    { accessorKey: 'attended_by', header: 'Attended By' },
    { accessorKey: 'date', header: 'Date' },
    { accessorKey: 'time', header: 'Time' },
    { accessorKey: 'status', header: 'Status' },
];

// Add a utility function for responsive layout
const isMobile = ref(window.innerWidth < 768);
window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth < 768;
});

</script>

<template>

    <Head title="My Deficiencies" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Deficiencies Pertaining to Me
            </h2>

            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <AppDataTable apiUrl="/deficiencies/list" :columns="columns" empty-message="No deficiencies found"
                    empty-description="If deficiencies get assigned to you, they will appear here.">
                    <template #actions="{ row }">
                        <Link :href="`/inspections/${row.id}`"
                            class="inline-flex items-center gap-2 text-primary hover:text-primary/80">
                        <Eye class="h-4 w-4" />
                        View
                        </Link>
                    </template>
                </AppDataTable>
            </div>
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

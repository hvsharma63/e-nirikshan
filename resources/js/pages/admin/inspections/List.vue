<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { DropdownItem, SharedData, type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';
import AppDataTable from '@/components/AppDataTable.vue';
import AppFilter, { FilterConfig } from '@/components/AppFilter.vue';
const page = usePage<SharedData>();

const filterConfig: FilterConfig = {
    filters: [
        {
            id: 'location',
            type: 'search',
            label: 'Search',
            key: 'search',
            placeholder: 'Search by location...'
        },
        {
            id: 'inspectionDate',
            type: 'daterange',
            label: 'Inspection Date',
            keys: { from: 'from', to: 'to' },
            key: null,
        },
        {
            id: 'branchOfficerMultiselect',
            type: 'multiselect',
            label: 'User',
            keys: null,
            key: 'users',
            placeholder: 'Select Officer',
            options: page.props.users?.data as DropdownItem[],
        }
        // Example of additional filters:
        // {
        //     id: 'status',
        //     type: 'search',
        //     label: 'Search Status',
        //     key: 'status_search'
        // },
        // {
        //     id: 'createdAt',
        //     type: 'daterange',
        //     label: 'Created Date',
        //     keys: { from: 'created_from', to: 'created_to' }
        // }
    ]
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'All Inspections', href: '/inspections' },
];
const columns = [
    { accessorKey: 'id', header: 'ID' },
    { accessorKey: 'location', header: 'Location' },
    { accessorKey: 'attended_by', header: 'Inspector' },
    { accessorKey: 'deficiencies_count', header: 'Total Deficiencies' },
    { accessorKey: 'date', header: 'Date' },
    { accessorKey: 'time', header: 'Time' },
    { accessorKey: 'day_period', header: 'Period' },
    { accessorKey: 'status', header: 'Status' },
];

const filters = ref({});
const isLoading = ref(false);

const handleFilter = (newFilters: any) => {
    filters.value = newFilters;
};

onMounted(() => {
    console.log(page.props.users);
});
</script>

<template>

    <Head title="All Inspections" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
            <AppFilter :filter-config="filterConfig" :loading="isLoading" @filter="handleFilter" />

            <AppDataTable :apiUrl="route('admin.inspections.list')" :columns="columns" :filters="filters"
                @update:loading="(val) => isLoading = val" empty-message="No inspections found"
                empty-description="When you create Inspections, they will appear here.">
                <template #actions="{ row }">
                    <Link :href="`/admin/inspections/${row.id}`"
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

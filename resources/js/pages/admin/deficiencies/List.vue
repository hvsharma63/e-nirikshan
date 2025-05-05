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
            id: 'search',
            type: 'search',
            label: 'Search',
            key: 'search',
            placeholder: 'Search by location, note...'
        },
        {
            id: 'inspectionDate',
            type: 'daterange',
            label: 'Inspection Date',
            keys: { from: 'from', to: 'to' },
            key: null,
        },
        {
            id: 'inspectorSelection',
            type: 'select',
            label: 'Inspector',
            keys: null,
            key: 'inspector',
            placeholder: 'Select Officer',
            options: page.props.users?.data as DropdownItem[],
        },
        {
            id: 'per',
            type: 'select',
            label: 'Pertaining Officer',
            keys: null,
            key: 'pertaining_officer',
            placeholder: 'Select Officer',
            options: page.props.users?.data as DropdownItem[],
        }
    ]
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'All Deficiencies', href: '/admin/deficiencies' },
];
const columns = [
    { accessorKey: 'location', header: 'Location' },
    { accessorKey: 'note', header: 'Note' },
    { accessorKey: 'attended_by', header: 'Inspector' },
    { accessorKey: 'pertains_to', header: 'Pertaining Officer' },
    { accessorKey: 'action_date', header: 'Action Date' },
    { accessorKey: 'date', header: 'Insp. Date' },
    { accessorKey: 'time', header: 'Insp. Time' },
    { accessorKey: 'status', header: 'Def. Status' },
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

    <Head title="All Deficiencies" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
            <AppFilter :filter-config="filterConfig" :loading="isLoading" @filter="handleFilter" />

            <AppDataTable :apiUrl="route('admin.deficiencies.list')" :columns="columns" :filters="filters"
                @update:loading="(val) => isLoading = val" empty-message="No deficiencies found"
                empty-description="If deficiencies get assigned to you, they will appear here.">
                <template #actions="{ row }">
                    <Link :href="`/admin/deficiencies/${row.id}`"
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

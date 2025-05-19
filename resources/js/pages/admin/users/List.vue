<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
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
            placeholder: 'Search by user...'
        },
    ]
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'All Users', href: '/admin/users' },
];
const columns = [
    { accessorKey: 'name', header: 'Name' },
    { accessorKey: 'designation', header: 'Designation' },
    { accessorKey: 'mobile_no', header: 'Mobile No.' },
    { accessorKey: 'pf_no', header: 'PF No.' },
    { accessorKey: 'inspections_attended_count', header: 'Inspections Attended' },
    { accessorKey: 'deficiencies_attended_count', header: 'Deficiencies Attended' },
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

    <Head title="All Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
            <AppFilter :filter-config="filterConfig" :loading="isLoading" @filter="handleFilter" />

            <AppDataTable :apiUrl="route('admin.users.list')" :columns="columns" :filters="filters"
                @update:loading="(val) => isLoading = val" empty-message="No users found"
                empty-description="If users get assigned to you, they will appear here.">
                <template #actions="{ row }">
                    <Link :href="`/admin/users/${row.id}/details`"
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

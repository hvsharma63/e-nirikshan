<script setup lang="ts">
import { ref, watch } from 'vue';
import apiService from '@/services/apiService';
import { useVueTable, getCoreRowModel, type ColumnDef } from '@tanstack/vue-table';
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Loader2 } from 'lucide-vue-next';
import AppPagination from './AppPagination.vue';

const props = defineProps({
    apiUrl: String,
    columns: Array as () => ColumnDef<any, any>[],
    filters: Object,
    emptyMessage: {
        type: String,
        default: 'No results found.'
    },
    emptyDescription: {
        type: String,
        default: 'Try adjusting your search or filters to find what you\'re looking for.'
    },
    shouldSearch: {
        type: Boolean,
        default: false
    }
});

// Simplified state management
const data = ref([]);
const total = ref(0);
const searchQuery = ref('');
const isLoading = ref(false);
const pageCount = ref(0);
const pagination = ref({ pageIndex: 0, pageSize: 10 });
const extraFilters = ref(props.filters || {});

// Add scroll handler
const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const table = useVueTable({
    getCoreRowModel: getCoreRowModel(),
    data,
    columns: [...(props.columns || []), { accessorKey: 'actions', header: 'Actions' }],
    state: { pagination: pagination.value },
    manualPagination: true,
    pageCount: pageCount.value,
});

// Simplified fetch function
const fetchData = async () => {
    isLoading.value = true;
    try {
        const response = await apiService.get(props.apiUrl || '', {
            params: {
                page: pagination.value.pageIndex + 1,
                per_page: pagination.value.pageSize,
                search: searchQuery.value,
                ...extraFilters.value,
            },
        });
        const { data: rows, meta } = response.data?.data ? response.data : { data: response.data, meta: response.meta || {} };
        data.value = rows;
        total.value = meta.total || rows.length;
        pageCount.value = Math.ceil(total.value / pagination.value.pageSize);
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

// Update watchers to exclude pagination
watch(
    [searchQuery, extraFilters],
    fetchData,
    { immediate: true }
);

// Add utility for responsive layout
const isMobile = ref(window.innerWidth < 768);
window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth < 768;
});

const goToPage = (page: number) => {
    pagination.value.pageIndex = page - 1;
    fetchData();
    scrollToTop();
};
</script>

<template>
    <div class="w-full space-y-4">
        <!-- Search and filters section -->
        <div class="flex flex-col sm:flex-row gap-3" v-if="props.shouldSearch">
            <div class="w-full sm:max-w-md relative">
                <Input v-model="searchQuery" placeholder="Search..."
                    class="w-full pl-9 bg-white/50 dark:bg-gray-800/50" />
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-4 w-4 text-gray-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="flex gap-2 items-center">
                <slot name="filters"></slot>
                <Button @click="fetchData" variant="default" class="shadow-sm hover:shadow-md transition-shadow">
                    <Loader2 v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
                    Search
                </Button>
            </div>
        </div>

        <!-- Table section -->
        <div class="relative rounded-lg md:border md:bg-white md:dark:bg-gray-800">
            <!-- Loading overlay -->
            <div v-if="isLoading"
                class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 flex items-center justify-center z-10">
                <Loader2 class="h-8 w-8 animate-spin text-primary" />
            </div>

            <Table class="w-full">
                <TableHeader class="hidden md:table-header-group">
                    <TableRow>
                        <TableHead v-for="col in table.getFlatHeaders()" :key="col.id"
                            class="bg-gray-50/50 dark:bg-gray-800/50 font-semibold">
                            {{ col.column.columnDef.header }}
                        </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody class="divide-y divide-gray-200 dark:divide-gray-700 md:divide-y-0">
                    <TableRow v-if="!data.length && !isLoading">
                        <TableCell :colspan="table.getFlatHeaders().length" class="h-32 text-center">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ props.emptyMessage }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ props.emptyDescription }}
                                </p>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id"
                        class="block md:table-row hover:bg-gray-50/50 dark:hover:bg-gray-800/50 
                               transition-colors rounded-lg md:rounded-none border-[2.5px] md:border-0 border-gray-200 
                               dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md md:shadow-none">
                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id" class="flex items-center justify-between p-3 first:pt-4 last:pb-4 md:table-cell md:py-3
                                   border-b last:border-b-0 border-gray-200 dark:border-gray-700 md:border-0">
                            <span class="text-xs font-medium tracking-wide text-gray-500 dark:text-gray-400 md:hidden">
                                {{ cell.column.columnDef.header }}
                            </span>
                            <span
                                class="text-[15px] leading-normal md:text-sm text-gray-900 dark:text-white 
                                        text-right md:text-left flex-shrink-0 md:flex-shrink max-w-[60%] md:max-w-none">
                                <template v-if="cell.column.id === 'actions'">
                                    <slot name="actions" :row="row.original"></slot>
                                </template>
                                <template v-else>
                                    {{ cell.getValue() }}
                                </template>
                            </span>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <AppPagination :totalItems="total" :currentPage="pagination.pageIndex + 1" :itemsPerPage="pagination.pageSize"
            @update:currentPage="goToPage" />
    </div>
</template>

<style scoped>
@media (max-width: 768px) {
    :deep(tbody) {
        @apply block px-4 pb-4 grid gap-4;
    }
}
</style>

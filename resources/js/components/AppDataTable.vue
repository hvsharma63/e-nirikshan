<script setup lang="ts">
import { ref, reactive, onMounted, watch } from 'vue';
import apiService from '@/services/apiService';
import { useVueTable, getCoreRowModel, type ColumnDef } from '@tanstack/vue-table';
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Loader2 } from 'lucide-vue-next';
import AppPagination from './AppPagination.vue';

const emit = defineEmits(['update:loading']);

const props = defineProps({
    apiUrl: String,
    columns: Array as () => ColumnDef<any, any>[],
    filters: {
        type: Object,
        default: () => ({})
    },
    emptyMessage: {
        type: String,
        default: 'No results found.'
    },
    emptyDescription: {
        type: String,
        default: 'Try adjusting your search or filters to find what you\'re looking for.'
    },
    showIndex: {
        type: Boolean,
        default: true
    }
});

// Data states
const data = ref([]);
const total = ref(0);
const isLoading = ref(false);
const pageCount = ref(0);
const pagination = ref({ pageIndex: 0, pageSize: 10 });
const extraFilters = ref(props.filters);

// Watch for filter changes from parent
watch(() => props.filters, (newFilters) => {
    console.log(newFilters, 'newFilters');
    extraFilters.value = newFilters;
    // Reset to page 1 when filters change
    pagination.value.pageIndex = 0;
    fetchData();
}, { deep: true });

// Utility
const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const table = useVueTable({
    getCoreRowModel: getCoreRowModel(),
    data,
    columns: [
        ...(props.showIndex ? [{
            id: 'index',
            header: '#',
            cell: (row: any) => (pagination.value.pageIndex * pagination.value.pageSize) + row.row.index + 1
        }] : []),
        ...(props.columns || []),
        { accessorKey: 'actions', header: 'Actions' }
    ],
    state: {
        pagination: pagination.value,
    },
    manualPagination: true,
    pageCount: pageCount.value,
});

const fetchData = async () => {
    isLoading.value = true;
    emit('update:loading', true);
    try {
        const response = await apiService.get(props.apiUrl || '', {
            params: {
                page: pagination.value.pageIndex + 1,
                per_page: pagination.value.pageSize,
                ...extraFilters.value
            },
        });
        console.log(response, 'response');
        const { data: rows, meta } = response.data?.data ? response.data : { data: response.data, meta: response.meta || {} };
        data.value = rows;
        total.value = meta.total || rows.length;
        pageCount.value = Math.ceil(total.value / pagination.value.pageSize);
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
        emit('update:loading', false);
    }
};

onMounted(() => {
    fetchData();
});

// Pagination event.
const goToPage = (page: number) => {
    pagination.value.pageIndex = page - 1;
    fetchData();
    scrollToTop();
};
</script>

<template>
    <div class="w-full space-y-4">
        <div class="md:rounded-lg md:border md:bg-card">
            <!-- Loading overlay -->
            <div v-if="isLoading"
                class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 flex items-center justify-center z-10">
                <Loader2 class="h-8 w-8 animate-spin text-primary" />
            </div>

            <Table>
                <TableHeader class="hidden md:table-header-group">
                    <TableRow class="hover:bg-transparent">
                        <TableHead v-for="col in table.getFlatHeaders()" :key="col.id"
                            class="h-12 px-4 text-left align-middle font-bold text-muted-foreground">
                            {{ col.column.columnDef.header }}
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="md:divide-y md:divide-gray-200 dark:divide-gray-700">
                    <TableRow v-if="!data.length && !isLoading">
                        <TableCell :colspan="table.getFlatHeaders().length" class="h-24 text-center">
                            <div class="flex flex-col items-center justify-center gap-1">
                                <p class="text-sm font-medium">{{ props.emptyMessage }}</p>
                                <p class="text-sm text-muted-foreground">{{ props.emptyDescription }}</p>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id" class="block md:table-row transition-colors rounded-md md:rounded-none 
                        border-2 md:border-0 border-gray-200 dark:border-gray-700 
                        bg-white dark:bg-gray-800 
                        shadow-sm hover:shadow-md md:shadow-none 
                        mb-4 md:mb-0 
                        hover:bg-muted/50">
                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id"
                            class="flex md:table-cell items-center justify-between p-4 first:pt-4 last:pb-4 md:py-4 border-b last:border-b-0 border-gray-200 dark:border-gray-700 md:border-0">
                            <span class="text-xs font-medium tracking-wide text-muted-foreground md:hidden">
                                {{ cell.column.columnDef.header }}
                            </span>
                            <div
                                class="text-[15px] leading-normal md:text-sm text-right md:text-left flex-shrink-0 md:flex-shrink max-w-[60%] md:max-w-none">
                                <template v-if="cell.column.id === 'actions'">
                                    <div class="flex items-center gap-2 justify-end md:justify-start">
                                        <slot name="actions" :row="row.original"></slot>
                                    </div>
                                </template>
                                <template v-else-if="cell.column.id === 'index'">
                                    {{ (pagination.pageIndex * pagination.pageSize) + row.index + 1 }}
                                </template>
                                <template v-else>
                                    {{ cell.getValue() }}
                                </template>
                            </div>
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
:deep(.table) {
    @apply w-full caption-bottom text-sm;
}

:deep(thead tr) {
    @apply border-b transition-colors hover:bg-muted/50;
}

:deep(tbody tr) {
    @apply border-b transition-colors;
}

:deep(tfoot tr:last-child) {
    @apply border-0;
}

:deep(tbody tr:last-child) {
    @apply border-0;
}

@media (max-width: 768px) {
    :deep(tbody) {
        @apply grid gap-4 px-4;
    }

    :deep(tbody tr) {
        @apply overflow-hidden;
    }
}
</style>

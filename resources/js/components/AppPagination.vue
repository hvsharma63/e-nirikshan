<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination'

interface Props {
    currentPage?: number
    totalItems: number
    itemsPerPage?: number
    siblingCount?: number
    showEdges?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    currentPage: 1,
    itemsPerPage: 5, // Changed default to 5
    siblingCount: 1,
    showEdges: true
})

const emit = defineEmits(['update:currentPage'])

const handlePageChange = (page: number) => {
    emit('update:currentPage', page)
}

const getVisiblePages = (items: any[], currentPage: number) => {
    const pageItems = items.filter(item => item.type === 'page');
    const currentIndex = pageItems.findIndex(item => item.value === currentPage);
    const start = Math.max(0, Math.min(pageItems.length - 3, currentIndex - 1));
    return pageItems.slice(start, start + 3);
}
</script>

<template>
    <div class="flex flex-col items-center sm:flex-row sm:justify-between gap-4">
        <p class="text-sm text-muted-foreground whitespace-nowrap text-center">
            Showing {{ ((props.currentPage - 1) * props.itemsPerPage) + 1 }}
            to
            {{ Math.min(props.currentPage * props.itemsPerPage, totalItems) }}
            of {{ totalItems }} results
        </p>
        <div class="flex justify-center w-full sm:w-auto sm:justify-start">
            <Pagination v-slot="{ page }" :page="props.currentPage" :total="totalItems"
                :items-per-page="props.itemsPerPage" :sibling-count="props.siblingCount" :show-edges="props.showEdges"
                @update:page="handlePageChange">
                <PaginationList v-slot="{ items }" class="flex items-center gap-2">
                    <PaginationFirst v-if="props.showEdges" />
                    <PaginationPrev />

                    <template v-for="(item, index) in getVisiblePages(items, props.currentPage)" :key="index">
                        <PaginationListItem v-if="item.type === 'page'" :value="item.value" as-child>
                            <Button class="h-9 w-9 p-0" :variant="item.value === page ? 'default' : 'outline'">
                                {{ item.value }}
                            </Button>
                        </PaginationListItem>
                        <PaginationEllipsis v-else :index="index" />
                    </template>

                    <PaginationNext />
                    <PaginationLast v-if="props.showEdges" />
                </PaginationList>
            </Pagination>
        </div>
    </div>
</template>
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
    if (page !== props.currentPage) {
        emit('update:currentPage', page)
    }
}

</script>

<template>
    <div class="flex flex-col items-center sm:flex-row sm:justify-between gap-4">
        <p class="text-sm text-muted-foreground whitespace-nowrap text-center">
            Showing {{ ((currentPage - 1) * itemsPerPage) + 1 }}
            to
            {{ Math.min(currentPage * itemsPerPage, totalItems) }}
            of {{ totalItems }} results
        </p>
        <div class="flex justify-center w-full sm:w-auto sm:justify-start">
            <Pagination v-slot="{ page }" :total="totalItems" :items-per-page="itemsPerPage"
                :sibling-count="siblingCount" :show-edges="showEdges" :default-page="currentPage"
                @update:page="handlePageChange">
                <PaginationList v-slot="{ items }" class="flex items-center gap-2">
                    <PaginationFirst v-if="showEdges" />
                    <PaginationPrev />

                    <template v-for="(item, index) in items" :key="index">
                        <PaginationListItem v-if="item.type === 'page'" :value="item.value" as-child>
                            <Button class="h-9 w-9 p-0" :variant="item.value === page ? 'default' : 'outline'">
                                {{ item.value }}
                            </Button>
                        </PaginationListItem>
                        <PaginationEllipsis v-else :index="index" />
                    </template>

                    <PaginationNext />
                    <PaginationLast v-if="showEdges" />
                </PaginationList>
            </Pagination>
        </div>
    </div>
</template>
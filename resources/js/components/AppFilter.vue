<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { DateRangePicker } from '@/components/ui/date-range-picker';
import { Loader2, Filter } from 'lucide-vue-next';
import VSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion';

export interface FilterConfig {
    filters: Array<{
        id: string;
        type: 'search' | 'daterange' | 'multiselect';
        label: string;
        key: string | null;
        keys?: { from: string; to: string } | null;
        placeholder?: string | null;
        options?: Array<{ label: string; value: string | number }> | null;
    }>;
}

interface DateRange {
    from: string;
    to: string;
}

const props = defineProps({
    filterConfig: {
        type: Object as () => FilterConfig,
        required: true
    },
    loading: { type: Boolean, default: false }
});

const emit = defineEmits(['filter']);

const formState = reactive({
    values: {
        search: {} as Record<string, string>,
        daterange: {} as Record<string, DateRange | null>,
        multiselect: {} as Record<string, Array<any>>
    },

    init() {
        props.filterConfig.filters.forEach(filter => {
            if (filter.type === 'search') {
                this.values.search[filter.id] = '';
            } else if (filter.type === 'daterange') {
                this.values.daterange[filter.id] = null;
            } else if (filter.type === 'multiselect') {
                this.values.multiselect[filter.id] = [];
            }
        });
    },

    reset() {
        Object.keys(this.values.search).forEach(key => this.values.search[key] = '');
        Object.keys(this.values.daterange).forEach(key => this.values.daterange[key] = null);
        Object.keys(this.values.multiselect).forEach(key => this.values.multiselect[key] = []);
    },

    getFilters() {
        const filters = {};
        props.filterConfig.filters.forEach(filter => {
            if (filter.type === 'search') {
                const value = this.values.search[filter.id];
                if (value?.trim()) filters[filter.key] = value;
            } else if (filter.type === 'daterange') {
                const value = this.values.daterange[filter.id];
                if (value?.from && value?.to) {
                    filters[filter.keys.from] = value.from;
                    filters[filter.keys.to] = value.to;
                }
            } else if (filter.type === 'multiselect') {
                const value = this.values.multiselect[filter.id];
                if (value?.length > 0) filters[filter.key] = value.map(v => v.value);
            }
        });
        return filters;
    }
});

// Initialize form state
formState.init();

const handleSubmit = () => emit('filter', formState.getFilters());
const clearFilters = () => {
    formState.reset();
    handleSubmit();
};

</script>

<template>
    <div class="relative rounded-lg md:border md:bg-white md:dark:bg-gray-800">
        <Accordion type="single" collapsible class="w-full">
            <AccordionItem value="filters" class="border-none">
                <AccordionTrigger class="flex items-center gap-2 px-4 py-3 text-sm hover:no-underline">
                    <div class="flex items-center gap-2">
                        <Filter class="h-4 w-4" />
                        <span class="font-medium">Filters</span>
                    </div>
                </AccordionTrigger>
                <AccordionContent class="overflow-visible">
                    <div class="p-4 border-t dark:border-gray-700 overflow-visible">
                        <form @submit.prevent="handleSubmit" class="space-y-6">
                            <!-- Updated grid layout with mobile stacking -->
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-x-4 gap-y-6">
                                <template v-for="filter in filterConfig.filters" :key="filter.id">
                                    <!-- Make all filter items use flex-col -->
                                    <div class="flex flex-col space-y-1.5">
                                        <label :for="filter.id"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ filter.label }}
                                        </label>

                                        <!-- Search Input -->
                                        <template v-if="filter.type === 'search'">
                                            <Input :id="filter.id" v-model="formState.values.search[filter.id]"
                                                :placeholder="filter.placeholder"
                                                class="w-full bg-white dark:bg-gray-800 h-9" />
                                        </template>

                                        <!-- Date Range -->
                                        <template v-else-if="filter.type === 'daterange'">
                                            <div class="flex-col w-full">
                                                <DateRangePicker :model-value="formState.values.daterange[filter.id]"
                                                    @update:model-value="v => formState.values.daterange[filter.id] = v"
                                                    class="w-full flex flex-col [&>button]:h-9 [&>button]:w-full [&>button]:justify-start [&>button]:text-left [&>button]:bg-white [&>button]:dark:bg-gray-800" />
                                            </div>
                                        </template>

                                        <!-- Multiselect Input -->
                                        <template v-else-if="filter.type === 'multiselect'">
                                            <v-select v-model="formState.values.multiselect[filter.id]"
                                                :options="filter.options" multiple :placeholder="filter.placeholder"
                                                append-to-body :class="[
                                                    'vue-select-custom',
                                                ]" />
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Submit Button - Reduced spacing -->
                            <div class="flex justify-end gap-3 pt-3 mt-1 border-t dark:border-gray-700">
                                <Button type="button" size="sm" variant="outline" @click="clearFilters"
                                    :disabled="loading">
                                    Clear
                                </Button>
                                <Button type="submit" size="sm" class="min-w-[100px]" :disabled="loading">
                                    <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
                                    <span>Apply Filters</span>
                                </Button>
                            </div>
                        </form>
                    </div>
                </AccordionContent>
            </AccordionItem>
        </Accordion>
    </div>
</template>

<style scoped>
:deep(.date-range-picker) {
    width: 100%;
}

/* Mobile Optimization */
@media (max-width: 640px) {
    :deep([role="button"]) {
        @apply text-left whitespace-normal;
    }

    :deep(.date-range-picker) {
        @apply flex flex-col;
    }
}

:deep(.v-select) {
    @apply border-input;
}

:deep(.vs__dropdown-toggle) {
    @apply border-input bg-transparent min-h-[36px];
}

/* Global styles for v-select dropdown when appended to body */
:global(.vs__dropdown-menu) {
    z-index: 100;
    margin-top: 4px;
    @apply bg-white border border-gray-200 rounded-md shadow-lg max-w-[300px];
}

:global(.vs__dropdown-option) {
    @apply px-3 py-2 whitespace-normal break-words text-gray-900;
}

:global(.vs__dropdown-option--highlight) {
    @apply bg-gray-100 text-gray-900;
}

:global(.vs--dark-theme .vs__dropdown-menu) {
    @apply bg-gray-800 border-gray-700;
}

:global(.vs--dark-theme .vs__dropdown-option) {
    @apply text-gray-200;
}

:global(.vs--dark-theme .vs__dropdown-option--highlight) {
    @apply bg-gray-700 text-white;
}

/* Fix for accordion overflow issues */
:deep(.accordion-content) {
    overflow: visible !important;
}

:deep(.accordion-content > div) {
    overflow: visible !important;
}
</style>

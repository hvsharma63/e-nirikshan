<script setup lang="ts">
import { DateRange } from 'reka-ui'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { RangeCalendar } from '@/components/ui/range-calendar'
import {
    CalendarDate,
    DateFormatter,
    getLocalTimeZone,
} from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { ref, watch } from 'vue'
import moment from 'moment';

const props = defineProps<{
    modelValue: { from: string; to: string } | null
}>()

const emit = defineEmits(['update:modelValue'])

const df = new DateFormatter('en-US', {
    dateStyle: 'medium',
})

// Use ref instead of computed
const value = ref<DateRange | null>(null)

// Update value when props change
watch(() => props.modelValue, (newValue) => {
    if (!newValue) {
        value.value = null;
        return;
    }

    value.value = {
        start: new CalendarDate(
            new Date(newValue.from).getFullYear(),
            new Date(newValue.from).getMonth() + 1,
            new Date(newValue.from).getDate()
        ),
        end: new CalendarDate(
            new Date(newValue.to).getFullYear(),
            new Date(newValue.to).getMonth() + 1,
            new Date(newValue.to).getDate()
        )
    }
}, { immediate: true })

const getDate = (date: DateRange) => {
    if (!date?.start || !date?.end) return;

    const from = moment(date.start.toDate(getLocalTimeZone())).format('YYYY-MM-DD');
    const to = moment(date.end.toDate(getLocalTimeZone())).format('YYYY-MM-DD');

    emit('update:modelValue', { from, to })
}

</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" :class="cn(
                'w-[280px] justify-start text-left font-normal',
                !value && 'text-muted-foreground',
            )">
                <CalendarIcon class="mr-2 h-4 w-4" />
                <template v-if="value?.start">
                    <template v-if="value?.end">
                        {{ df.format(value.start.toDate(getLocalTimeZone())) }} - {{
                            df.format(value.end.toDate(getLocalTimeZone())) }}
                    </template>

                    <template v-else>
                        {{ df.format(value.start.toDate(getLocalTimeZone())) }}
                    </template>
                </template>
                <template v-else>
                    Pick a date
                </template>
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <RangeCalendar v-model="value as DateRange" initial-focus :number-of-months="2"
                @update:model-value="getDate($event)" />
        </PopoverContent>
    </Popover>
</template>
<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCaption from '@/components/ui/table/TableCaption.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ListInspections, SharedData, type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Calendar,
    Eye,
    LocateFixed,
    UserRound,
    CheckCircle2,
    Clock,
    AlertCircle,
    Hash,
    AlertTriangle,
    CalendarClock,
    Timer
} from 'lucide-vue-next';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Inspections',
        href: '/inspections/create',
    },
];
const page = usePage<SharedData>();

const inspections: ListInspections[] = (page.props.inspections?.data as ListInspections[]) ?? [];

// Modify status badge styling utility
const getStatusColor = (status: string) => {
    switch (status.toLowerCase()) {
        case 'completed':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
        case 'progress':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
    }
};

// Modify icon utility for status
const getStatusIcon = (status: string) => {
    switch (status.toLowerCase()) {
        case 'completed':
            return CheckCircle2;
        case 'pending':
            return AlertCircle;
        case 'progress':
            return Clock;
        default:
            return AlertCircle;
    }
};

// Add a utility function for responsive layout
const isMobile = ref(window.innerWidth < 768);
window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth < 768;
});

// Add view handler method
const viewInspection = (id: number) => {
    // You can use Inertia Link or router to navigate
    window.location.href = `/inspections/${id}`;
};

// Add these utility functions
const getHeaderIcon = (header: string) => {
    const icons = {
        'Sr. No.': Hash,
        'Location': LocateFixed,
        'Total Deficiencies': AlertTriangle,
        'Date': Calendar,
        'Time': Timer,
        'Period': CalendarClock,
        'Status': Clock,
        'Actions': Eye
    };
    return icons[header] || AlertCircle;
};

const getCellTextClass = (field: string) => {
    return field === 'location'
        ? 'font-semibold text-gray-900 dark:text-white'
        : 'text-gray-900 dark:text-white';
};

onMounted(() => {
    // Add any additional setup if needed
    console.log(page.props.inspections);
});
</script>

<template>

    <Head title="My Inspections" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">My Inspections</h2>

            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <Table class="w-full">
                    <TableCaption v-if="!inspections?.length" class="py-8">
                        <div class="flex flex-col items-center justify-center space-y-4 text-center">
                            <p class="text-lg font-medium text-gray-600 dark:text-gray-300">No inspections found</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">When you create inspections, they will
                                appear here.</p>
                        </div>
                    </TableCaption>

                    <TableHeader class="hidden bg-gray-50 dark:bg-gray-800/90 md:table-header-group">
                        <TableRow class="border-b border-gray-200 dark:border-gray-700">
                            <TableHead
                                v-for="header in ['Sr. No.', 'Location', 'Total Deficiencies', 'Date', 'Time', 'Period', 'Status', 'Actions']"
                                :key="header"
                                class="py-4 px-6 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                :class="{ 'text-right': header === 'Actions' }">
                                <div class="flex items-center gap-2" :class="{ 'justify-end': header === 'Actions' }">
                                    <component :is="getHeaderIcon(header)" class="h-4 w-4 text-gray-500" />
                                    {{ header }}
                                </div>
                            </TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody v-if="inspections.length > 0" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <TableRow v-for="inspection in inspections" :key="inspection.id" class="group block transition-colors md:table-row
                                       hover:bg-gray-50/80 dark:hover:bg-gray-800/80
                                       md:hover:shadow-sm">
                            <TableCell v-for="(field, key) in {
                                id: 'Sr. No.',
                                location: 'Location',
                                deficiencies_count: 'Total Deficiencies',
                                date: 'Date',
                                time: 'Time',
                                day_period: 'Period'
                            }" :key="key" class="flex items-center justify-between p-4 md:table-cell md:py-4 md:px-6">
                                <span class="flex items-center gap-2 font-medium text-gray-500 md:hidden">
                                    <component :is="getHeaderIcon(field)" class="h-4 w-4" />
                                    {{ field }}
                                </span>
                                <span :class="getCellTextClass(key)">{{ inspection[key] }}</span>
                            </TableCell>

                            <TableCell class="flex items-center justify-between p-4 md:table-cell md:py-4 md:px-6">
                                <span class="flex items-center gap-2 font-medium text-gray-500 md:hidden">
                                    <Clock class="h-4 w-4" /> Status
                                </span>
                                <span
                                    class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-medium shadow-sm transition-colors"
                                    :class="getStatusColor(inspection?.status)">
                                    <component :is="getStatusIcon(inspection?.status)" class="h-3.5 w-3.5" />
                                    {{ inspection?.status }}
                                </span>
                            </TableCell>

                            <TableCell
                                class="flex items-center justify-between p-4 md:table-cell md:py-4 md:px-6 md:text-right">
                                <span class="flex items-center gap-2 font-medium text-gray-500 md:hidden">
                                    <Eye class="h-4 w-4" /> Actions
                                </span>
                                <Link :href="`/inspections/${inspection.id}`"
                                    class="inline-flex items-center justify-center gap-2 rounded-md bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">
                                <Eye class="h-4 w-4" />
                                <span class="md:hidden">View Details</span>
                                </Link>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
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

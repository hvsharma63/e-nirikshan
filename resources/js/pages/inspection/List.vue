<script setup lang="ts">
import { Button } from '@/components/ui/button';
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
import AppDataTable from '@/components/AppDataTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Inspections',
        href: '/inspections',
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
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">My Inspections</h2>
                <Link href="/inspections/create">
                <Button class="gap-2">
                    <Calendar class="h-4 w-4" />
                    Diarise Inspection
                </Button>
                </Link>
            </div>

            <AppDataTable apiUrl="/inspections/list" :columns="[
                { accessorKey: 'id', header: 'ID' },
                { accessorKey: 'location', header: 'Location' },
                { accessorKey: 'deficiencies_count', header: 'Total Deficiencies' },
                { accessorKey: 'date', header: 'Date' },
                { accessorKey: 'time', header: 'Time' },
                { accessorKey: 'day_period', header: 'Period' },
                { accessorKey: 'status', header: 'Status' },
            ]">
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

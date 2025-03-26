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
import { Inspection, type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Calendar, Eye, LocateFixed, UserRound, CheckCircle2, Clock, AlertCircle } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Received Deficiencies',
        href: '/deficiencies',
    },
];

const deficiencies = [
    {
        id: 1,
        location: 'LC-250',
        type: 'Safety Violation',
        note: 'Gate lodge requires immediate maintenance',
        date: '2023-11-15',
        inspected_by: 'Arya Kirnendu',
        is_attended: true,
        action_date: '2023-11-20'
    },
    {
        id: 2,
        location: 'LC-342',
        type: 'Equipment Malfunction',
        note: 'Lifting barrier not working properly',
        date: '2023-11-14',
        inspected_by: 'Raj Mehta',
        is_attended: false,
        action_date: null
    },
];

const getAttendanceStatus = (isAttended: boolean) => {
    return isAttended
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
};

const getAttendanceIcon = (isAttended: boolean) => {
    return isAttended ? CheckCircle2 : Clock;
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
</script>

<template>

    <Head title="Deficiencies" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-6 rounded-xl bg-white p-4 shadow-sm dark:bg-gray-800 sm:p-8">
            <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Deficiencies pertaining to me</h2>

            <div class="overflow-x-auto">
                <Table class="min-w-full">
                    <TableHeader class="hidden md:table-header-group">
                        <TableRow>
                            <TableHead class="py-4">Sr. No.</TableHead>
                            <TableHead class="w-[100px] py-4">Location</TableHead>
                            <TableHead class="py-4">Type</TableHead>
                            <TableHead class="py-4">Note</TableHead>
                            <TableHead class="py-4">
                                <div class="flex items-center gap-2">
                                    <Calendar class="h-4 w-4" /> Date
                                </div>
                            </TableHead>
                            <TableHead class="py-4">
                                <div class="flex items-center gap-2">
                                    <UserRound class="h-4 w-4" /> Inspected By
                                </div>
                            </TableHead>
                            <TableHead class="py-4">Status</TableHead>
                            <TableHead class="py-4">Action Date</TableHead>
                            <TableHead class="w-[100px] py-4 text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="deficiency in deficiencies" :key="deficiency.id" class="block border-b border-gray-200 md:table-row md:border-0
                            even:bg-gray-50/50 dark:even:bg-gray-800/50 
                            md:even:bg-transparent
                            hover:bg-gray-100 dark:hover:bg-gray-700/50 
                            transition-colors
                            mb-3 md:mb-0
                            rounded-lg md:rounded-none
                            shadow-sm md:shadow-none
                            p-4 md:p-0">
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Sr. No.</span>
                                <span class="font-semibold">{{ deficiency.id }}</span>
                            </TableCell>
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Location</span>
                                <span class="font-semibold">{{ deficiency.location }}</span>
                            </TableCell>
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Type</span>
                                <span>{{ deficiency.type }}</span>
                            </TableCell>
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Note</span>
                                <span>{{ deficiency.note }}</span>
                            </TableCell>
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Date</span>
                                <span>{{ deficiency.date }}</span>
                            </TableCell>
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Inspected By</span>
                                <span>{{ deficiency.inspected_by }}</span>
                            </TableCell>
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Status</span>
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium"
                                    :class="getAttendanceStatus(deficiency.is_attended)">
                                    <component :is="getAttendanceIcon(deficiency.is_attended)" class="h-3.5 w-3.5" />
                                    {{ deficiency.is_attended ? 'Attended' : 'Pending' }}
                                </span>
                            </TableCell>
                            <TableCell class="flex items-center justify-between py-2 md:table-cell md:py-3">
                                <span class="font-medium text-gray-500 md:hidden">Action Date</span>
                                <span>{{ deficiency.action_date || '-' }}</span>
                            </TableCell>
                            <TableCell
                                class="flex items-center justify-between py-2 md:table-cell md:py-3 md:text-right">
                                <span class="font-medium text-gray-500 md:hidden">Actions</span>
                                <Link :href="`/deficiencies/view`"
                                    class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 dark:ring-offset-slate-950 dark:focus-visible:ring-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 h-9 px-4 py-2">
                                <Eye class="h-4 w-4" />
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
    :deep(td::before) {
        content: attr(data-label);
        font-weight: 500;
    }
}
</style>

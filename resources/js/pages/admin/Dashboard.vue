<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ScrollText, NotebookTextIcon, AlertCircle, CheckCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { Skeleton } from '@/components/ui/skeleton'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

import { usePermissionStore } from '@/stores/permission';
import apiService from '@/services/apiService';
import { onMounted } from 'vue';
const store = usePermissionStore();

const timePeriod = ref('overall')
const timeRanges = [
    { value: 'overall', label: 'Overall' },
    { value: 'today', label: 'Today' },
    { value: 'this_week', label: 'This Week' },
    { value: 'this_month', label: 'This Month' },
    { value: 'this_financial_year', label: 'This FY' },
]

// Stats data
const stats = ref({
    inspections_total_count: 0,
    deficiencies_total_count: 0,
    deficiencies_pending_count: 0,
    deficiencies_attended_count: 0
})

const loading = ref(false)

// Fetch stats data based on the selected time period
const fetchStats = async () => {
    loading.value = true;
    try {
        const response = await apiService.get(route('dashboard.stats'), {
            params: {
                time_range: timePeriod.value,
            },
        });
        stats.value = response;
    } catch (error) {
        console.error('Error fetching stats:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    console.log(store.permissions)
    fetchStats();
})

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="store.hasPermission('view-dashboard-stats')"
            class="flex h-full flex-1 flex-col space-y-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
            <!-- Header with Time Range Selector -->
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard</h2>
                <Select v-model="timePeriod" @update:modelValue="fetchStats">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Select time range" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Time Range</SelectLabel>
                            <SelectItem v-for="range in timeRanges" :key="range.value" :value="range.value">
                                {{ range.label }}
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-primary/20 shadow-sm transition-all hover:shadow-md dark:border-primary/20">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-base font-semibold text-gray-900 dark:text-white">
                            Total Inspections
                        </CardTitle>
                        <ScrollText class="h-6 w-6 text-primary" />
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-1">
                            <div v-if="loading">
                                <Skeleton class="h-9 w-16" />
                            </div>
                            <p v-else class="text-3xl font-bold text-primary">{{ stats.inspections_total_count }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total inspections conducted</p>
                        </div>
                    </CardContent>
                </Card>

                <Card
                    class="border border-yellow-500/20 shadow-sm transition-all hover:shadow-md dark:border-yellow-500/20">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-base font-semibold text-gray-900 dark:text-white">
                            Total Deficiencies
                        </CardTitle>
                        <NotebookTextIcon class="h-6 w-6 text-yellow-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-1">
                            <div v-if="loading">
                                <Skeleton class="h-9 w-16" />
                            </div>
                            <p v-else class="text-3xl font-bold text-yellow-500">{{ stats.deficiencies_total_count }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total deficiencies identified</p>
                        </div>
                    </CardContent>
                </Card>

                <Card
                    class="border border-destructive/20 shadow-sm transition-all hover:shadow-md dark:border-destructive/20">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-base font-semibold text-gray-900 dark:text-white">
                            Pending Deficiencies
                        </CardTitle>
                        <AlertCircle class="h-6 w-6 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-1">
                            <div v-if="loading">
                                <Skeleton class="h-9 w-16" />
                            </div>
                            <p v-else class="text-3xl font-bold text-destructive">{{ stats.deficiencies_pending_count }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Deficiencies to be resolved</p>
                        </div>
                    </CardContent>
                </Card>

                <Card
                    class="border border-green-500/20 shadow-sm transition-all hover:shadow-md dark:border-green-500/20">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-base font-semibold text-gray-900 dark:text-white">
                            Resolved Deficiencies
                        </CardTitle>
                        <CheckCircle class="h-6 w-6 text-green-600 dark:text-green-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-1">
                            <div v-if="loading">
                                <Skeleton class="h-9 w-16" />
                            </div>
                            <p v-else class="text-3xl font-bold text-green-600 dark:text-green-500">{{
                                stats.deficiencies_attended_count }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Successfully resolved issues</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

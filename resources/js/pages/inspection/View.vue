<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import {
    AlertCircle,
    CheckCircle,
    Clock,
    Mail,
    MapPin,
    Calendar,
    Sun,
    User,
    Building2,
    ClipboardList
} from 'lucide-vue-next';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import type { SharedData, ViewInspection, ItemDeficiency } from '@/types';
import apiService from '@/services/apiService';
import { toast } from '@/components/ui/toast';

const breadcrumbs = [
    {
        title: 'My Inspections',
        href: '/inspections',
    },
    { title: 'Inspection Details', href: '/inspections/view' }
];

const page = usePage<SharedData>();

const inspection: ViewInspection = (page.props.inspection as { data: ViewInspection }).data;

const sendReminder = async (deficiencyId: number) => {
    try {
        await apiService.post(`/deficiencies/${deficiencyId}/remind`, {});
        toast({
            title: "Reminder Sent Successfully!",
            variant: "success",
        })
    } catch (error) {
        console.error('Failed to send reminder:', error);
    }
};

const getStatusColor = (deficiency: ItemDeficiency) => {
    if (deficiency.is_attended) return 'text-green-600';
    if (deficiency.is_seen) return 'text-yellow-600';
    if (deficiency.is_pending) return 'text-red-600';
    return 'text-gray-600'; // fallback
};

const getStatusIcon = (deficiency: ItemDeficiency) => {
    if (deficiency.is_attended) return CheckCircle;
    if (deficiency.is_seen) return Clock;
    if (deficiency.is_pending) return AlertCircle;
    return AlertCircle; // fallback
};

const getStatusStep = (deficiency: ItemDeficiency) => {
    if (deficiency.is_attended) return 3;
    if (deficiency.is_seen) return 2;
    if (deficiency.is_pending) return 1;
    return 0;
};

const checkIsMobile = () => window.innerWidth < 768;
const isMobile = ref(checkIsMobile());

const viewNote = () => {
    window.open(route('inspections.view-note', inspection.id), '_blank');
};

const downloadNote = () => {
    window.open(route('inspections.download-note', inspection.id), '_blank');
};

onMounted(() => {
    window.addEventListener('resize', () => {
        isMobile.value = checkIsMobile();
    });
    console.log(page.props.inspection);
});
</script>

<template>

    <Head title="Inspection Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
            <!-- Inspection Details Card -->
            <Card class="shadow-md mb-4 sm:mb-6">
                <section class="p-4 sm:p-8 bg-white rounded">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3">
                        <h2 class="text-xl sm:text-2xl font-bold">Inspection Details</h2>
                        <span :class="{
                            'px-3 py-1 rounded-full text-sm font-medium self-start sm:self-auto': true,
                            'bg-yellow-100 text-yellow-800': inspection.status === 'Progress',
                            'bg-green-100 text-green-800': inspection.status === 'Completed',
                            'bg-red-100 text-red-800': inspection.status === 'Pending'
                        }">
                            {{ inspection.status }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <MapPin class="w-4 h-4" />Location
                                </span>
                                <span class="text-lg">{{ inspection.location }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <Calendar class="w-4 h-4" />Date & Time
                                </span>
                                <span class="text-lg">{{ inspection.datetime }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <Sun class="w-4 h-4" />Period
                                </span>
                                <span class="text-lg">{{ inspection.day_period }}</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <User class="w-4 h-4" />Attended By
                                </span>
                                <span class="text-lg">{{ inspection.attended_by }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row justify-end gap-2">
                        <Button @click="viewNote" class="inline-flex items-center justify-center px-4 py-2 text-sm">
                            <ClipboardList class="w-4 h-4 mr-2" />
                            View Inspection Note
                        </Button>
                        <Button @click="downloadNote" class="inline-flex items-center justify-center px-4 py-2 text-sm">
                            <ClipboardList class="w-4 h-4 mr-2" />
                            Download Inspection Note
                        </Button>
                    </div>
                </section>
            </Card>

            <!-- Deficiencies Section -->
            <Card class="shadow-md">
                <section class="p-4 sm:p-8 bg-white rounded">
                    <div class="flex flex-col sm:flex-row justify-between mb-6 gap-2">
                        <h2 class="text-xl sm:text-2xl font-bold">Deficiencies / Observations / Abnormalities</h2>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm w-fit">
                            {{ inspection.deficiencies.length }} found
                        </span>
                    </div>

                    <div v-if="inspection.deficiencies.length === 0" class="text-center py-8 text-gray-500">
                        No deficiencies recorded
                    </div>

                    <Accordion type="single" collapsible class="space-y-4">
                        <AccordionItem v-for="deficiency in inspection.deficiencies" :key="deficiency.id"
                            :value="deficiency.id.toString()" class="border rounded-lg px-0">
                            <AccordionTrigger class="px-4 sm:px-6 hover:no-underline text-left w-full">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                                    <div class="flex items-center gap-3 flex-1">
                                        <component :is="getStatusIcon(deficiency)"
                                            :class="['w-5 h-5 flex-shrink-0', getStatusColor(deficiency)]" />
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-medium">{{ deficiency.pertains_to.name }}</h3>
                                            <span class="text-sm text-gray-500 block">{{
                                                deficiency.pertains_to.designation }}</span>
                                            <div class="text-sm text-gray-500 mt-1 sm:hidden">
                                                Action Date: {{ deficiency.action_date }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="hidden sm:block text-sm text-gray-500 whitespace-nowrap">
                                        Action Date: {{ deficiency.action_date }}
                                    </span>
                                </div>
                            </AccordionTrigger>
                            <AccordionContent class="px-4 sm:px-6 pt-4">
                                <div class="space-y-4 sm:space-y-6 border-t pt-4">
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                                                <AlertCircle class="w-4 h-4" />Inspector's Observation
                                            </h4>
                                            <p class="text-gray-700">
                                                {{ deficiency.note }}
                                            </p>
                                            <div class="mt-3 text-sm text-gray-500">
                                                Reported on {{ deficiency.reported_on }}
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                                                <User class="w-4 h-4" />Pertaining Officer's Response
                                            </h4>

                                            <!-- Officer Info & Action Date -->
                                            <div class="flex flex-col gap-4 p-3 bg-white rounded-md mb-4">
                                                <div class="flex items-start gap-3">
                                                    <User class="w-4 h-4 mt-1 text-gray-500" />
                                                    <div>
                                                        <span class="font-medium text-gray-900">{{
                                                            deficiency.pertains_to.name }}</span>
                                                        <span class="text-sm text-gray-500 block">{{
                                                            deficiency.pertains_to.designation }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <Calendar class="w-4 h-4 mt-1 text-gray-500" />
                                                    <div>
                                                        <span class="text-sm text-gray-500">Action Date</span>
                                                        <span class="font-medium text-gray-900 block">{{
                                                            deficiency.action_date }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Response Text -->
                                            <div class="bg-white p-3 rounded-md mb-6">
                                                <h5 class="text-sm text-gray-500 mb-2">Response</h5>
                                                <p class="text-gray-900">
                                                    {{ deficiency.comment_by_pertaining_officer || 'No response yet' }}
                                                </p>
                                            </div>

                                            <!-- Status Timeline (unchanged) -->
                                            <div class="bg-white p-3 rounded-md mb-6">
                                                <h5 class="text-sm text-gray-500 mb-2">Status Timeline</h5>

                                                <div class="mt-6 ml-3 relative">
                                                    <div
                                                        class="absolute left-[1.3rem] top-2 h-[calc(100%-16px)] w-0.5 bg-gray-200">
                                                    </div>
                                                    <div class="space-y-6 relative">
                                                        <div class="flex items-center gap-3">
                                                            <div class="flex items-center justify-center w-7 h-7 rounded-full border-2 bg-white"
                                                                :class="[
                                                                    getStatusStep(deficiency) >= 1
                                                                        ? 'border-red-500 text-red-500'
                                                                        : 'border-gray-300 text-gray-300'
                                                                ]">
                                                                <AlertCircle class="w-4 h-4" />
                                                            </div>
                                                            <div :class="[
                                                                'flex flex-col',
                                                                getStatusStep(deficiency) >= 1 ? 'text-gray-900' : 'text-gray-400'
                                                            ]">
                                                                <span class="font-medium">Pending</span>
                                                                <span class="text-sm">Issue reported</span>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-3">
                                                            <div class="flex items-center justify-center w-7 h-7 rounded-full border-2 bg-white"
                                                                :class="[
                                                                    getStatusStep(deficiency) >= 2
                                                                        ? 'border-yellow-500 text-yellow-500'
                                                                        : 'border-gray-300 text-gray-300'
                                                                ]">
                                                                <Clock class="w-4 h-4" />
                                                            </div>
                                                            <div :class="[
                                                                'flex flex-col',
                                                                getStatusStep(deficiency) >= 2 ? 'text-gray-900' : 'text-gray-400'
                                                            ]">
                                                                <span class="font-medium">Seen</span>
                                                                <span class="text-sm">Under review</span>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-3">
                                                            <div class="flex items-center justify-center w-7 h-7 rounded-full border-2 bg-white"
                                                                :class="[
                                                                    getStatusStep(deficiency) >= 3
                                                                        ? 'border-green-500 text-green-500'
                                                                        : 'border-gray-300 text-gray-300'
                                                                ]">
                                                                <CheckCircle class="w-4 h-4" />
                                                            </div>
                                                            <div :class="[
                                                                'flex flex-col',
                                                                getStatusStep(deficiency) >= 3 ? 'text-gray-900' : 'text-gray-400'
                                                            ]">
                                                                <span class="font-medium">Attended</span>
                                                                <span class="text-sm">Issue resolved</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="!deficiency.is_attended" class="flex sm:justify-end">
                                        <Button variant="outline" @click="sendReminder(deficiency.id)"
                                            class="w-full sm:w-auto">
                                            <Mail class="w-4 h-4 mr-2" />
                                            Send Reminder
                                        </Button>
                                    </div>
                                </div>
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                </section>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
.accordion-trigger[data-state='open'] .chevron {
    transform: rotate(180deg);
}
</style>

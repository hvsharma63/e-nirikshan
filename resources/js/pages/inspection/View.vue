<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import { AlertCircle, CheckCircle, Clock, Mail, ChevronDown } from 'lucide-vue-next';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import type { Inspection } from '@/types';

const breadcrumbs = [
    { title: 'Inspection Details', href: '/inspections/view' }
];

// Dummy data for development
const inspection: Inspection = {
    id: 1,
    location: 'Central Office Building',
    date: '2023-10-15',
    inspection_type: 'Safety Inspection',
    address: '123 Main Street, City Center',
    attended_by: 'John Inspector',
    status: 'In Progress',
    deficiencies: [
        {
            id: 1,
            inspection_id: 1,
            pertains_to: 'Maintenance Officer',
            is_viewed: true,
            is_attended: false,
            comment_by_inspector: 'Fire extinguishers need immediate replacement in Block A',
            comment_by_pertaining_officer: '',
            action_date: '2023-10-30'
        },
        {
            id: 2,
            inspection_id: 1,
            pertains_to: 'Security Officer',
            is_viewed: true,
            is_attended: true,
            comment_by_inspector: 'Emergency exit signs not properly illuminated',
            comment_by_pertaining_officer: 'Replaced all emergency exit signs with new LED ones',
            action_date: '2023-10-20'
        }
    ]
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const sendReminder = async (deficiencyId: number) => {
    try {
        // In real implementation, use router.post or axios
        await router.post(`/deficiencies/${deficiencyId}/remind`);
        alert('Reminder sent successfully!');
    } catch (error) {
        console.error('Failed to send reminder:', error);
    }
};

const getStatusColor = (deficiency: typeof inspection.deficiencies[0]) => {
    if (deficiency.is_attended) return 'text-green-600';
    if (deficiency.is_viewed) return 'text-yellow-600';
    return 'text-red-600';
};

const getStatusIcon = (deficiency: typeof inspection.deficiencies[0]) => {
    if (deficiency.is_attended) return CheckCircle;
    if (deficiency.is_viewed) return Clock;
    return AlertCircle;
};

const checkIsMobile = () => window.innerWidth < 768;
const isMobile = ref(checkIsMobile());

onMounted(() => {
    window.addEventListener('resize', () => {
        isMobile.value = checkIsMobile();
    });
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
                            'bg-yellow-100 text-yellow-800': inspection.status === 'In Progress',
                            'bg-green-100 text-green-800': inspection.status === 'Completed',
                            'bg-red-100 text-red-800': inspection.status === 'Pending'
                        }">
                            {{ inspection.status }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Location</span>
                                <span class="text-lg">{{ inspection.location }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Date</span>
                                <span class="text-lg">{{ formatDate(inspection.date) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Type</span>
                                <span class="text-lg">{{ inspection.inspection_type }}</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Address</span>
                                <span class="text-lg">{{ inspection.address }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Attended By</span>
                                <span class="text-lg">{{ inspection.attended_by }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </Card>

            <!-- Deficiencies Section -->
            <Card class="shadow-md">
                <section class="p-4 sm:p-8 bg-white rounded">
                    <div class="flex flex-col sm:flex-row justify-between mb-6 gap-2">
                        <h2 class="text-xl sm:text-2xl font-bold">Deficiencies</h2>
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
                                    <div class="flex items-start gap-3 flex-1">
                                        <component :is="getStatusIcon(deficiency)"
                                            :class="['w-5 h-5 mt-1 sm:mt-0 flex-shrink-0', getStatusColor(deficiency)]" />
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-medium">{{ deficiency.pertains_to }}</h3>
                                            <p class="text-sm text-gray-500 line-clamp-2 sm:line-clamp-1">
                                                {{ deficiency.comment_by_inspector }}
                                            </p>
                                            <div class="text-sm text-gray-500 mt-1 sm:hidden">
                                                Due: {{ formatDate(deficiency.action_date || '') }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="hidden sm:block text-sm text-gray-500 whitespace-nowrap">
                                        Due: {{ formatDate(deficiency.action_date || '') }}
                                    </span>
                                </div>
                            </AccordionTrigger>
                            <AccordionContent class="px-4 sm:px-6 pt-4">
                                <div class="space-y-4 sm:space-y-6 border-t pt-4">
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-3">Inspector's Observation</h4>
                                            <p class="text-gray-700">
                                                {{ deficiency.comment_by_inspector }}
                                            </p>
                                            <div class="mt-3 text-sm text-gray-500">
                                                Reported on {{ formatDate(inspection.date) }}
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-3">Officer's Response</h4>
                                            <p class="text-gray-700">
                                                {{ deficiency.comment_by_pertaining_officer || 'No response yet' }}
                                            </p>
                                            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" :checked="deficiency.is_viewed" disabled
                                                        class="rounded border-gray-300">
                                                    <span class="text-sm text-gray-600">Viewed</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" :checked="deficiency.is_attended" disabled
                                                        class="rounded border-gray-300">
                                                    <span class="text-sm text-gray-600">Attended</span>
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

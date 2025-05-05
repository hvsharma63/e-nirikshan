<script setup lang="ts">
import { ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { AlertCircle, CheckCircle, Clock, MapPin, User, Calendar, MessageSquare, CalendarCheck, ClipboardList } from 'lucide-vue-next';
import { SharedData, ViewDeficiency } from '@/types';
import { onMounted } from 'vue';

const breadcrumbs = [
    { title: 'All Deficiencies', href: '/admin/deficiencies' },
    { title: 'Deficiency Details', href: '/admin/deficiencies' },
];

const page = usePage<SharedData>();
const deficiency: ViewDeficiency = (page.props.deficiency as { data: ViewDeficiency }).data;

// Remove form-related code and keep only view functions
const viewNote = () => {
    window.open(route('admin.notes.view', {
        'userId': deficiency.attended_by.id,
        'inspectionId': deficiency.inspection_id,
    }), '_blank');
};

const downloadNote = () => {
    window.open(route('admin.notes.download', {
        'userId': deficiency.attended_by.id,
        'inspectionId': deficiency.inspection_id,
    }), '_blank');
};

const getStatusColor = (deficiency: ViewDeficiency) => {
    if (deficiency.inspection_status === 'Completed') return 'text-green-600';
    if (deficiency.inspection_status === 'Progress') return 'text-yellow-600';
    return 'text-red-600';
};

const getStatusIcon = (deficiency: ViewDeficiency) => {
    if (deficiency.inspection_status === 'Completed') return CheckCircle;
    if (deficiency.inspection_status === 'Progress') return Clock;
    return AlertCircle;
};

// Replace the DeficiencyStatus constant and getStatusMessage function
const DeficiencyStatus = {
    PENDING: 'Pending',
    SEEN: 'Seen',
    ATTENDED: 'Attended'
} as const;

const getStatusMessage = (status: string) => {
    switch (status) {
        case DeficiencyStatus.PENDING:
            return 'No response has been submitted yet. The officer has not seen this deficiency.';
        case DeficiencyStatus.SEEN:
            return 'The Officer has seen this deficiency but has not submitted a response yet.';
        case DeficiencyStatus.ATTENDED:
            return null; // We don't show this message when status is ATTENDED
        default:
            return 'No response has been submitted yet.';
    }
};

onMounted(() => {
    console.log(deficiency);
});

</script>

<template>

    <Head title="View Deficiency" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="bg-white rounded-lg shadow-md border">
                <!-- Header Section -->
                <div class="border-b p-4 sm:p-6 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex items-start gap-3 flex-1">
                            <component :is="getStatusIcon(deficiency)"
                                :class="['w-6 h-6 mt-1 sm:mt-0 flex-shrink-0', getStatusColor(deficiency)]" />
                            <div>
                                <h1 class="text-xl font-semibold">{{ deficiency.location }}</h1>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span :class="[
                                'px-3 py-1 rounded-full text-sm font-medium',
                                getStatusColor(deficiency),
                                'bg-opacity-10'
                            ]">
                                {{ deficiency.inspection_status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6 space-y-6">
                    <!-- Inspection Details Card -->
                    <div class="bg-white rounded-lg p-4 sm:p-6 border shadow-sm hover:shadow-md transition-shadow">
                        <h2 class="text-lg font-medium mb-4 pb-2 border-b">Inspection Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <Calendar class="w-5 h-5 text-gray-400 mt-0.5" />
                                    <div>
                                        <span class="text-sm text-gray-500">Inspection Date & Time</span>
                                        <p class="font-medium">{{ deficiency.datetime }}</p>
                                        <span class="text-sm text-gray-500">{{ deficiency.day_period }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <User class="w-5 h-5 text-gray-400 mt-0.5" />
                                    <div>
                                        <span class="text-sm text-gray-500">Inspected By</span>
                                        <p class="font-medium">{{ deficiency.attended_by.name }}</p>
                                        <p class="text-sm">{{ deficiency.attended_by.active_designation.address_asc }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <MapPin class="w-5 h-5 text-gray-400 mt-0.5" />
                                    <div>
                                        <span class="text-sm text-gray-500">Location</span>
                                        <p class="font-medium">{{ deficiency.location }}</p>
                                        <span class="text-sm text-gray-500"> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex flex-col sm:flex-row justify-end gap-2">
                            <Button @click="viewNote" class="inline-flex items-center justify-center px-4 py-2 text-sm">
                                <ClipboardList class="w-4 h-4 mr-2" />
                                View Inspection Note
                            </Button>
                            <Button @click="downloadNote"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm">
                                <ClipboardList class="w-4 h-4 mr-2" />
                                Download Inspection Note
                            </Button>
                        </div>
                    </div>

                    <!-- Officer Details Card -->
                    <div class="bg-white rounded-lg p-4 sm:p-6 border shadow-sm hover:shadow-md transition-shadow">
                        <h2 class="text-lg font-medium mb-4 pb-2 border-b">Pertaining Officer</h2>
                        <div class="flex items-start gap-4">
                            <User class="w-8 h-8 text-purple-500" />
                            <div class="space-y-2">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ deficiency.pertains_to?.name ||
                                        'N/A' }}</h3>
                                    <p class="text-sm text-gray-600">{{
                                        deficiency.pertains_to?.active_designation?.address_asc || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deficiencies Card -->
                    <div class="bg-white p-4 rounded-lg border-l-4 border border-red-400 shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-3 pb-2 border-b flex items-center gap-2">
                            <AlertCircle class="w-4 h-4 text-red-500" />
                            Deficiencies / Observations / Abnormalities
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="flex-1">
                                    <p class="text-gray-700 whitespace-pre-line">{{ deficiency.note }}</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Response Details Section -->
                    <div v-if="deficiency.deficiency_status === DeficiencyStatus.ATTENDED"
                        class="bg-white p-4 rounded-lg border-l-4 border border-green-400 shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-3 pb-2 border-b flex items-center gap-2">
                            <MessageSquare class="w-4 h-4 text-green-500" />
                            Response Details
                            <span class="text-sm text-gray-500 ml-auto flex items-center gap-2">
                                <Clock class="w-4 h-4" />
                                Responded on {{ deficiency.deficiency_created_at }}
                            </span>
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <MessageSquare class="w-5 h-5 text-green-400 mt-0.5" />
                                <div class="flex-1">
                                    <span class="text-sm text-gray-500">Comment</span>
                                    <p class="mt-1 text-gray-700">{{ deficiency.comment }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <CalendarCheck class="w-5 h-5 text-green-400 mt-0.5" />
                                <div class="flex-1">
                                    <span class="text-sm text-gray-500">Action Date</span>
                                    <p class="mt-1 text-gray-700">{{ deficiency.action_date }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Show message if no response -->
                    <div v-else-if="getStatusMessage(deficiency.deficiency_status)"
                        class="bg-gray-50 p-4 rounded-lg border" :class="{
                            'border-yellow-300 bg-yellow-50': deficiency.deficiency_status === DeficiencyStatus.PENDING,
                            'border-blue-300 bg-blue-50': deficiency.deficiency_status === DeficiencyStatus.SEEN
                        }">
                        <p class="text-gray-600">{{ getStatusMessage(deficiency.deficiency_status) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

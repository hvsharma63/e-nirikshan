<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { AlertCircle, CheckCircle, Clock, MapPin, User, Calendar, MessageSquare, CalendarCheck, ClipboardList } from 'lucide-vue-next';
import { SharedData, ViewDeficiency } from '@/types';
import { onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import Gallery from '@/components/ui/images/Gallery.vue';

const breadcrumbs = [
    { title: 'Received Deficiencies', href: '/deficiencies' },
    { title: 'Deficiency Details', href: '/deficiencies' },
];

const page = usePage<SharedData>();

const deficiency: ViewDeficiency = (page.props.deficiency as { data: ViewDeficiency }).data;

const deficiencyForm = useForm({
    inspection_id: deficiency.inspection_id,
    comment: '',
    action_date: '',
});

const isSubmitting = ref(false);

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

const submitResponse = async (deficiencyId: number) => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    try {
        await deficiencyForm.post(route('officer.deficiencies.attend', {
            'id': deficiencyId
        }), {
            onFinish: () => {
                isSubmitting.value = false;
                // Manually handle state update
                if (Object.keys(deficiencyForm.errors).length === 0) {
                    // No errors, reload the page or update the view
                    window.location.reload();
                }
            },
            preserveScroll: true,
            preserveState: true, // Always preserve state to show errors if they exist
            replace: true
        });
    } catch (error) {
        console.error('Failed to submit response:', error);
        isSubmitting.value = false;
    }
};

onMounted(() => {
    console.log(deficiency);
});

const viewNote = () => {
    window.open(route('officer.deficiencies.view-note', deficiency.inspection_id), '_blank');
};

const downloadNote = () => {
    window.open(route('officer.deficiencies.download-note', deficiency.inspection_id), '_blank');
};

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
                                        <p class="text-sm">&nbsp;</p>
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
                                    <div class="gallery-wrapper">
                                        <Gallery :images="deficiency.media || []" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Section -->
                    <div v-if="deficiency.deficiency_status === 'Attended'"
                        class="bg-white p-4 rounded-lg border-l-4 border border-green-400 shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-3 pb-2 border-b flex items-center gap-2">
                            <MessageSquare class="w-4 h-4 text-green-500" />
                            Your Response Details
                            <span class="text-sm text-gray-500 ml-auto flex items-center gap-2">
                                <Clock class="w-4 h-4" />
                                Responded on {{ deficiency.deficiency_created_at }}
                            </span>
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <MessageSquare class="w-5 h-5 text-green-400 mt-0.5" />
                                <div class="flex-1">
                                    <span class="text-sm text-gray-500">Your Comment</span>
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

                    <!-- Response Form -->
                    <Form v-else @submit="submitResponse(deficiency.id)"
                        class="bg-white p-6 rounded-lg border shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-4">Your Response</h4>
                        <div class="space-y-4">
                            <FormField name="comment">
                                <FormItem>
                                    <FormLabel>Comment</FormLabel>
                                    <FormControl>
                                        <Textarea v-model="deficiencyForm.comment"
                                            :disabled="deficiency.inspection_status === 'attended'"
                                            placeholder="Enter your response..." rows="3" />
                                    </FormControl>
                                    <FormMessage />
                                    <InputError :message="deficiencyForm.errors.comment" />
                                </FormItem>
                            </FormField>

                            <FormField name="action_date">
                                <FormItem>
                                    <FormLabel>Action Date</FormLabel>
                                    <FormControl>
                                        <Input type="date" v-model="deficiencyForm.action_date"
                                            :disabled="deficiency.inspection_status === 'attended'" />
                                    </FormControl>
                                    <FormMessage />
                                    <InputError :message="deficiencyForm.errors.action_date" />
                                </FormItem>
                            </FormField>

                            <div class="flex justify-end">
                                <Button type="submit"
                                    :disabled="deficiency.inspection_status === 'attended' || isSubmitting || deficiencyForm.processing"
                                    :loading="isSubmitting || deficiencyForm.processing" class="w-full sm:w-auto">
                                    {{ isSubmitting || deficiencyForm.processing ? 'Submitting...' : 'Submit Response'
                                    }}
                                </Button>
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.gallery-wrapper :deep(.grid) {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.gallery-wrapper :deep(.aspect-square) {
    max-width: 200px;
    max-height: 200px;
}
</style>

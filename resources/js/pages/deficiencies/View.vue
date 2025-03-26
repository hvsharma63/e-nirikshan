<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { AlertCircle, CheckCircle, Clock } from 'lucide-vue-next';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import type { Deficiency } from '@/types';

const breadcrumbs = [
    { title: 'My Deficiencies', href: '/deficiencies' }
];

interface DeficiencyResponse {
    comment: string;
    action_date: string;
}

const deficiencies = ref<any[]>([
    {
        id: 1,
        inspection: {
            id: 1,
            location: 'Central Office Building',
            date: '2023-10-15',
            inspection_type: 'Safety Inspection',
            address: '123 Main Street, City Center',
        },
        pertains_to: 'Maintenance Officer',
        is_viewed: true,
        is_attended: false,
        comment_by_inspector: 'Fire extinguishers need immediate replacement in Block A',
        comment_by_pertaining_officer: '',
        action_date: '',
    },
    // ... more deficiencies
]);

const responses = ref<Record<number, DeficiencyResponse>>(
    Object.fromEntries(
        deficiencies.value.map(d => [d.id, {
            comment: '',
            action_date: ''
        }])
    )
);

const formatDate = (date: string) => {
    if (!date) return 'Not set';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const isSubmitting = ref(false);

const submitResponse = async (deficiencyId: number) => {
    if (isSubmitting.value) return;

    isSubmitting.value = true;
    try {
        await router.post(`/deficiencies/${deficiencyId}/respond`, responses.value[deficiencyId]);
    } catch (error) {
        console.error('Failed to submit response:', error);
    } finally {
        isSubmitting.value = false;
    }
};

const getStatusColor = (deficiency: typeof deficiencies.value[0]) => {
    if (deficiency.is_attended) return 'text-green-600';
    if (deficiency.is_viewed) return 'text-yellow-600';
    return 'text-red-600';
};

const getStatusIcon = (deficiency: typeof deficiencies.value[0]) => {
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

    <Head title="My Deficiencies" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
            <Card class="shadow-md">
                <section class="p-4 sm:p-8 bg-white rounded">
                    <div class="flex flex-col sm:flex-row justify-between mb-6 gap-2">
                        <h2 class="text-xl sm:text-2xl font-bold">My Deficiencies</h2>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm w-fit">
                            {{ deficiencies.length }} assigned
                        </span>
                    </div>

                    <div v-if="deficiencies.length === 0" class="text-center py-8 text-gray-500">
                        No deficiencies assigned
                    </div>

                    <Accordion type="single" collapsible class="space-y-4">
                        <AccordionItem v-for="deficiency in deficiencies" :key="deficiency.id"
                            :value="deficiency.id.toString()" class="border rounded-lg px-0">
                            <AccordionTrigger class="px-4 sm:px-6 hover:no-underline text-left w-full">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                                    <div class="flex items-start gap-3 flex-1">
                                        <component :is="getStatusIcon(deficiency)"
                                            :class="['w-5 h-5 mt-1 sm:mt-0 flex-shrink-0', getStatusColor(deficiency)]" />
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-medium">{{ deficiency.inspection.location }}</h3>
                                            <p class="text-sm text-gray-500 line-clamp-2 sm:line-clamp-1">
                                                {{ deficiency.comment_by_inspector }}
                                            </p>
                                            <div class="text-sm text-gray-500 mt-1 sm:hidden">
                                                Due: {{ formatDate(deficiency.inspection.date) }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="hidden sm:block text-sm text-gray-500 whitespace-nowrap">
                                        {{ formatDate(deficiency.inspection.date) }}
                                    </span>
                                </div>
                            </AccordionTrigger>
                            <AccordionContent class="px-4 sm:px-6 pt-4">
                                <div class="space-y-6 border-t pt-4">
                                    <!-- Inspection Details -->
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div class="space-y-3">
                                            <div>
                                                <span class="text-sm text-gray-500">Location</span>
                                                <p>{{ deficiency.inspection.location }}</p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Inspection Type</span>
                                                <p>{{ deficiency.inspection.inspection_type }}</p>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <span class="text-sm text-gray-500">Address</span>
                                                <p>{{ deficiency.inspection.address }}</p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Inspection Date</span>
                                                <p>{{ formatDate(deficiency.inspection.date) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Inspector's Comment -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="font-medium text-gray-900 mb-3">Inspector's Observation</h4>
                                        <p class="text-gray-700">{{ deficiency.comment_by_inspector }}</p>
                                    </div>

                                    <!-- Response Form -->
                                    <Form @submit="submitResponse(deficiency.id)"
                                        class="bg-white p-4 rounded-lg border">
                                        <h4 class="font-medium text-gray-900 mb-4">Your Response</h4>
                                        <div class="space-y-4">
                                            <FormField name="comment">
                                                <FormItem>
                                                    <FormLabel>Comment</FormLabel>
                                                    <FormControl>
                                                        <Textarea v-model="responses[deficiency.id].comment"
                                                            :disabled="deficiency.is_attended"
                                                            placeholder="Enter your response..." rows="3" />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="action_date">
                                                <FormItem>
                                                    <FormLabel>Action Date</FormLabel>
                                                    <FormControl>
                                                        <Input type="date"
                                                            v-model="responses[deficiency.id].action_date"
                                                            :disabled="deficiency.is_attended" />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <div class="flex justify-end">
                                                <Button type="submit" :disabled="deficiency.is_attended || isSubmitting"
                                                    :loading="isSubmitting" class="w-full sm:w-auto">
                                                    Submit Response
                                                </Button>
                                            </div>
                                        </div>
                                    </Form>
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

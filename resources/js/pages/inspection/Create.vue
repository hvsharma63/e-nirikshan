<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Card from '@/components/ui/card/Card.vue';
import { CalendarIcon, MapPinIcon, UserIcon, ClockIcon } from 'lucide-vue-next';
import { PlusCircleIcon, TrashIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inspections',
        href: '/inspections',
    },
    {
        title: 'Diarise Inspection',
        href: '/inspections/create',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    location: '',
    date: '',
    inspectionTypes: [],
    address: '',
    attended_by: user.name,
    attended_by_id: user.id,
    daytime: '',
    deficiencies: [{ note: '', pertainsTo: '' }],
});

const addDeficiency = () => {
    form.deficiencies.push({ note: '', pertainsTo: '' });
};

const removeDeficiency = (index: number) => {
    form.deficiencies.splice(index, 1);
};

const submit = () => {
    // form.post(route('profile.update'), {
    //     preserveScroll: true,
    // });
};
</script>

<template>

    <Head title="Diarise Inspection" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <Card class="shadow-md">
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg sm:text-xl font-semibold tracking-tight mb-6">
                        Diarise Inspection
                    </h2>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="location">Location</Label>
                                <div class="flex items-center gap-2">
                                    <MapPinIcon class="h-5 w-5 text-muted-foreground" />
                                    <Input id="location" type="text" required autofocus :tabindex="1"
                                        placeholder="Enter location" v-model="form.location" />
                                </div>
                                <InputError :message="form.errors.location" />
                            </div>

                            <div class="space-y-2">
                                <Label for="date">Inspection Date & Time</Label>
                                <div class="flex items-center gap-2">
                                    <CalendarIcon class="h-5 w-5 text-muted-foreground" />
                                    <Input id="date" type="datetime-local" required :tabindex="2" v-model="form.date" />
                                </div>
                                <InputError :message="form.errors.date" />
                            </div>

                            <div class="space-y-2">
                                <Label for="attended_by">Attended By</Label>
                                <div class="flex items-center gap-2">
                                    <UserIcon class="h-5 w-5 text-muted-foreground" />
                                    <Input id="attended_by" type="text" required :tabindex="3"
                                        placeholder="Enter attendee name" v-model="form.attended_by" disabled />
                                </div>
                                <InputError :message="form.errors.attended_by" />
                            </div>

                            <div class="space-y-2">
                                <Label for="daytime">Time</Label>
                                <div class="flex items-center gap-2">
                                    <ClockIcon class="h-5 w-5 text-muted-foreground" />
                                    <Input id="daytime" type="time" required :tabindex="4" v-model="form.daytime" />
                                </div>
                                <InputError :message="form.errors.daytime" />
                            </div>

                            <div class="sm:col-span-2 space-y-2">
                                <Label for="address">Address</Label>
                                <div class="flex items-center gap-2">
                                    <MapPinIcon class="h-5 w-5 text-muted-foreground" />
                                    <textarea id="address" type="text" required :tabindex="5"
                                        class="w-full h-24 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        placeholder="Enter complete address" v-model="form.address">
                                </textarea>
                                </div>
                                <InputError :message="form.errors.address" />
                            </div>

                            <!-- Modified Deficiencies Section -->
                            <div class="sm:col-span-2 mt-6">
                                <h3 class="text-xl font-semibold mb-2">Deficiencies</h3>
                                <div v-for="(deficiency, index) in form.deficiencies" :key="index"
                                    class="mb-4 p-4 border rounded-md">
                                    <div class="space-y-2">
                                        <Label :for="`deficiency_note_${index}`">Deficiency Note</Label>
                                        <textarea :id="`deficiency_note_${index}`" required
                                            placeholder="Enter deficiency note" v-model="deficiency.note"
                                            class="w-full h-20 rounded-md border border-input bg-background px-3 py-2 text-base focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                                        </textarea>
                                    </div>
                                    <div class="space-y-2 mt-4">
                                        <Label :for="`deficiency_user_${index}`">Pertains To</Label>
                                        <select :id="`deficiency_user_${index}`" required
                                            v-model="deficiency.pertainsTo"
                                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-base focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                                            <option disabled value="">Select User</option>
                                            <option value="user1">User 1</option>
                                            <option value="user2">User 2</option>
                                            <option value="user3">User 3</option>
                                        </select>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <Button type="button"
                                            class="btn-remove bg-red-500 text-white hover:bg-red-600 flex items-center gap-2"
                                            @click="removeDeficiency(index)">
                                            <TrashIcon class="h-4 w-4" />
                                            Remove
                                        </Button>
                                    </div>
                                </div>
                                <div>
                                    <Button type="button"
                                        class="btn-add bg-green-500 text-white hover:bg-green-600 flex items-center gap-2"
                                        @click="addDeficiency">
                                        <PlusCircleIcon class="h-4 w-4" />
                                        Add
                                    </Button>
                                </div>
                            </div>
                            <!-- End of Deficiencies Section -->
                        </div>

                        <div class="flex justify-end">
                            <Button type="submit" class="btn-primary w-full sm:w-auto" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                                Create Inspection
                            </Button>
                        </div>
                    </form>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

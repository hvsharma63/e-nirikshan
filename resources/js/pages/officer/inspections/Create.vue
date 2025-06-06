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
import { CalendarIcon, MapPinIcon } from 'lucide-vue-next';
import { PlusCircleIcon, TrashIcon } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';
import apiService from '@/services/apiService';
import 'vue-select/dist/vue-select.css';
import { Checkbox } from '@/components/ui/checkbox';
import vSelect from 'vue-select';
import ImageUpload from '@/components/ui/images/Upload.vue';
import { toastError } from '@/services/toasterService';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Inspections',
        href: '/inspections',
    },
    {
        title: 'Diarise Inspection',
        href: '/inspections/create',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const getCurrentDateTime = () => {
    const now = new Date();
    return new Date(now.getTime() - now.getTimezoneOffset() * 60000)
        .toISOString()
        .slice(0, 16);
};

const createDeficiency = () => ({
    note: '',
    pertains_to: null,
    temporary_upload_uuid: null,
    has_unuploaded_images: false,
    has_images: false
});

const form = useForm({
    location: '',
    datetime: getCurrentDateTime(),
    is_draft: false as boolean,
    attended_by: user.name,
    attended_by_id: user.id,
    day_period: '',
    deficiencies: [createDeficiency()],
    completed: false,
    no_deficiencies_found: false,
});

const users = ref<{ name: string; value: number; }[]>([]);

const addDeficiency = () => {
    form.deficiencies.push(createDeficiency());
};

const removeDeficiency = (index: number) => {
    form.deficiencies.splice(index, 1);
};

const validateImages = () => {
    const hasUnuploadedImages = form.deficiencies.some(d => d.has_images && d.has_unuploaded_images);

    if (hasUnuploadedImages) {
        toastError('Please upload all images or remove failed uploads before proceeding');
        return false;
    }
    return true;
};

const submit = (type: 'draft' | 'create') => {
    if (!validateImages()) {
        return;
    }

    form.is_draft = type === 'draft';
    form.post(route('officer.inspections.save'), {
        preserveScroll: true,
    });
};

const getUsersList = () => {
    apiService.get(route('users.list-branch-officers')).then((response) => {
        users.value = response.data;
    });
}

const onNoDeficienciesCheckboxClick = (value: any) => {
    if (value) {
        form.deficiencies = [];
    } else {
        addDeficiency();
    }
};

onMounted(() => {
    getUsersList();
});
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
                    <form @submit.prevent="() => submit('create')" class="space-y-6">
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
                                <Label for="datetime">Inspection Date & Time</Label>
                                <div class="flex items-center gap-2">
                                    <CalendarIcon class="h-5 w-5 text-muted-foreground" />
                                    <Input id="datetime" type="datetime-local" required :tabindex="2"
                                        v-model="form.datetime" />
                                </div>
                                <InputError :message="form.errors.datetime" />
                            </div>

                            <!-- Modified Deficiencies Section -->
                            <div class="sm:col-span-2 mt-4">
                                <h3 class="text-xl font-semibold mb-2">Deficiencies / Observations / Abnormalities</h3>
                                <div v-show="!form.no_deficiencies_found">
                                    <div v-for="(deficiency, index) in form.deficiencies" :key="index"
                                        class="mb-4 p-4 border rounded-md">
                                        <div class="space-y-2">
                                            <Label :for="`deficiency_note_${index}`">Deficiency Note</Label>
                                            <textarea :id="`deficiency_note_${index}`" required
                                                placeholder="Enter deficiency note" v-model="deficiency.note"
                                                class="w-full h-20 rounded-md border border-input bg-background px-3 py-2 text-base focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                                            </textarea>
                                            <InputError
                                                :message="(form.errors as Record<string, string>)[`deficiencies.${index}.note`]" />
                                        </div>
                                        <div class="space-y-2 mt-4">
                                            <Label>Images</Label>
                                            <div class="space-y-4">
                                                <ImageUpload :temporaryUuid="deficiency.temporary_upload_uuid"
                                                    :deficiency-id="index" @uploadStatusChange="(status) => {
                                                        deficiency.has_unuploaded_images = status.hasUnuploadedImages;
                                                        deficiency.has_images = status.hasImages;
                                                    }"
                                                    @uuidAssigned="(uuid) => deficiency.temporary_upload_uuid = uuid" />
                                                <p v-if="!deficiency.temporary_upload_uuid"
                                                    class="text-sm text-muted-foreground">
                                                    Upload images related to this deficiency
                                                </p>
                                                <p v-else class="text-sm text-green-600">
                                                    Images uploaded successfully
                                                </p>
                                            </div>
                                        </div>
                                        <div class="space-y-2 mt-4">
                                            <Label :for="`deficiency_user_${index}`">Pertains To</Label>
                                            <v-select v-model="deficiency.pertains_to" :options="users"
                                                :reduce="(user: any) => user.value"
                                                :get-option-label="(option: any) => option.label"
                                                placeholder="Search or Select Officer" :searchable="true"
                                                :clearable="true" />
                                            <InputError
                                                :message="(form.errors as Record<string, string>)[`deficiencies.${index}.pertains_to`]" />
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
                                <div class="items-top flex gap-x-2 mt-4">
                                    <Checkbox id="no_deficiencies_found" v-model="form.no_deficiencies_found"
                                        @update:modelValue="onNoDeficienciesCheckboxClick" />
                                    <div class="grid gap-1.5 leading-none">
                                        <label for="no_deficiencies_found"
                                            class="text-base font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                            No Deficiencies Found
                                        </label>
                                        <p class="text-sm text-muted-foreground max-w-[90ch]">
                                            Check the box to confirm and mark the inspection as 'Completed'
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Deficiencies Section -->
                        </div>

                        <div class="flex justify-end gap-2">
                            <!-- <Button type="button" class="btn-secondary w-full sm:w-auto" :disabled="form.processing"
                                @click="submit('draft')">
                                <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                                Save Draft
                            </Button> -->
                            <Button type="button" class="btn-primary w-full sm:w-auto" :disabled="form.processing"
                                @click="submit('create')">
                                <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                                Diarise
                            </Button>
                        </div>
                    </form>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
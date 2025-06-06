<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import { onMounted } from 'vue';

interface Props {
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
    mobile_no: user.mobile_no,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

onMounted(() => {
    console.log(user);
});

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="pf_no">PF No.</Label>
                        <Input id="pf_no" class="mt-1 block w-full disabled text-black dark:text-white"
                            v-model="user.pf_no" required disabled />
                    </div>

                    <div class="grid gap-2">
                        <Label for="dob">Date of Birth</Label>
                        <Input id="dob" class="mt-1 block w-full disabled text-black dark:text-white" v-model="user.dob"
                            required disabled />
                    </div>

                    <div class="grid gap-2">
                        <Label for="designation">Designation</Label>
                        <Input id="designation" class="mt-1 block w-full" v-model="user.active_designation.address_asc"
                            required disabled />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name"
                            placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>


                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autocomplete="username" placeholder="Email address" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="mobile_no">Mobile No</Label>
                        <Input id="mobile_no" type="mobile_no" class="mt-1 block w-full" v-model="form.mobile_no"
                            required autocomplete="username" placeholder="Contact No" />
                        <InputError class="mt-2" :message="form.errors.mobile_no" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

        </SettingsLayout>
    </AppLayout>
</template>

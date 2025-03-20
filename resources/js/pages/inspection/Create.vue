<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inspections',
        href: '/inspections',
    },
    {
        title: 'Create Inspection',
        href: '/inspections/create',
    },
];
</script>

<template>

    <Head title="Create Inspection" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="pf_no">PF No.</Label>
                        <Input id="pf_no" class="mt-1 block w-full disabled text-black" required disabled />
                    </div>

                    <div class="grid gap-2">
                        <Label for="dob">Date of Birth</Label>
                        <Input id="dob" class="mt-1 block w-full disabled text-black" required disabled />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" required autocomplete="name"
                            placeholder="Full name" />
                        <InputError class="mt-2" />
                    </div>


                </form>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>

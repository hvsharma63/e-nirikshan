<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import {
    UserIcon,
    MailIcon,
    PhoneIcon,
    CalendarIcon,
    BuildingIcon,
    ClipboardListIcon,
    AlertCircleIcon,
    IdCardIcon
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, ViewUser } from '@/types';

const page = usePage<SharedData>();

const user: ViewUser = (page.props.user as { data: ViewUser }).data;

const breadcrumbs = [
    {
        title: 'Users',
        href: '/admin/users',
    },
    {
        title: 'User Details',
        href: '#',
    },
];

onMounted(() => {
    // Fetch user data if needed
    // This is just a placeholder, you can implement your own logic here
    console.log('User data:', user);
});

const goToInspection = (id: number) => {
    router.visit(route('admin.inspections.view', id));
};

const goToDeficiency = (id: number) => {
    router.visit(route('admin.deficiencies.view', id));
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="User Profile" />

        <div class="container p-4 sm:p-6 space-y-6">
            <div class="space-y-6">
                <!-- User Info Card -->
                <Card class="bg-card">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-card-foreground">
                            <UserIcon class="h-5 w-5 text-primary" />
                            Personal Information
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <p class="text-sm text-muted-foreground">Name</p>
                                    <p class="font-medium flex items-center gap-2 text-foreground">
                                        <UserIcon class="h-4 w-4 text-primary" />
                                        {{ user.name }}
                                    </p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-sm text-muted-foreground">Email</p>
                                    <p class="font-medium flex items-center gap-2 text-foreground">
                                        <MailIcon class="h-4 w-4 text-primary" />
                                        {{ user.email }}
                                    </p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-sm text-muted-foreground">PF Number</p>
                                    <p class="font-medium flex items-center gap-2 text-foreground">
                                        <IdCardIcon class="h-4 w-4 text-primary" />
                                        {{ user.pf_no }}
                                    </p>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <p class="text-sm text-muted-foreground">Mobile</p>
                                    <p class="font-medium flex items-center gap-2 text-foreground">
                                        <PhoneIcon class="h-4 w-4 text-primary" />
                                        {{ user.mobile_no }}
                                    </p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-sm text-muted-foreground">Date of Birth</p>
                                    <p class="font-medium flex items-center gap-2 text-foreground">
                                        <CalendarIcon class="h-4 w-4 text-primary" />
                                        {{ user.dob }}
                                    </p>
                                </div>

                                <div v-if="user.current_designation" class="space-y-1">
                                    <p class="text-sm text-muted-foreground">Current Designation</p>
                                    <p class="font-medium flex items-center gap-2 text-foreground">
                                        <BuildingIcon class="h-4 w-4 text-primary" />
                                        {{ user.current_designation.address_asc }}
                                    </p>
                                    <p class="text-sm text-muted-foreground"> &nbsp;
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <!-- Inspection Stats -->
                    <Card class="bg-card lg:col-span-2">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2 text-card-foreground text-base">
                                <ClipboardListIcon class="h-5 w-5 text-primary" />
                                Inspection Statistics
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="pt-2">
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div>
                                    <div class="text-2xl font-bold text-primary">{{ user.inspection_statistics.completed
                                        ?? 0
                                    }}
                                    </div>
                                    <p class="text-xs text-muted-foreground">Completed</p>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-secondary-foreground">{{
                                        user.inspection_statistics.progress ?? 0 }}</div>
                                    <p class="text-xs text-muted-foreground">In Progress</p>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-foreground">{{ user.inspection_statistics.total
                                        ?? 0
                                    }}
                                    </div>
                                    <p class="text-xs text-muted-foreground">Total</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Deficiency Stats -->
                    <Card class="bg-card lg:col-span-2">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2 text-card-foreground text-base">
                                <AlertCircleIcon class="h-5 w-5 text-primary" />
                                Deficiency Statistics
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="pt-2">
                            <div class="grid grid-cols-4 gap-4 items-center">
                                <div>
                                    <div class="text-2xl font-bold text-primary">{{ user.deficiency_statistics.attended
                                        ?? 0
                                    }}
                                    </div>
                                    <p class="text-xs text-muted-foreground">Attended</p>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-yellow-600">{{ user.deficiency_statistics.seen
                                        ?? 0
                                    }}
                                    </div>
                                    <p class="text-xs text-muted-foreground">Seen</p>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-destructive">{{
                                        user.deficiency_statistics.pending ?? 0
                                    }}</div>
                                    <p class="text-xs text-muted-foreground">Pending</p>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-foreground">{{ user.deficiency_statistics.total
                                        ?? 0
                                    }}
                                    </div>
                                    <p class="text-xs text-muted-foreground">Total</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Recent Activities -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Inspections -->
                    <Card class="bg-card">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2 text-card-foreground">
                                <ClipboardListIcon class="h-5 w-5 text-primary" />
                                Recent Inspections
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="text-muted-foreground">Date</TableHead>
                                        <TableHead class="text-muted-foreground">Location</TableHead>
                                        <TableHead class="text-muted-foreground">Status</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-if="!user.recent_inspections?.length">
                                        <TableCell colspan="3" class="text-center text-muted-foreground">
                                            No inspections found
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-else v-for="inspection in user.recent_inspections" :key="inspection.id"
                                        @click="goToInspection(inspection.id)" class="cursor-pointer hover:bg-muted/50">
                                        <TableCell class="text-foreground">{{ inspection.datetime }}</TableCell>
                                        <TableCell class="text-foreground">{{ inspection.location }}</TableCell>
                                        <TableCell>
                                            <Badge :class="{
                                                'bg-primary/10 text-primary': inspection.status === 'Completed',
                                                'bg-secondary text-secondary-foreground': inspection.status === 'Pending'
                                            }">
                                                {{ inspection.status }}
                                            </Badge>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>

                    <!-- Recent Deficiencies -->
                    <Card class="bg-card">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2 text-card-foreground">
                                <AlertCircleIcon class="h-5 w-5 text-primary" />
                                Recent Deficiencies
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="text-muted-foreground">Date</TableHead>
                                        <TableHead class="text-muted-foreground">Description</TableHead>
                                        <TableHead class="text-muted-foreground">Status</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-if="!user.recent_deficiencies?.length">
                                        <TableCell colspan="3" class="text-center text-muted-foreground">
                                            No deficiencies found
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-else v-for="deficiency in user.recent_deficiencies" :key="deficiency.id"
                                        @click="goToDeficiency(deficiency.id)" class="cursor-pointer hover:bg-muted/50">
                                        <TableCell>{{ deficiency.created_at }}</TableCell>
                                        <TableCell class="max-w-[200px] truncate">{{ deficiency.note }}</TableCell>
                                        <TableCell>
                                            <Badge :class="{
                                                'bg-primary/10 text-primary': deficiency.status,
                                                'bg-destructive text-destructive-foreground': !deficiency.status
                                            }">
                                                {{ deficiency.status ? 'Attended' : 'Pending' }}
                                            </Badge>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

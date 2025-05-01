<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, CalendarClock, LayoutGrid, ScrollText, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';
import { usePermissionStore } from '@/stores/permission';

// Initialize Pinia store
const store = usePermissionStore();

// Define navigation items with required permissions

const generalNavItems: (NavItem & { permission?: string })[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        permission: 'view-dashboard',
    },
];

const adminNavItems: (NavItem & { permission?: string })[] = [
    {
        title: 'All Inspections',
        href: '/admin/inspections',
        icon: ScrollText,
        permission: 'view-all-index-inspections',
    },
    {
        title: 'All Deficiencies',
        href: '/admin/deficiencies',
        icon: CalendarClock,
        permission: 'view-all-index-inspections',
    },
    {
        title: 'All Users',
        href: '/admin/users',
        icon: Users,
        permission: 'view-all-index-inspections',
    },
];


const officerNavItems: (NavItem & { permission?: string })[] = [
    {
        title: 'My Inspections',
        href: '/inspections',
        icon: ScrollText,
        permission: 'view-inspections',
    },
    {
        title: 'Received Deficiencies',
        href: '/deficiencies',
        icon: CalendarClock,
        permission: 'view-deficiencies',
    },
];

const footerNavItems: (NavItem)[] = [
    {
        title: 'Documentation',
        href: 'https://pine-swift-34e.notion.site/Ghaat-Nirikshan-Inspection-Portal-1b9ab2f4a3af8045a782f49feee4f609?pvs=74',
        icon: BookOpen,
    },
];

// Filter navigation items based on permissions
const filteredOfficerNavItems = computed(() =>
    officerNavItems.filter((item) => !item.permission || (store.hasPermission(item.permission)))
);

const filteredAdminNavItems = computed(() =>
    adminNavItems.filter((item) => !item.permission || (store.hasPermission(item.permission)))
);

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard.index')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="generalNavItems" :title="'Dashboard'" />
            <NavMain :items="filteredAdminNavItems" :title="'Admin'" v-if="store.hasRole('admin')" />
            <NavMain :items="filteredOfficerNavItems" :title="'Officer'" v-if="store.hasRole('officer')" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
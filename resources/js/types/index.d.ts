import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface User {
    id: number;
    name: string;
    email: string;
    active_designation: { address_asc: string; };
    pf_no: string;
    dob: string;
    mobile_no: string;
    avatar?: string;
    created_at: string;
    updated_at: string;
}

export interface ListInspections {
    id: number;
    location: string;
    date: string;
    time: string;
    day_period: string;
    deficiencies_count: number;
    status: string;
}

export interface ListDeficiencies {
    id: number;
    location: string;
    note: string;
    action_date: string;
    attended_by: string;
    date: string;
    time: string;
    status: string;
}

export interface Deficiencies {
    id: number;
    inspection_id: number;
    pertains_to: string;
    is_viewed: boolean;
    is_attended: boolean;
    comment_by_inspector: string;
    comment_by_pertaining_officer: string;
    action_date: string;
}

export interface Deficiency {
    id: number;
    inspection_id: number;
    pertains_to: string;
    is_viewed: boolean;
    is_attended: boolean;
    comment_by_inspector: string;
    comment_by_pertaining_officer: string;
    action_date: string;
}

export interface ItemDeficiency {
    id: number;
    pertains_to: ItemDeficiencyPertainsTo;
    note: string;
    action_date: string;
    status: string;
    reported_on: string;
    is_pending: boolean;
    is_seen: boolean;
    is_attended: boolean;
    comment_by_pertaining_officer: string;
}

export interface ItemDeficiencyPertainsTo {
    id: number;
    name: string;
    active_designation: UserActiveDesignation;
}

export interface UserActiveDesignation {
    id: number;
    address_asc: string;
}

export interface ViewInspection {
    id: number;
    location: string;
    attended_by: ItemDeficiencyPertainsTo;
    datetime: string;
    day_period: string;
    no_deficiencies_found: boolean;
    status: string;
    deficiencies: ItemDeficiency[];
}


export interface ViewDeficiency {
    id: number;
    inspection_id: number;
    location: string;
    note: string;
    attended_by: ItemDeficiencyPertainsTo;
    pertains_to?: ItemDeficiencyPertainsTo;
    datetime: string;
    deficiency_note: string;
    day_period: string;
    inspection_status: string;
    deficiency_status: string;
    action_date: string;
    comment: string;
    deficiency_created_at: string;
    deficiency_status: string;
}

export interface DropdownItem {
    label: string;
    value: string | number;
}

export interface ViewUser {
    id: number;
    name: string;
    email: string;
    dob: string;
    pf_no: string;
    mobile_no: string;
    current_designation: UserActiveDesignation;
    inspection_statistics: {
        completed: number;
        total: number;
        progress: number;
    };
    deficiency_statistics: {
        attended: number;
        seen: number;
        pending: number;
        total: number;
    };
    recent_inspections: RecentInspection[];
    recent_deficiencies: RecentDeficiency[];
}

export interface RecentInspection {
    id: number;
    datetime: string;
    location: string;
    status: string;
}

export interface RecentDeficiency {
    id: number;
    created_at: string;
    note: string;
    status: string;
}

export interface RecentDeficiency {
    id: number;
    date: string;
    note: string;
    status: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

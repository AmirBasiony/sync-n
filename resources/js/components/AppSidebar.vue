<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    BookText,
    CreditCard,
    Folder,
    HelpCircle,
    House,
    Images,
    Layers,
    Layers2,
    LayoutGrid,
    LayoutList,
    MessageCircle,
    MonitorSmartphone,
    Shield,
    Lock,
    User,
    Users,
    Gamepad,
    MonitorSpeaker,
    UsersRound,
    Globe,
    Megaphone,
    LifeBuoy,
    Ticket,
    CirclePower,
    Newspaper,
    ChartBarStacked,
    Notebook,
} from 'lucide-vue-next';

import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
    },
    {
        title: 'Devices',
        icon: MonitorSpeaker,
        href: '#',
        label: 'Devices Management',
        subitems: [
            {
                title: 'Devices',
                href: route('devices.index'),
                icon: MonitorSmartphone,
            },
            {
                title: 'Types',
                href: route('types.index'),
                icon: Layers2,
            },
            {
                title: 'Widgets',
               href: route('subtypes.index'),
                icon: Layers,
            },
            {
                title: 'Remote Control',
                href: route('remoteControl.index'),
                icon: Gamepad, // ✅ هنا التغيير
            },
        ],
    },
    {
        title: 'Users',
        href: '#',
        label: 'Users Management',
        icon: UsersRound,
        subitems: [
            {
                title: 'Users',
                href: route('users.index'),
                icon: Users,
            },
            // {
            //     title: 'Subscriptions',
            //     href: route('subscriptions.index'),
            //     icon: CreditCard,
            // },
            {
                title: 'Notifications',
                href: route('users.notifications.create'),
                icon: Megaphone,
            },
        ],
    },
    {
        title: 'Admins',
        icon: Shield,
        href: '#',
        label: 'Admins Management',
        subitems: [
            {
                title: 'Admins',
                href: route('admins.index'),
                icon: User,
            },
            {
                title: 'Roles',
                href: route('roles.index'),
                icon: Lock,
            },
            {
                title: 'Permissions',
                href: route('permissions.index'),
                icon: Shield,
            },
        ],
    },
    {
        title: 'Website',
        icon: Globe,
        href: '#',
        label: 'Website Management',
        subitems: [
            {
                title: 'Blogs',
                href: route('blogs.index'),
                icon: Newspaper,
            },
            {
                title: 'Content Hub',
                href: route('content-hubs.index'),
                icon: MessageCircle,
            },
            {
                title: 'Case Study Category',
                href:  route('category.index'),
                icon: ChartBarStacked,
            },
            {
                title: 'Case Study',
                href: route('case-studies.index'),
                icon: Notebook,
            },
            {
                title: 'Faqs',
                href: route('faqs.index'),
                icon: HelpCircle,
            },
        ],
    },
    {
        title: 'Help Center',
        icon: LifeBuoy,
        href: '#',
        label: 'Help Center Management',
        subitems: [
            {
                title: 'Tickets',
                href: route('tickets.index'),
                icon: Ticket,
            },
            {
                title: 'Troubleshooting',
                href: '#',
                icon: CirclePower,
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

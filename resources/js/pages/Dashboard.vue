<script setup lang="ts">
import { Card, CardDescription, CardFooter, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const props = defineProps<{
    admin_logs: any;
    devices_num: any;
    types_num: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <Card class="border-none text-center">
                        <CardHeader>
                            <CardTitle>Number Of Devices</CardTitle>
                            <CardDescription class="font-bold">{{ devices_num }}</CardDescription>
                        </CardHeader>
                        <CardHeader>
                            <CardTitle>Number Of Types</CardTitle>
                            <CardDescription class="font-bold">{{ types_num }}</CardDescription>
                        </CardHeader>
                    </Card>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    
                    <CardTitle class="text-center pt-2">Admin Logs</CardTitle>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Action</TableHead>
                                <TableHead>Time</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="admin_log in admin_logs" :key="admin_log.id">
                                <TableCell>{{ admin_log.id }}</TableCell>
                                <TableCell>{{ admin_log.causer.name }}</TableCell>
                                <TableCell>{{ admin_log.causer.email }}</TableCell>
                                <TableCell>{{ admin_log.log_name }}</TableCell>
                                <TableCell>{{ admin_log.created_at }}</TableCell>
                            </TableRow>
                            <TableRow v-if="admin_logs.length === 0">
                                <TableCell colspan="5" class="text-center">No users found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>

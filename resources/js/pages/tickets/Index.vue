<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import TableToolbar from '@/components/TableToolbar.vue';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Client Tickets', href: route('tickets.index') }];

const props = defineProps({
    tickets: {
        type: Object,
        required: true,
    },
});

/**
 * ==============
 * METHODS
 * ==============
 */
function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('tickets.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Help Center - Client Tickets" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :on-filter-change="onFilterChange" :showUsedFilter="false" :showCreateButton="false" />

            <div v-if="tickets.data.length" class="mt-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Ticket Number</TableHead>
                            <TableHead>Client Name</TableHead>
                            <TableHead>Client Phone</TableHead>
                            <TableHead>Client Email</TableHead>
                            <TableHead>Last Reply</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Created At</TableHead>
                            <TableHead>Closed at</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="ticket in tickets.data" :key="ticket.id">
                            <a :href="route('tickets.show', ticket.id)" class="cursor-pointer hover:underline text-blue-600 dark:text-blue-400 dark:hover:text-blue-300">
                                <TableCell>{{ ticket.ticket_number }}</TableCell>
                            </a>
                            <TableCell>{{ ticket.user.name }}</TableCell>
                            <TableCell>{{ ticket.user.phone }}</TableCell>
                            <TableCell>{{ ticket.user.email }}</TableCell>
                            <TableCell>{{ ticket.last_reply_at }}</TableCell>
                            <TableCell>
                                <Badge :class="ticket.status_badge">
                                    {{ ticket.status }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{ ticket.created_at }}</TableCell>
                            <TableCell>{{ ticket.closed_at ?? '-' }}</TableCell>
                        </TableRow>
                    </TableBody>
                    <TableCaption>
                        <div v-if="tickets.total > tickets.per_page" class="row align-items-center mt-4">
                            <div class="ml-auto">
                                <Pagination :pagination="tickets.links" />
                            </div>
                        </div>
                    </TableCaption>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>

<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import TableToolbar from '@/components/TableToolbar.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { SquarePen, Trash2 } from 'lucide-vue-next';
const { confirmDelete } = useConfirmDelete();

const props = defineProps<{
    subscriptions: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Subscriptions',
        href: route('subscriptions.index'),
    },
];

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('devices.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteSubscription(id: string) {
    confirmDelete('subscriptions.destroy', id);
}
</script>

<template>
    <Head title="Subscriptions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('subscriptions.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

            <div class="panel panel-bordered mx-4">
                <div class="panel-body">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>User</TableHead>
                                <TableHead>Plan</TableHead>
                                <TableHead>Subscription Date</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="subscription in subscriptions.data" :key="subscription.id">
                                <TableCell>{{ subscription.user?.name }}</TableCell>
                                <TableCell>{{ subscription.plan?.title_en }}</TableCell>
                                <TableCell>{{ subscription.created_at }}</TableCell>
                                <TableCell class="flex gap-2">
                                    <Link :href="route('subscriptions.edit', subscription.id)">
                                        <Button type="button" variant="primary"><SquarePen />Edit</Button>
                                    </Link>
                                    <Button type="button" @click="deleteSubscription(subscription.id)" variant="danger"> <Trash2 />Delete </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="subscriptions.total === 0">
                                <TableCell colspan="4" class="text-center">No subscriptions found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <div v-if="subscriptions.total > subscriptions.per_page" class="row align-items-center mt-4">
                        <div class="ml-auto">
                            <Pagination :pagination="subscriptions.links" />
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Showing {{ subscriptions.from }} to {{ subscriptions.to }} of {{ subscriptions.total }} entries
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

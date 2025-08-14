<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import TableToolbar from '@/components/TableToolbar.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { SquarePen, Trash2 } from 'lucide-vue-next';
const { confirmDelete } = useConfirmDelete();

const props = defineProps<{
    users: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.index'),
    },
];

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('users.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteUser(id: string) {
    confirmDelete('users.destroy', id);
}
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('users.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

            <div class="panel panel-bordered mx-4">
                <div class="panel-heading d-flex justify-content-between align-items-center"></div>

                <div class="panel-body">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Gender</TableHead>
                                <TableHead>Active</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in users.data" :key="user.id">
                                <TableCell>{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>{{ user.gender == 'female' ? 'Female' : 'Male' }}</TableCell>
                                <TableCell>
                                    <Badge :variant="user.email_verified_at ? 'success' : 'destructive'">
                                        {{ user.email_verified_at ? 'Yes' : 'No' }}
                                    </Badge>
                                </TableCell>

                                <TableCell class="flex gap-2">
                                    <Link :href="route('users.edit', user.id)">
                                        <Button size="sm" variant="secondary">
                                            <SquarePen class="w-4 h-4 mr-1" /> Edit
                                        </Button>
                                    </Link>
                                    <Button variant="ghost" size="sm" @click="deleteUser(user.id)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="users.total === 0">
                                <TableCell colspan="5" class="text-center">No users found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-if="users.total > users.per_page" class="row align-items-center mt-4">
                        <Pagination :pagination="users.links" />
                        <div class="small text-muted">Showing {{ users.from }} to {{ users.to }} of {{ users.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

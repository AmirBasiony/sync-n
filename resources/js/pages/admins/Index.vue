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
import { usePermissions } from '@/composables/usePermissions';

const { hasPermission } = usePermissions();
const { confirmDelete } = useConfirmDelete();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admins',
        href: route('admins.index'),
    },
];

const props = defineProps<{
    admins: any;
}>();

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('admins.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteAdmin(id: string) {
    confirmDelete('admins.destroy', id);
}
</script>

<template>
    <Head title="Admins" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="hasPermission('edit_admin') ? route('admins.create') : false" :on-filter-change="onFilterChange" :show-used-filter="false" />

            <div class="mt-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead>Active</TableHead>
                            <TableHead>Created At</TableHead>
                            <TableHead v-if="hasPermission('edit_admin') && hasPermission('delete_admin')">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="admin in admins.data" :key="admin.id">
                            <TableCell>{{ admin.name }}</TableCell>
                            <TableCell>{{ admin.email }}</TableCell>
                            <TableCell>
                                <span :class="!admin.roles[0]?.name ? 'text-red-400' : ''">
                                    {{ admin.roles[0]?.name ?? 'Not linked with any Role yet' }}
                                </span>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="admin.is_active ? 'success' : 'destructive'">
                                    {{ admin.is_active ? 'Yes' : 'No' }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{ admin.created_at }}</TableCell>
                            <TableCell class="flex gap-2 pt-6" v-if="hasPermission('edit_admin') && hasPermission('delete_admin')">
                                <Link v-if="hasPermission('edit_admin')" :href="route('admins.edit', admin.id)" class="btn btn-info btn-sm">
                                    <Button type="button" variant="primary"><SquarePen />Edit</Button>
                                </Link>
                                <Button v-if="hasPermission('delete_admin')" variant="danger" type="button" @click="deleteAdmin(admin.id)"><Trash2 />Delete</Button>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="admins.total.length === 0">
                            <TableCell colspan="5" class="text-center">No admins found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div v-if="admins.total > admins.per_page" class="row align-items-center mt-4">
                    <div class="ml-auto">
                        <Pagination :pagination="admins.links" />
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Showing {{ admins.from }} to {{ admins.to }} of {{ admins.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

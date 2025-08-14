<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import TableToolbar from '@/components/TableToolbar.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye, SquarePen, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { useDate } from '@/composables/useDate';
import { usePermissions } from '@/composables/usePermissions';

const { hasPermission } = usePermissions();
const { confirmDelete } = useConfirmDelete();
const { formatDate } = useDate();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: route('roles.index'),
    },
];

const props = defineProps<{
    roles: any;
}>();

const showDialog = ref(false);
const selectedRolePermissions = ref<string[]>([]);
const selectedRoleName = ref('');

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('roles.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function viewRole(roleId: string) {
    const role = props.roles.data.find((r) => r.id === roleId);
    if (role) {
        selectedRoleName.value = role.name;
        selectedRolePermissions.value = role.permissions;
        showDialog.value = true;
    }
}

function deleteRole(id: string) {
    confirmDelete('roles.destroy', id);
}
</script>

<template>
    <Head title="Roles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('roles.create')" :on-filter-change="onFilterChange" :show-used-filter="false" />

            <div class="mt-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Created At</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="role in roles.data" :key="role.id">
                            <TableCell>{{ role.name }}</TableCell>
                            <TableCell>{{ formatDate(role.created_at) }}</TableCell>
                            <TableCell class="flex gap-2">
                                <Button variant="warning" type="button" @click="viewRole(role.id)"><Eye />View</Button>
                                <Link :href="route('roles.edit', role)">
                                    <Button type="button" variant="primary"><SquarePen />Edit</Button>
                                </Link>
                                <Button variant="danger" type="button" @click="deleteRole(role.id)"><Trash2 />Delete</Button>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="roles.total === 0">
                            <TableCell colspan="5" class="text-center">No roles found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <Dialog v-model:open="showDialog">
                    <DialogContent class="max-w-md space-y-4 border bg-white p-6 shadow-xl" :style="{ zIndex: 9999 }">
                        <DialogTitle>Permissions for {{ selectedRoleName }}</DialogTitle>

                        <!-- Scrollable permissions list -->
                        <div class="max-h-40 overflow-y-auto pr-2">
                            <ul class="list-disc space-y-1 pl-5 text-sm">
                                <li v-for="(permission, index) in selectedRolePermissions" :key="index">
                                    {{ permission.name }}
                                </li>
                            </ul>
                        </div>

                        <div class="text-right">
                            <Button variant="secondary" @click="showDialog = false">Close</Button>
                        </div>
                    </DialogContent>
                </Dialog>
                <div v-if="roles.total > roles.per_page" class="row align-items-center mt-4">
                    <div class="ml-auto">
                        <Pagination :pagination="roles.links" />
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Showing {{ roles.from }} to {{ roles.to }} of {{ roles.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

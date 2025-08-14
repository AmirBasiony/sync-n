<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import TableToolbar from '@/components/TableToolbar.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Eye, Trash2 } from 'lucide-vue-next';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useDate } from '@/composables/useDate';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Permissions',
        href: route('permissions.index'),
    },
];

const props = defineProps<{
    permissions: any;
}>();

const { confirmDelete } = useConfirmDelete();
const { formatDate } = useDate();

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('permissions.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

const showDialog = ref(false);
const selectedPermissionRoles = ref<string[]>([]);
const selectedPermissionName = ref('');

function viewPermission(permissionId: string) {
    const permission = props.permissions.data.find((p) => p.id === permissionId);
    if (permission) {
        selectedPermissionName.value = permission.name;
        selectedPermissionRoles.value = permission.roles;
        showDialog.value = true;
    }
}

function deletePermission(id: string) {
    confirmDelete('permissions.destroy', id);
}
</script>

<template>
    <Head title="Permissions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('permissions.create')" :on-filter-change="onFilterChange" :show-used-filter="false" />

            <div class="mt-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Permission</TableHead>
                            <TableHead>Created At</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="permission in permissions.data" :key="permission.id">
                            <TableCell>{{ permission.name }}</TableCell>
                            <TableCell>{{ formatDate(permission.created_at) }}</TableCell>
                            <TableCell class="flex gap-2">
                                <Button variant="warning" type="button" @click="viewPermission(permission.id)"><Eye />View</Button>
                                <Button variant="danger" type="button" @click="deletePermission(permission.id)"><Trash2 />Delete</Button>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="permissions.total === 0">
                            <TableCell colspan="5" class="text-center">No permissions found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="row align-items-center mt-4">
                    <div class="ml-auto">
                        <Pagination :pagination="permissions.links" />
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Showing {{ permissions.from }} to {{ permissions.to }} of {{ permissions.total }} entries</div>
                    </div>
                </div>
                <Dialog v-model:open="showDialog">
                    <DialogContent class="max-w-md space-y-4 border bg-white p-6 shadow-xl" :style="{ zIndex: 9999 }">
                        <DialogTitle>Permissions for {{ selectedPermissionName }}</DialogTitle>

                        <!-- Scrollable permissions list -->
                        <div class="max-h-40 overflow-y-auto pr-2">
                            <ul class="list-disc space-y-1 pl-5 text-sm">
                                <li v-for="(role, index) in selectedPermissionRoles" :key="index">
                                    {{ role.name }}
                                </li>
                            </ul>
                        </div>

                        <div class="text-right">
                            <Button variant="secondary" @click="showDialog = false">Close</Button>
                        </div>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    </AppLayout>
</template>

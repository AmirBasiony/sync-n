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
    contentHubs: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Content Hubs',
        href: route('content-hubs.index'),
    },
];

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('content-hubs.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteUser(id: string) {
    confirmDelete('content-hubs.destroy', id);
}
</script>

<template>
    <Head title="Content Hub" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('content-hubs.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

            <div class="panel panel-bordered mx-4">
                <div class="panel-heading d-flex justify-content-between align-items-center"></div>

                <div class="panel-body">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Category Name</TableHead>
                                <TableHead>title</TableHead>
                                <TableHead>subtitle</TableHead>
                                <TableHead>image</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="contentHub in contentHubs.data" :key="contentHub.id">
                                <TableCell>{{ contentHub.category_name }}</TableCell>
                                <TableCell>{{ contentHub.title }}</TableCell>
                                <TableCell>{{contentHub.subtitle }}</TableCell>
                                <TableCell>
                                    <img :src="contentHub.image_url" alt="contentHub image" class="w-20 h-20 object-cover rounded" />
                                </TableCell>
                                <TableCell class="flex gap-2">
                                    <Link :href="route('content-hubs.edit', contentHub.id)">
                                        <Button size="sm" variant="secondary">
                                            <SquarePen class="w-4 h-4 mr-1" /> Edit
                                        </Button>
                                    </Link>
                                    <Button variant="ghost" size="sm"  @click="deleteUser(contentHub.id)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="contentHubs.total === 0">
                                <TableCell colspan="5" class="text-center">No content hubs found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-if="contentHubs.total > contentHubs.per_page" class="row align-items-center mt-4">
                        <Pagination :pagination="contentHubs.links" />
                        <div class="small text-muted">Showing {{ contentHubs.from }} to {{ contentHubs.to }} of {{ contentHubs.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

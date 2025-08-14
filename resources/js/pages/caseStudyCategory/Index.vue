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
    categories: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'CaseStudyCategory',
        href: route('category.index'),
    },
];

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('category.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteUser(id: string) {
    confirmDelete('category.destroy', id);
}
</script>

<template>
    <Head title="Case Study Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('category.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

            <div class="panel panel-bordered mx-4">
                <div class="panel-heading d-flex justify-content-between align-items-center"></div>

                <div class="panel-body">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>title</TableHead>
                                <TableHead>description</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="category in categories.data" :key="category.id">
                                <TableCell>{{ category.title }}</TableCell>
                                <TableCell>{{ category.description }}</TableCell>
                                <TableCell class="flex gap-2">
                                    <Link :href="route('category.edit', category.id)">
                                        <Button size="sm" variant="secondary">
                                            <SquarePen class="w-4 h-4 mr-1" /> Edit
                                        </Button>
                                    </Link>
                                    <Button variant="ghost" size="sm"  @click="deleteUser(category.id)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="categories.total === 0">
                                <TableCell colspan="5" class="text-center">No Case Study Category found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-if="categories.total > categories.per_page" class="row align-items-center mt-4">
                        <Pagination :pagination="categories.links" />
                        <div class="small text-muted">Showing {{ categories.from }} to {{ categories.to }} of {{ categories.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

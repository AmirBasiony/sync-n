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
    faqs: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Faqs',
        href: route('faqs.index'),
    },
];

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('faqs.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteUser(id: string) {
    confirmDelete('faqs.destroy', id);
}
</script>

<template>
    <Head title="faqs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('faqs.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

            <div class="panel panel-bordered mx-4">
                <div class="panel-heading d-flex justify-content-between align-items-center"></div>

                <div class="panel-body">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>question</TableHead>
                                <TableHead>answer</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="faq in faqs.data" :key="faq.id">
                                <TableCell class="w-90 max-w-100 truncate">{{ faq.question }}</TableCell>
                                <TableCell class="w-90 max-w-100 truncate">{{ faq.answer }}</TableCell>
                                <TableCell class="flex gap-2">
                                    <Link :href="route('faqs.edit', faq.id)">
                                        <Button size="sm" variant="secondary">
                                            <SquarePen class="w-4 h-4 mr-1" /> Edit
                                        </Button>
                                    </Link>
                                    <Button variant="ghost" size="sm"  @click="deleteUser(faq.id)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="faqs.total === 0">
                                <TableCell colspan="5" class="text-center">No faqs found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-if="faqs.total > faqs.per_page" class="row align-items-center mt-4">
                        <Pagination :pagination="faqs.links" />
                        <div class="small text-muted">Showing {{ faqs.from }} to {{ faqs.to }} of {{ faqs.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

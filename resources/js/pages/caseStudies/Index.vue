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
    caseStudies: any,
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Case Studies',
        href: route('case-studies.index'),
    },
];

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('case-studies.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteUser(id: string) {
    confirmDelete('case-studies.destroy', id);
}
</script>

<template>
    <Head title="Blog" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('case-studies.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

            <div class="panel panel-bordered mx-4">
                <div class="panel-heading d-flex justify-content-between align-items-center"></div>

                <div class="panel-body">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>category name</TableHead>
                                <TableHead>title</TableHead>
                                <TableHead>author</TableHead>
                                <TableHead>image</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="caseStudy in caseStudies.data" :key="caseStudy.id">
                                <TableCell>{{ caseStudy.category?.title }}</TableCell>
                                <TableCell>{{ caseStudy.title }}</TableCell>
                                <TableCell>{{ caseStudy.author }}</TableCell>
                                <TableCell>
                                    <img :src="caseStudy.image_url" alt="caseStudy image" class="w-20 h-20 object-cover rounded" />
                                </TableCell>
                                <TableCell class="flex gap-2">
                                    <Link :href="route('case-studies.edit', caseStudy.id)">
                                        <Button size="sm" variant="secondary">
                                            <SquarePen class="w-4 h-4 mr-1" /> Edit
                                        </Button>
                                    </Link>
                                    <Button variant="ghost" size="sm"  @click="deleteUser(caseStudy.id)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="caseStudies.total === 0">
                                <TableCell colspan="5" class="text-center">No case studies found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-if="caseStudies.total > caseStudies.per_page" class="row align-items-center mt-4">
                        <Pagination :pagination="caseStudies.links" />
                        <div class="small text-muted">Showing {{ caseStudies.from }} to {{ caseStudies.to }} of {{ caseStudies.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

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
    blogs: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'blogs',
        href: route('blogs.index'),
    },
];

function onFilterChange(filters: any) {
    const cleanFilters = Object.fromEntries(Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)) as any;
    router.get(route('blogs.index'), cleanFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}

function deleteUser(id: string) {
    confirmDelete('blogs.destroy', id);
}
</script>

<template>
    <Head title="Blog" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <TableToolbar :create-route="route('blogs.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

            <div class="panel panel-bordered mx-4">
                <div class="panel-heading d-flex justify-content-between align-items-center"></div>

                <div class="panel-body">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>title</TableHead>
                                <TableHead>subtitle</TableHead>
                                <TableHead>image</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="blog in blogs.data" :key="blog.id">
                                <TableCell>{{ blog.title }}</TableCell>
                                <TableCell>{{ blog.subtitle }}</TableCell>
                                <TableCell>
                                    <img :src="blog.image_url" alt="blog image" class="w-20 h-20 object-cover rounded" />
                                </TableCell>
                                <TableCell class="flex gap-2">
                                    <Link :href="route('blogs.edit', blog.id)">
                                        <Button size="sm" variant="secondary">
                                            <SquarePen class="w-4 h-4 mr-1" /> Edit
                                        </Button>
                                    </Link>
                                    <Button variant="ghost" size="sm"  @click="deleteUser(blog.id)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="blogs.total === 0">
                                <TableCell colspan="5" class="text-center">No blogs found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-if="blogs.total > blogs.per_page" class="row align-items-center mt-4">
                        <Pagination :pagination="blogs.links" />
                        <div class="small text-muted">Showing {{ blogs.from }} to {{ blogs.to }} of {{ blogs.total }} entries</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

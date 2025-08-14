<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import TableToolbar from '@/components/TableToolbar.vue';
import Pagination from '@/components/Pagination.vue';
import { SquarePen, Trash2 } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

const { confirmDelete } = useConfirmDelete();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Types',
    href: route('types.index'),
  },
];

const props = defineProps<{
  types: any;
}>();

function onFilterChange(filters: any) {
  const cleanFilters = Object.fromEntries(
    Object.entries(filters).filter(([_, value]) => value !== '' && value !== null)
  ) as any;

  router.get(route('types.index'), cleanFilters, {
    preserveState: true,
    replace: true,
    preserveScroll: true,
  });
}

function deleteType(id: string) {
  confirmDelete('types.destroy', id);
}
</script>

<template>
  <Head title="Types" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-6">
      <TableToolbar
        :create-route="route('types.create')"
        :on-filter-change="onFilterChange"
        :show-used-filter="false"
      />

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Name</TableHead>
            <TableHead>Description</TableHead>
            <TableHead>Created At</TableHead>
            <TableHead class="text-right">Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="type in types.data" :key="type.id">
            <TableCell>{{ type.name }}</TableCell>
            <TableCell>{{ type.description }}</TableCell>
            <TableCell>{{ type.created_at }}</TableCell>
            <TableCell class="flex justify-end gap-2">
              <Link :href="route('types.edit', type.id)">
                <Button size="sm" variant="secondary">
                  <SquarePen class="w-4 h-4 mr-1" /> Edit
                </Button>
              </Link>
              <Button size="sm" variant="ghost" @click="deleteType(type.id)">
                <Trash2 class="w-4 h-4 mr-1" /> 
              </Button>
            </TableCell>
          </TableRow>
          <TableRow v-if="types.data.length === 0">
            <TableCell colspan="4" class="text-center py-4">
              No types found.
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <div v-if="types.total > types.per_page" class="flex items-center justify-between mt-4">
        <Pagination :pagination="types.links" />
        <div class="text-sm text-muted-foreground">
          Showing {{ types.from }} to {{ types.to }} of {{ types.total }} entries
        </div>
      </div>
    </div>
  </AppLayout>
</template>

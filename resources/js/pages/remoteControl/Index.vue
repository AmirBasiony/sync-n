<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import TableToolbar from '@/components/TableToolbar.vue';
import { Trash2 } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';

import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { PlusCircle, Info } from 'lucide-vue-next';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/components/ui/table';

import { useConfirmDelete } from '@/composables/useConfirmDelete';

 const { confirmDelete } = useConfirmDelete();

function deleteRemote(remote: any) {
  confirmDelete(async () => {
    router.delete(route('remoteControl.destroy', remote._id), {
      preserveScroll: true,
      onSuccess: () => {
        showDialog.value = false;
      },
    });
  });
}


const props = defineProps<{
  remotes: any[];
  types: any[];
}>();

const search = ref('');
const selectedRemote = ref(null);
const showDialog = ref(false);

function getTypeName(typeId: string) {
  const type = props.types.find(t => t._id === typeId || t.id === typeId);
  return typeof type?.name === 'object' ? type.name.en : type?.name ?? '—';
}

function viewAttributes(remote: any) {
  selectedRemote.value = remote;
  showDialog.value = true;
}

const filteredRemotes = computed(() => {
  if (!search.value) return props.remotes;
  return props.remotes.filter((remote) =>
    remote.brand?.toLowerCase().includes(search.value.toLowerCase()) ||
    remote.model?.toLowerCase().includes(search.value.toLowerCase())
  );
});

function goToCreate() {
  router.get(route('remoteControl.create'));
}
</script>

<template>
  <Head title="Remote Controllers" />

  <AppLayout>
    <div class="p-6 space-y-6">
        
      
   <TableToolbar :create-route="route('remoteControl.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />


      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Type</TableHead>
            <TableHead>Brand</TableHead>
            <TableHead>Model</TableHead>
            <TableHead class="text-right">Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="remote in filteredRemotes" :key="remote._id">
            <TableCell>{{ getTypeName(remote.type_id) }}</TableCell>
            <TableCell>{{ remote.brand }}</TableCell>
            <TableCell>{{ remote.model }}</TableCell>
            <TableCell class="text-right">
              <Button variant="outline" size="sm" @click="viewAttributes(remote)">
                <Info class="w-4 h-4 mr-1" />
                
              </Button>

              <Button variant="ghost" size="sm" @click="deleteRemote(remote)">
    <Trash2 class="w-4 h-4 mr-1" />

  </Button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

     
      <!-- <Pagination /> -->

     <Dialog v-model:open="showDialog">
  <DialogContent class="max-w-md">
    <DialogHeader>
      <DialogTitle>Remote Attributes</DialogTitle>
    </DialogHeader>

    

    <div v-if="Array.isArray(selectedRemote?.attributes)">
      <div class="grid gap-3">
        <div
          v-for="(attr, index) in selectedRemote.attributes"
          :key="index"
          class="rounded-lg border p-4 shadow-sm bg-muted/50"
        >
          <div class="text-sm font-medium text-muted-foreground mb-1">
            {{ attr.key }}
          </div>
          <div class="text-base font-semibold text-foreground">
            {{ attr.value }}
          </div>
        </div>
      </div>
    </div>
    <div v-else class="text-gray-500">No attributes found.</div>
  </DialogContent>
</Dialog>

    </div>
  </AppLayout>
</template>

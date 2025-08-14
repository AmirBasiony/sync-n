<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectItem,
} from '@/components/ui/select';
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableBody,
  TableCell,
} from '@/components/ui/table';
import { Trash2 } from 'lucide-vue-next';

const props = defineProps<{
  types: any[];
}>();

const form = useForm({
  type_id: '',
  name: '',
  attributes: [],
});

function addRow() {
  form.attributes.push({ key: '', value: '' });
}

function deleteRow(index: number) {
  form.attributes.splice(index, 1);
}

function submit() {
  form.post(route('subtypes.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
}

const breadcrumbs = [
  { title: 'Widget', href: route('subtypes.index') },
  { title: 'Create', href: null },
];
</script>

<template>
  <Head title="Create Widget" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 w-full space-y-6">
      <div class="space-y-4">
        <!-- Select Type -->
        <div class="space-y-1">
          <Label>Widget Type</Label>
          <Select v-model="form.type_id">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Choose type" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="type in types" :key="type.id" :value="type.id">
                {{ typeof type.name === 'object' ? type.name.en : type.name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Widget Name -->
        <div class="space-y-1">
          <Label>Widget Name</Label>
          <Input v-model="form.name" placeholder="Enter name" />
        </div>

        <!-- Attributes Table -->
        <div class="space-y-2">
          <Button type="button" variant="outline" @click="addRow">
            + Add Attribute
          </Button>
          <Table class="mt-4">
            <TableHeader>
              <TableRow>
                <TableHead>Key</TableHead>
                <TableHead>Value</TableHead>
                <TableHead>Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="(attr, index) in form.attributes"
                :key="index"
              >
                <TableCell>
                  <Input v-model="attr.key" placeholder="Key" />
                </TableCell>
                <TableCell>
                  <Input v-model="attr.value" placeholder="Value" />
                </TableCell>
                <TableCell>
                  <Button
                    variant="ghost"
                    size="sm"
                    type="button"
                    @click="deleteRow(index)"
                  >
                    <Trash2 class="mr-1 w-4 h-4" /> 
                  </Button>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Save Button -->
        <div class="text-right pt-4">
          <Button @click="submit" class="px-10">Save Widget</Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

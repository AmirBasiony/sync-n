<script setup lang="ts">
import { ref, watch } from 'vue';
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
  brand: '',
  model: '',
  type_id: '',
  attributes: [],
});

// ✅ حدد النوع الافتراضي "IRController"
const defaultType = props.types.find((t) =>
  typeof t.name === 'object'
    ? t.name.en === 'IRController'
    : t.name === 'IRController'
);
if (defaultType) {
  form.type_id = defaultType.id ?? defaultType._id?.toString();
}

// ✨ إضافة و حذف الصفوف
function addRow() {
  form.attributes.push({ key: '', value: '' });
}

function deleteRow(index: number) {
  form.attributes.splice(index, 1);
}

// ✉️ إرسال النموذج
function submit() {
  form.post(route('remoteControl.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
}

// ✅ مراقبة لاختبار اختيار النوع
watch(() => form.type_id, (val) => {
  console.log('✅ Selected type_id:', val);
});

const breadcrumbs = [
  { title: 'Remote Control', href: route('remoteControl.index') },
  { title: 'Create', href: null },
];
</script>

<template>
  <Head title="Create Remote Controller" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 w-full space-y-6">
      <div class="space-y-4">
        <!-- Select Type -->
        <div class="space-y-1">
          <Label>Widget Type</Label>
          <Select :modelValue="form.type_id" @update:modelValue="form.type_id = $event">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Choose type" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="type in types"
                :key="type.id ?? type._id"
                :value="type.id ?? type._id?.toString()"
              >
                {{ typeof type.name === 'object' ? type.name.en : type.name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Brand -->
        <div class="space-y-1">
          <Label>Brand</Label>
          <Input v-model="form.brand" placeholder="e.g. Sharp" />
        </div>

        <!-- Model -->
        <div class="space-y-1">
          <Label>Model</Label>
          <Input v-model="form.model" placeholder="e.g. A115" />
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
              <TableRow v-for="(attr, index) in form.attributes" :key="index">
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
          <Button @click="submit" class="px-10">Save Remote Controller</Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

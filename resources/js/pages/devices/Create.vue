<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import axios from 'axios';

const props = defineProps({
  types: Array,
});

const form = useForm({
  name: '',
  type_id: '',
  selected_widgets: [] as string[],
});


const widgets = ref([]);
const loading = ref(false);

const breadcrumbs = [
  { title: 'Subtypes', href: route('subtypes.index') },
  { title: 'Create', href: null },
];

watch(() => form.type_id, async (newTypeId) => {
  if (!newTypeId) {
    widgets.value = [];
    return;
  }

  loading.value = true;
  try {
    const res = await axios.get(`/subtypes/widgets-by-type/${newTypeId}`);
    widgets.value = res.data;
  } catch (err) {
    console.error('Failed to fetch widgets:', err);
  } finally {
    loading.value = false;
  }
});

function submit() {
  form
    .transform((data) => ({
      ...data,
      selected_widgets: form.selected_widgets,
    }))
    .post(route('devices.store'), {
      preserveScroll: true,
      onSuccess: () => form.reset(),
    });
}
</script>

<template>
  <Head title="Create Subtype" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-12 space-y-12 max-w-2xl">

      <!-- Device Name -->
      <div class="space-y-1">
        <Label for="name">Device Name</Label>
        <Input id="name" v-model="form.name" placeholder="Enter device name" />
      </div>

      <!-- Device Type -->
      <div class="space-y-1">
        <Label>Select Type</Label>
        <Select v-model="form.type_id">
          <SelectTrigger>
            <SelectValue placeholder="Choose Type" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem
              v-for="type in props.types"
              :key="type.id"
              :value="type.id"
            >
              {{ typeof type.name === 'object' ? type.name[$page.props.locale || 'en'] : type.name || 'Unnamed Type' }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>

      <!-- Loading Indicator -->
      <div v-if="loading" class="text-sm text-muted-foreground">Loading widgets...</div>

      <!-- No widgets -->
      <div v-if="!loading && widgets.length === 0" class="text-sm text-red-500">
        No widgets found for this type.
      </div>

      <!-- Widgets Selection -->
      <div v-if="widgets.length > 0" class="space-y-3">
        <Label>Select Widgets</Label>
        <div class="space-y-2">
          <div
            v-for="widget in widgets"
            :key="widget.id"
            class="flex items-center space-x-2 border p-2 rounded-lg"
          >
          <input
  type="checkbox"
  :id="`widget-${widget.id}`"
  :value="widget.id"
  v-model="form.selected_widgets"
/>

<Label :for="`widget-${widget.id}`">
  {{ widget.name || 'Unnamed Widget' }}
</Label>

          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <Button @click="submit" class="w-full">
          Save Device
        </Button>
      </div>

    </div>
  </AppLayout>
</template>

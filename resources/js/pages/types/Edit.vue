<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
  Form,
  FormField,
  FormItem,
  FormLabel,
  FormControl,
  FormError
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
  type: {
    id: string;
    name: string;
    description: string;
  };
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Types', href: route('types.index') },
  { title: 'Edit Type', href: '#' },
];

const form = useForm({
  name: props.type.name,
  description: props.type.description,
});

const submit = () => {
  form.put(route('types.update', props.type.id), {
    preserveScroll: true,
  });
};
</script>

<template>
  <Head title="Edit Type" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="w-full p-6">
      <h2 class="text-2xl font-semibold mb-6">Edit Type</h2>

      <Form @submit="submit" class="space-y-6">
        <!-- Name -->
        <FormField name="name">
          <FormItem>
            <FormLabel>Name</FormLabel>
            <FormControl>
              <Input v-model="form.name" required />
            </FormControl>
            <FormError :message="form.errors.name" />
          </FormItem>
        </FormField>

        <!-- Description -->
        <FormField name="description">
          <FormItem>
            <FormLabel>Description</FormLabel>
            <FormControl>
              <Textarea v-model="form.description" rows="4" />
            </FormControl>
            <FormError :message="form.errors.description" />
          </FormItem>
        </FormField>

        <div class="flex justify-end gap-4 pt-4">
          <Link :href="route('types.index')">
            <Button type="button" variant="outline">Cancel</Button>
          </Link>
          <Button type="submit" variant="success" :disabled="form.processing">
            Update
          </Button>
        </div>
      </Form>
    </div>
  </AppLayout>
</template>

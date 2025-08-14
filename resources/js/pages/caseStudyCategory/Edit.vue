<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    category: {
        id: number;
        title: string;
        description: string;
    };
}>();

const form = useForm({
    title: props.category.title ?? '',
    description: props.category.description ?? '',
});

const submit = () => {
    form.put(route('category.update', props.category.id), {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: ' Categories', href: route('category.index') },
    { title: 'Edit', href: '#' },
];
</script>


<template>
    <Head title="Edit category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <h3 class="font-bold text-lg">Edit Blog</h3>

            <div class="mt-4">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Title -->
                        <FormField name="title">
                            <FormItem>
                                <FormLabel>Title</FormLabel>
                                <FormControl>
                                    <Input v-model="form.title"  />
                                </FormControl>
                                <FormError :message="form.errors.title" />
                            </FormItem>
                        </FormField>

                        <!-- Subtitle -->
                        <FormField name="description">
                            <FormItem>
                                <FormLabel>description</FormLabel>
                                <FormControl>
                                    <Textarea rows="5" v-model="form.description"  />
                                </FormControl>
                                <FormError :message="form.errors.description" />
                            </FormItem>
                        </FormField>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('category.index')">
                            <Button type="button" class="w-64">Cancel</Button>
                        </Link>
                        <Button variant="success" class="w-64" :disabled="form.processing">Update</Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>


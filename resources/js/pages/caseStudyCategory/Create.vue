<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { Textarea } from '@/components/ui/textarea';


const form = useForm({
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('category.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Case Study Category', href: route('category.index') },
    { title: 'Create', href: '#' },
];
</script>


<template>
    <Head title="Create Case Study Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <h3 class="font-bold text-lg">Create New Case Study Category</h3>

            <div class="mt-4">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Title -->
                        <FormField name="title">
                            <FormItem>
                                <FormLabel>Title</FormLabel>
                                <FormControl>
                                    <Input v-model="form.title" type="text" placeholder="Case Study Category Title" required />
                                </FormControl>
                                <FormError :message="form.errors.title" />
                            </FormItem>
                        </FormField>

                        <!-- Subtitle -->
                        <FormField name="description">
                            <FormItem>
                                <FormLabel>description</FormLabel>
                                <FormControl>
                                    <Textarea v-model="form.description" rows="5" placeholder="Case Study Category description" required />
                                </FormControl>
                                <FormError :message="form.errors.description" />
                            </FormItem>
                        </FormField>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('category.index')">
                            <Button class="w-64" type="button">Cancel</Button>
                        </Link>
                        <Button :disabled="form.processing" type="submit" variant="success" class="w-64">
                            <Button type="submit" variant="success" class="w-64" :disabled="form.processing">Create</Button>
                        </Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>


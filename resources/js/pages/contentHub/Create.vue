<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { Textarea } from '@/components/ui/textarea';

const form = useForm({
    category_name: '',
    title: '',
    subtitle: '',
    content: '',
    image: null,
})

const submit = () => {
    form.post(route('content-hubs.store'), {
        preserveScroll: true,
        forceFormData: true, // مهم عشان نرفع ملف
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Content Hub', href: route('content-hubs.index') },
    { title: 'Create', href: '#' },
];
</script>


<template>
    <Head title="Create Content Hub" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <h3 class="font-bold text-lg">Create New content hubs</h3>

            <div class="mt-4">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- category_name -->
                        <div>
                            <label for="category_name" class="block font-semibold">Category Name</label>
                            <input
                                id="category_name"
                                v-model="form.category_name"
                                type="text"
                                class="w-full mt-1 border rounded px-3 py-2"
                            />
                            <FormError :message="form.errors.category_name" />
                        </div>
                        <!-- Title -->
                        <FormField name="title">
                            <FormItem>
                                <FormLabel>Title</FormLabel>
                                <FormControl>
                                    <Input v-model="form.title" type="text" placeholder="Content Hub Title" required />
                                </FormControl>
                                <FormError :message="form.errors.title" />
                            </FormItem>
                        </FormField>

                        <!-- Subtitle -->
                        <FormField name="subtitle">
                            <FormItem>
                                <FormLabel>Subtitle</FormLabel>
                                <FormControl>
                                    <Input v-model="form.subtitle" type="text" placeholder="Content Hub Subtitle" required />
                                </FormControl>
                                <FormError :message="form.errors.subtitle" />
                            </FormItem>
                        </FormField>

                        <!-- Content -->
                        <FormField name="content">
                            <FormItem class="md:col-span-2">
                                <FormLabel>Content</FormLabel>
                                <FormControl>
                                    <Textarea v-model="form.content" placeholder="Content Hub Content" rows="5" required />
                                </FormControl>
                                <FormError :message="form.errors.content" />
                            </FormItem>
                        </FormField>

                        <!-- Image -->
                        <FormField name="image">
                            <FormItem class="md:col-span-2">
                                <FormLabel>Image</FormLabel>
                                <FormControl>
                                    <Input
                                        type="file"
                                        accept="image/*"
                                        @change="form.image = $event.target.files[0]"
                                        required
                                    />
                                </FormControl>
                                <FormError :message="form.errors.image" />
                            </FormItem>
                        </FormField>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('content-hubs.index')">
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


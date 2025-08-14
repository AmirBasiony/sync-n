<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    blog: {
        id: number;
        title: string;
        subtitle: string;
        content: string;
        image: string;
        image_url?: string;
    };
}>();

const form = useForm({
    title: props.blog.title ?? '',
    subtitle: props.blog.subtitle ?? '',
    content: props.blog.content ?? '',
    image: null as File | null,
});

const submit = () => {
    form.post(route('blogs.update', props.blog.id), {
        preserveScroll: true,
        forceFormData: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Blogs', href: route('blogs.index') },
    { title: 'Edit', href: '#' },
];
</script>


<template>
    <Head title="Edit Blog" />

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
                        <FormField name="subtitle">
                            <FormItem>
                                <FormLabel>Subtitle</FormLabel>
                                <FormControl>
                                    <Input v-model="form.subtitle"  />
                                </FormControl>
                                <FormError :message="form.errors.subtitle" />
                            </FormItem>
                        </FormField>

                        <!-- Content -->
                        <FormField name="content">
                            <FormItem class="md:col-span-2">
                                <FormLabel>Content</FormLabel>
                                <FormControl>
                                    <Textarea v-model="form.content" rows="5"  />
                                </FormControl>
                                <FormError :message="form.errors.content" />
                            </FormItem>
                        </FormField>

                        <!-- New Image -->
                        <FormField name="image">
                            <div class="md:col-span-2" v-if="props.blog.image_url">
                                <FormLabel>Current Image</FormLabel>
                                <img :src="props.blog.image_url" alt="Current Image" class="mt-2 max-h-48 rounded border" />
                            </div>
                            <FormItem class="md:col-span-2">
                                <FormLabel>Change Image</FormLabel>
                                <FormControl>
                                    <Input
                                        type="file"
                                        accept="image/*"
                                        @change="form.image = $event.target.files[0]"
                                    />
                                </FormControl>
                                <FormError :message="form.errors.image" />
                            </FormItem>
                        </FormField>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('blogs.index')">
                            <Button type="button" class="w-64">Cancel</Button>
                        </Link>
                        <Button variant="success" class="w-64" :disabled="form.processing">Update</Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>


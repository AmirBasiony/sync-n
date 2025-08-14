<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { string } from 'zod';

const props = defineProps<{
    caseStudy: {
        id: number;
        category_id:string;
        title: string;
        author: string;
        content: string;
        image_url?: string;
    };
    categories: any,
}>();

const form = useForm({
    category_id: String(props.caseStudy.category_id),
    title: props.caseStudy.title ?? '',
    author: props.caseStudy.author ?? '',
    content: props.caseStudy.content ?? '',
    image: null as File | null,
});

const submit = () => {
    form.post(route('case-studies.update', props.caseStudy.id), {
        preserveScroll: true,
        forceFormData: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'case studies', href: route('case-studies.index') },
    { title: 'Edit', href: '#' },
];
</script>


<template>
    <Head title="Edit case studies" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <h3 class="font-bold text-lg">Edit case studies</h3>

            <div class="mt-4">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-------category_id------->
                        <FormField name="category_id">
                            <FormItem>
                                <FormLabel>Select category</FormLabel>
                                <Select v-model="form.category_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Choose category" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                                {{category.title}}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </FormItem>
                        </FormField>
                        <FormError :message="form.errors.category_id" />
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
                        <FormField name="author">
                            <FormItem>
                                <FormLabel>author</FormLabel>
                                <FormControl>
                                    <Input v-model="form.author"  />
                                </FormControl>
                                <FormError :message="form.errors.author" />
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
                            <div class="md:col-span-2" v-if="props.caseStudy.image_url">
                                <FormLabel>Current Image</FormLabel>
                                <img :src="props.caseStudy.image_url" alt="Current Image" class="mt-2 max-h-48 rounded border" />
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
                        <Link :href="route('case-studies.index')">
                            <Button type="button" class="w-64">Cancel</Button>
                        </Link>
                        <Button variant="success" class="w-64" :disabled="form.processing">Update</Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>


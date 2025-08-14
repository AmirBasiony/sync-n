<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const form = useForm({
    category_id:'',
    title: '',
    author: '',
    content: '',
    image: null as File | null,
});

const submit = () => {
    form.post(route('case-studies.store'), {
        preserveScroll: true,
        forceFormData: true, // مهم عشان نرفع ملف
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Case Studies', href: route('case-studies.index') },
    { title: 'Create', href: '#' },
];
const props = defineProps<{
    categories: any,
}>();
</script>


<template>
    <Head title="Create Case Studies" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <h3 class="font-bold text-lg">Create New Case Studies</h3>

            <div class="mt-4">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-1">
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
                            <FormError :message="form.errors.title" />
                        </div>
                        <!-- Title -->
                        <FormField name="title">
                            <FormItem>
                                <FormLabel>Title</FormLabel>
                                <FormControl>
                                    <Input v-model="form.title" type="text" placeholder="Case Studies Title" required />
                                </FormControl>
                                <FormError :message="form.errors.title" />
                            </FormItem>
                        </FormField>

                        <!-- author -->
                        <FormField name="author">
                            <FormItem>
                                <FormLabel>author</FormLabel>
                                <FormControl>
                                    <Input v-model="form.author" type="text" placeholder="Case Studies author" required />
                                </FormControl>
                                <FormError :message="form.errors.author" />
                            </FormItem>
                        </FormField>

                        <!-- Content -->
                        <FormField name="content">
                            <FormItem class="md:col-span-2">
                                <FormLabel>Content</FormLabel>
                                <FormControl>
                                    <Textarea v-model="form.content" placeholder="Case Studies Content" rows="5" required />
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
                        <Link :href="route('case-studies.index')">
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


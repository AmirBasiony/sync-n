<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    faqs: {
        id: string;
        question: string;
        answer: string;
    };
}>();

const form = useForm({
    question: props.faqs.question ?? '',
    answer: props.faqs.answer ?? '',
});

const submit = () => {
    form.put(route('faqs.update', props.faqs.id), {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Blogs', href: route('faqs.index') },
    { title: 'Edit', href: '#' },
];
</script>


<template>
    <Head title="Edit faqs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <h3 class="font-bold text-lg">Edit faqs</h3>

            <div class="mt-4">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Title -->
                        <FormField name="question">
                            <FormItem>
                                <FormLabel>question</FormLabel>
                                <FormControl>
                                    <Input v-model="form.question"  />
                                </FormControl>
                                <FormError :message="form.errors.question" />
                            </FormItem>
                        </FormField>

                        <!-- answer -->
                        <FormField name="answer">
                            <FormItem>
                                <FormLabel>answer</FormLabel>
                                <FormControl>
                                    <Input v-model="form.answer"  />
                                </FormControl>
                                <FormError :message="form.errors.answer" />
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


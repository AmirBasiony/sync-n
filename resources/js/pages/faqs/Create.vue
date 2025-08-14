<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { Textarea } from '@/components/ui/textarea';


const form = useForm({
    question: '',
    answer: '',
});

const submit = () => {
    form.post(route('faqs.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Faqs', href: route('faqs.index') },
    { title: 'Create', href: '#' },
];
</script>


<template>
    <Head title="Create Faqs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <h3 class="font-bold text-lg">Create New Faqs</h3>

            <div class="mt-4">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Title -->
                        <FormField name="question">
                            <FormItem>
                                <FormLabel>question</FormLabel>
                                <FormControl>
                                    <Textarea v-model="form.question" rows="5" placeholder="Faqs question" required />
                                </FormControl>
                                <FormError :message="form.errors.question" />
                            </FormItem>
                        </FormField>

                        <!-- Subtitle -->
                        <FormField name="description">
                            <FormItem>
                                <FormLabel>answer</FormLabel>
                                <FormControl>
                                    <Textarea v-model="form.answer" rows="5" placeholder="Faqs answer" required />
                                </FormControl>
                                <FormError :message="form.errors.answer" />
                            </FormItem>
                        </FormField>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('faqs.index')">
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


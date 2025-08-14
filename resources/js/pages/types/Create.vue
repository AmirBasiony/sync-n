<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
});


const submit = () => {
    form.post(route('types.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Types', href: route('types.index') },
    { title: 'Create', href: route('types.create') },
];
</script>

<template>
    <Head title="Create Type" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container-fluid">
            <div class="panel panel-bordered mx-4">
                <div class="panel-heading">
                    <h3 class="panel-title font-bold">Create New Type:</h3>
                </div>

                <div class="panel-body mt-3">
                    <Form @submit="submit">

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <FormField name="name">
                                <FormItem>
                                    <FormLabel>Type Name</FormLabel>
                                    <FormControl>
                                    <Input v-model="form.name" placeholder="Type Name in English" required />
                                    </FormControl>
                                    <FormError :message="form.errors.name" />
                                </FormItem>
                                </FormField>

                                <FormField name="description">
                                <FormItem>
                                    <FormLabel>Description</FormLabel>
                                    <FormControl>
                                    <Textarea v-model="form.description" placeholder="Description in English" />
                                    </FormControl>
                                    <FormError :message="form.errors.description" />
                                </FormItem>
                                </FormField>

                        </div>

                        <div class="flex justify-center space-x-4 pt-6">
                            <Link :href="route('types.index')">
                                <Button type="button" class="w-64">Cancel</Button>
                            </Link>
                            <Button variant="success" class="w-64" type="submit" :disabled="form.processing">Create</Button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('permissions.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Permission', href: route('permissions.index') },
    { title: 'Create', href: '#' },
];
</script>

<template>
    <Head title="Create Permission" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container-fluid">
            <div class="panel panel-bordered mx-4">
                <div class="panel-heading">
                    <h3 class="panel-title font-bold py-3">Create New Permission:</h3>
                </div>

                <div class="panel-body mt-3">
                    <Form @submit="submit">
                        <!-- Grid layout for two-column form -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Name -->
                            <FormField name="name">
                                <FormItem>
                                    <FormLabel>Name</FormLabel>
                                    <FormControl>
                                        <Input type="text" placeholder="Permission name" v-model="form.name" required />
                                    </FormControl>
                                    <FormError :message="form.errors.name" />
                                </FormItem>
                            </FormField>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-center space-x-4 pt-6">
                            <Link :href="route('admins.index')">
                                <Button class="w-64 flex-initial" type="button">Cancel</Button>
                            </Link>
                            <Button
                                variant="success"
                                type="submit"
                                class="w-64 flex-initial"
                                :disabled="form.processing"
                                :class="{
                                    'cursor-pointer': !form.processing,
                                    'cursor-not-allowed': form.processing,
                                }"
                            >
                                Create
                            </Button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

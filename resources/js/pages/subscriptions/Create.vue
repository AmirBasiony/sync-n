<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    users: any;
    packages: any;
}>();

const form = useForm({
    user_id: '',
    package_id: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Subscriptions', href: route('subscriptions.index') },
    { title: 'Create', href: '#' },
];

const submit = () => form.post(route('subscriptions.store'));
</script>

<template>
    <Head title="Create Subscription" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <Form @submit="submit">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <FormField name="name">
                        <FormItem>
                            <FormLabel>Name</FormLabel>
                            <FormControl>
                                <Select v-model="form.user_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a user" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Users</SelectLabel>
                                            <SelectItem v-for="user in users" :key="user.id" :value="user.id">
                                                {{ user.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormError :message="form.errors.user_id" />
                        </FormItem>
                    </FormField>

                    <FormField name="packages">
                        <FormItem>
                            <FormLabel>Plans</FormLabel>
                            <FormControl>
                                <Select v-model="form.package_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a plan" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Packages</SelectLabel>
                                            <SelectItem v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                                {{ pkg.title_en }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormError :message="form.errors.package_id" />
                        </FormItem>
                    </FormField>
                </div>

                <div class="flex justify-center space-x-4 pt-6">
                    <Link :href="route('subscriptions.index')">
                        <Button class="w-64" type="button">Cancel</Button>
                    </Link>
                    <Button variant="success" type="submit" class="w-64" :disabled="form.processing">Create</Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>

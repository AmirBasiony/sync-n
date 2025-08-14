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
    email: '',
    password: '',
    role_id: '',
});

const submit = () => {
    form.post(route('admins.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admins', href: route('admins.index') },
    { title: 'Create', href: route('admins.create') },
];

const props = defineProps<{
    roles: any;
}>();
</script>

<template>
    <Head title="Create Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container-fluid">
            <div class="panel panel-bordered mx-4">
                <div class="panel-heading">
                    <h3 class="panel-title my-3 font-bold">Create New Admin:</h3>
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
                                        <Input type="text" placeholder="Admin name" v-model="form.name" required />
                                    </FormControl>
                                    <FormError :message="form.errors.name" />
                                </FormItem>
                            </FormField>

                            <!-- Email -->
                            <FormField name="email">
                                <FormItem>
                                    <FormLabel>Email</FormLabel>
                                    <FormControl>
                                        <Input type="email" placeholder="Admin email" autocomplete="off" v-model="form.email" required />
                                    </FormControl>
                                    <FormError :message="form.errors.email" />
                                </FormItem>
                            </FormField>

                            <!-- Password -->
                            <FormField name="password">
                                <FormItem>
                                    <FormLabel>Password</FormLabel>
                                    <FormControl>
                                        <Input type="password" placeholder="Admin password" v-model="form.password" autocomplete="off" required />
                                    </FormControl>
                                    <FormError :message="form.errors.password" />
                                </FormItem>
                            </FormField>

                            <!-- Role -->
                            <FormField name="role_id">
                                <FormItem>
                                    <FormLabel>Role</FormLabel>
                                    <FormControl>
                                        <Select v-model="form.role_id">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder: admin.role.name />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="role in props.roles" :key="role.id" :value="role.id">
                                                    {{ role.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </FormControl>
                                    <FormError :message="form.errors.role_id" />
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

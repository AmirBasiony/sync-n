<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admins.index') },
    { title: 'Edit', href: '#' },
];

const props = defineProps<{
    admin: any;
    roles: any;
}>();

const form = useForm({
    name: props.admin.name,
    email: props.admin.email,
    is_active: props.admin.is_active,
    address: props.admin.address,
    role_id: props.admin.role,
});

onMounted(() => {
    form.role_id = props.admin.roles?.length ? props.admin.roles[0].id : null;
    form.is_active = props.admin.is_active ? 'true' : 'false';
});

const submit = () => {
    form.is_active = form.is_active === 'true';
    form.put(route('admins.update', props.admin.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container-fluid">
            <div class="panel panel-bordered mx-4 pb-6">
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

                            <!-- Activation -->
                            <FormField name="is_active">
                                <FormItem>
                                    <FormLabel>Activation</FormLabel>
                                    <FormControl>
                                        <Select v-model="form.is_active">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Select Activation" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="true">Active</SelectItem>
                                                <SelectItem value="false">Inactive</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </FormControl>
                                    <FormError :message="form.errors.is_active" />
                                </FormItem>
                            </FormField>

                            <!-- Role -->
                            <FormField name="role_id">
                                <FormItem>
                                    <FormLabel>Role</FormLabel>
                                    <FormControl>
                                        <Select v-model="form.role_id">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Select Role" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="role in roles" :key="role.id" :value="role.id">
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
                                Update
                            </Button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

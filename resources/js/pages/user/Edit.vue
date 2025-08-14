<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import 'flatpickr/dist/flatpickr.css';
import Flatpickr from 'vue-flatpickr-component';
import { Switch } from '@/components/ui/switch';

const props = defineProps<{
    user: any;
}>();

const form = useForm({
    name: props.user.name ?? '',
    email: props.user.email ?? '',
    birthdate: props.user.birthdate ?? '',
    address: props.user.address ?? '',
    gender: props.user.gender ?? false,
    phone: props.user.phone ?? '',
    email_verified_at: props.user.email_verified_at ?? false,
});

const submit = () => {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: route('users.index') },
    { title: 'Edit', href: '#' },
];
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <div>
                <h3 class="font-bold">Edit User</h3>
            </div>

            <div class="mt-3">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <FormField name="name">
                            <FormItem>
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input v-model="form.name" required />
                                </FormControl>
                                <FormError :message="form.errors.name" />
                            </FormItem>
                        </FormField>

                        <FormField name="email">
                            <FormItem>
                                <FormLabel>Email</FormLabel>
                                <FormControl>
                                    <Input v-model="form.email" required />
                                </FormControl>
                                <FormMessage>{{ form.errors.email }}</FormMessage>
                                <FormError :message="form.errors.email" />
                            </FormItem>
                        </FormField>

                        <FormField name="address">
                            <FormItem>
                                <FormLabel>Address</FormLabel>
                                <FormControl>
                                    <Input v-model="form.address" />
                                </FormControl>
                                <FormMessage />
                                <FormError :message="form.errors.address" />
                            </FormItem>
                        </FormField>

                        <FormField name="gender">
                            <FormItem>
                                <FormLabel>Gender</FormLabel>
                                <FormControl>
                                    <RadioGroup v-model="form.gender" class="flex space-x-4">
                                        <FormItem class="flex items-center space-x-2">
                                            <FormControl>
                                                <RadioGroupItem value="male" />
                                            </FormControl>
                                            <FormLabel>Male</FormLabel>
                                        </FormItem>
                                        <FormItem class="flex items-center space-x-2">
                                            <FormControl>
                                                <RadioGroupItem value="female" />
                                            </FormControl>
                                            <FormLabel>Female</FormLabel>
                                        </FormItem>
                                    </RadioGroup>
                                </FormControl>
                                <FormError :message="form.errors.gender" />
                            </FormItem>
                        </FormField>

                        <FormField name="phone">
                            <FormItem>
                                <FormLabel>Phone</FormLabel>
                                <FormControl>
                                    <Input v-model="form.phone" />
                                </FormControl>
                                <FormError :message="form.errors.phone" />
                            </FormItem>
                        </FormField>

                        <FormField name="email_verified_at">
                            <FormItem class="flex items-center space-x-2">
                                <FormLabel>Active User</FormLabel>
                                <FormControl>
                                    <Switch v-model="form.email_verified_at" />
                                </FormControl>
                                <FormError :message="form.errors.email_verified_at" />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('users.index')">
                            <Button type="button" class="w-64">Cancel</Button>
                        </Link>
                        <Button variant="success" class="w-64" :disabled="form.processing">Update</Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>

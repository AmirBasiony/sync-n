<script setup lang="ts">
import { Button } from '@/components/ui/button';
// import { Checkbox } from '@/components/ui/checkbox';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import 'flatpickr/dist/flatpickr.css';
import Flatpickr from 'vue-flatpickr-component';
import { Switch } from "@/components/ui/switch"
const form = useForm({
    name: '',
    email: '',
    password: '',
    birthdate: '',
    address: '',
    gender: null,
    phone: '',
    email_verified_at: false,
});

const submit = () => {
    form.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: route('users.index') },
    { title: 'Create', href: '#' },
];
</script>

<template>
    <Head title="Create User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <div>
                <h3 class="font-bold">Create New User:</h3>
            </div>

            <div class="mt-3">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <FormField name="name">
                            <FormItem>
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input type="text" v-model="form.name" placeholder="User Name" required />
                                </FormControl>
                                <FormError :message="form.errors.name" />
                            </FormItem>
                        </FormField>

                        <FormField name="email">
                            <FormItem>
                                <FormLabel>Email</FormLabel>
                                <FormControl>
                                    <Input type="email" v-model="form.email" placeholder="User Email" required />
                                </FormControl>
                                <FormError :message="form.errors.email" />
                            </FormItem>
                        </FormField>

                        <FormField name="password">
                            <FormItem>
                                <FormLabel>Password</FormLabel>
                                <FormControl>
                                    <Input type="password" v-model="form.password" placeholder="User Password" required />
                                </FormControl>
                                <FormError :message="form.errors.password" />
                            </FormItem>
                        </FormField>

                        <FormField name="address">
                            <FormItem>
                                <FormLabel>Address</FormLabel>
                                <FormControl>
                                    <Input v-model="form.address" type="text" placeholder="User Address" />
                                </FormControl>
                                <FormError :message="form.errors.address" />
                            </FormItem>
                        </FormField>

                        <FormField name="gender">
                            <FormItem>
                                <FormLabel>Gender</FormLabel>
                                <FormControl>
                                    <RadioGroup v-model="form.gender" class="flex space-x-4">
                                        <FormItem class="flex items-center space-x-2">
                                            <RadioGroupItem value="male" />
                                            <FormLabel class="font-normal">Male</FormLabel>
                                        </FormItem>
                                        <FormItem class="flex items-center space-x-2">
                                            <RadioGroupItem value="female" />
                                            <FormLabel class="font-normal">Female</FormLabel>
                                        </FormItem>
                                    </RadioGroup>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="phone">
                            <FormItem>
                                <FormLabel>Phone</FormLabel>
                                <FormControl>
                                    <Input v-model="form.phone" type="tel" placeholder="User Phone" />
                                </FormControl>
                                <FormError :message="form.errors.phone" />
                            </FormItem>
                        </FormField>

                        <FormField name="email_verified_at">
                            <FormItem>
                                <FormLabel>Active User</FormLabel>
                                <FormControl>
                                    <Switch v-model="form.email_verified_at"/>
                                </FormControl>
                                <FormError :message="form.errors.email_verified_at" />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('users.index')">
                            <Button class="w-64" type="button">Cancel</Button>
                        </Link>
                        <Button type="submit" variant="success" class="w-64" :disabled="form.processing">Create</Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>

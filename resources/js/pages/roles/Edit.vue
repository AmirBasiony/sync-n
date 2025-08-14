<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    permissions: Array<{ id: string; name: string }>;
    role: {
        id: string;
        name: string;
        permissions: string[]; // array of permission IDs
    };
}>();

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions ?? [],
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: route('roles.index') },
    { title: 'Edit', href: '#' },
];

const checkAll = ref(false);
const search = ref('');

const filteredPermissions = computed(() =>
    props.permissions.filter((permission) => permission.name.toLowerCase().includes(search.value.toLowerCase())),
);

// Handle user toggling checkAll
watch(checkAll, (isChecked) => {
    const filteredIds = filteredPermissions.value.map((p) => p.id);

    if (isChecked) {
        // Add all visible permissions
        form.permissions = [...new Set([...form.permissions, ...filteredIds])];
    } else {
        // Remove all visible permissions
        form.permissions = form.permissions.filter((id) => !filteredIds.includes(id));
    }
});

// Sync checkAll state with current selections
watch(
    () => [filteredPermissions.value, form.permissions],
    () => {
        const filteredIds = filteredPermissions.value.map((p) => p.id);
        checkAll.value = filteredIds.length > 0 && filteredIds.every((id) => form.permissions.includes(id));
    },
    { deep: true },
);

// Sync form with role on edit
watch(
    () => props.role,
    (newRole) => {
        form.name = newRole.name;
        form.permissions = [...newRole.permissions];
    },
    { immediate: true },
);
const submit = () => {
    form.put(route('roles.update', props.role.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <div>
                <h3 class="panel-title my-3 font-bold">Edit Role:</h3>
            </div>

            <div class="mt-3">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <FormField name="name">
                            <FormItem>
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input type="text" placeholder="Role name" v-model="form.name" required />
                                </FormControl>
                                <FormError :message="form.errors.name" />
                            </FormItem>
                        </FormField>
                        <FormField name="permissions">
                            <FormItem>
                                <FormLabel>Permissions</FormLabel>
                                <FormControl>
                                    <div class="max-h-60 space-y-2 overflow-y-auto rounded-md border p-3">
                                        <input
                                            type="text"
                                            v-model="search"
                                            placeholder="Search..."
                                            class="mb-2 w-full rounded border px-2 py-1 text-sm"
                                        />

                                        <!-- Check All Checkbox -->
                                        <div class="mb-2 flex items-center gap-2">
                                            <input type="checkbox" v-model="checkAll" class="accent-blue-600" id="check-all" />
                                            <label for="check-all" class="text-sm font-medium">Check All</label>
                                        </div>

                                        <!-- Permissions List -->
                                        <div v-for="permission in filteredPermissions" :key="permission.id" class="flex items-center gap-2">
                                            <input
                                                type="checkbox"
                                                :value="permission.id"
                                                v-model="form.permissions"
                                                class="accent-blue-600"
                                                :id="'perm-' + permission.id"
                                            />
                                            <label :for="'perm-' + permission.id" class="text-sm">
                                                {{ permission.name }}
                                            </label>
                                        </div>
                                    </div>
                                </FormControl>
                                <FormError :message="form.errors.permissions" />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('roles.index')">
                            <Button class="w-64 flex-initial" type="button">Cancel</Button>
                        </Link>
                        <Button variant="success" type="submit" class="w-64 flex-initial" :disabled="form.processing">Update</Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>

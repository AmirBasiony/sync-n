<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import 'vue-multiselect/dist/vue-multiselect.min.css';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: route('roles.index') },
    { title: 'Create', href: route('roles.create') },
];

const props = defineProps<{
    permissions: any;
}>();

const form = useForm({
    name: '',
    permissions: [],
});

const search = ref('');
const checkAll = ref(false);

const filteredPermissions = computed(() => {
  return (props.permissions || []).filter((permission) =>
    (permission.name || '').toLowerCase().includes(search.value.toLowerCase())
  )
})

watch(checkAll, (value) => {
    if (value) {
        // Add all filtered permissions
        const allIds = filteredPermissions.value.map((p) => p.id);
        form.permissions = Array.from(new Set([...form.permissions, ...allIds]));
    } else {
        // Remove all filtered permissions
        form.permissions = form.permissions.filter(
            (id) => !filteredPermissions.value.map((p) => p.id).includes(id)
        );
    }
});

watch(
    () => form.permissions,
    (newVal) => {
        const filteredIds = filteredPermissions.value.map((p) => p.id);
        checkAll.value =
            filteredIds.length > 0 &&
            filteredIds.every((id) => newVal.includes(id));
    },
    { deep: true }
);


const submit = () => {
    form.post(route('roles.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Create Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <div>
                <h3 class="panel-title my-3 font-bold">Create New Role:</h3>
            </div>

            <div class="mt-3">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <FormField name="name">
                            <FormItem>
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input type="text" v-model="form.name" placeholder="Role Name" required />
                                </FormControl>
                                <FormError :message="form.errors.name" />
                            </FormItem>
                        </FormField>

                        <FormField name="permissions">
                            <FormItem>
                                <FormLabel>Permissions</FormLabel>
                                <FormControl>
                                    <div class="max-h-60 space-y-2 overflow-y-auto rounded-md border p-3">
                                        <!-- Search -->
                                        <input
                                            type="text"
                                            v-model="search"
                                            placeholder="Search..."
                                            class="mb-2 w-full rounded border px-2 py-1 text-sm"
                                        />

                                        <!-- Check All -->
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" v-model="checkAll" class="accent-blue-600" id="check-all" />
                                            <label for="check-all" class="text-sm font-medium">Check All</label>
                                        </div>

                                        <!-- List of Permissions -->
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

                    <!-- Actions -->
                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('roles.index')">
                            <Button class="w-64" type="button">Cancel</Button>
                        </Link>
                        <Button variant="success" type="submit" class="w-64" :disabled="form.processing">Create</Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>

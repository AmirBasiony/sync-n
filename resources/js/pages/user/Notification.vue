<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Form, FormControl, FormError, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import * as LucideIcons from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: route('users.index') },
    { title: 'Notification', href: '#' },
];

const props = defineProps<{
    users: any;
    notificationIcons: { _id: string; name: string }[];
}>();

const form = useForm({
    subject: '',
    message: '',
    sendToAll: false,
    user_id: [],
    icon_id: '',
});

const getLucideIcon = (name: string) => {
    const iconName = name as keyof typeof LucideIcons;
    return LucideIcons[iconName] || LucideIcons.HelpCircle;
};

const selectIcon = (id: string) => {
    form.icon_id = id;
};

const submit = () => {
    // Ensure correct user_id state
    if (form.sendToAll) {
        form.user_id = null;
    } else if (form.user_id.length === 0) {
        // Validate: must select at least one user
        form.setError('user_id', 'You must select at least one user');
        return;
    }

    form.post(route('users.notifications.send'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Send Notification" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4">
            <div>
                <h3 class="my-3 font-bold">Send Notification:</h3>
            </div>

            <div class="mt-3">
                <Form @submit="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Subject -->
                        <FormField name="subject">
                            <FormItem>
                                <FormLabel>Subject</FormLabel>
                                <FormControl>
                                    <Input type="text" v-model="form.subject" placeholder="Notification Subject" required />
                                </FormControl>
                                <FormError :message="form.errors.subject" />
                            </FormItem>
                        </FormField>

                        <!-- Message -->
                        <FormField name="message">
                            <FormItem>
                                <FormLabel>Message</FormLabel>
                                <FormControl>
                                    <Input type="text" v-model="form.message" placeholder="Notification Message" required />
                                </FormControl>
                                <FormError :message="form.errors.message" />
                            </FormItem>
                        </FormField>

                        <!-- Checkbox: Send to all -->
                        <FormField name="sendToAll">
                            <FormItem>
                                <FormLabel>To All Users</FormLabel>
                                <FormControl>
                                    <Checkbox v-model="form.sendToAll" />
                                </FormControl>
                            </FormItem>
                        </FormField>

                        <!-- Multi-select: Specific Users -->
                        <FormField name="user_id">
                            <FormItem>
                                <FormLabel>Users</FormLabel>
                                <FormControl>
                                    <Select multiple v-model="form.user_id" :disabled="form.sendToAll">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select Users" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="user in users" :key="user.id" :value="user.id">
                                                {{ user.name }} - {{ user.email }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </FormControl>
                                <FormError :message="form.errors.user_id" />
                            </FormItem>
                        </FormField>

                        <FormField name="icon_id">
                            <FormItem>
                                <FormLabel>Choose Icon</FormLabel>
                                <FormControl>
                                    <div class="flex flex-wrap gap-2 pb-2">
                                        <button
                                            v-for="icon in notificationIcons"
                                            :key="icon.id"
                                            type="button"
                                            @click="selectIcon(icon.id)"
                                            class="flex h-20 w-20 flex-col items-center justify-center rounded-md border text-xs transition-colors"
                                            :class="{
                                                'border-primary bg-primary/10': form.icon_id === icon.id,
                                                'hover:border-muted-foreground': form.icon_id !== icon.id,
                                            }"
                                        >
                                            <component :is="getLucideIcon(icon.name)" class="mb-1 h-5 w-5" />
                                            <span class="truncate px-1 text-[10px]">{{ icon.name }}</span>
                                        </button>
                                    </div>
                                </FormControl>
                                <FormError :message="form.errors.icon_id" />
                            </FormItem>
                        </FormField>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-center space-x-4 pt-6">
                        <Link :href="route('users.notifications.create')">
                            <Button class="w-64" type="button">Cancel</Button>
                        </Link>
                        <Button type="submit" variant="success" class="w-64" :disabled="form.processing"> Send </Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>

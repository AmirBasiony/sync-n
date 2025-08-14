<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    ticketTypes: Object
});

const { toast } = useToast();
const showDialog = ref(false);
const form = useForm({
    subject: '',
    type: '',
    message: '',
    attachments: []
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.attachments = Array.from(target.files); // make it a real array
    }
};

const submit = () => {
    form.post(route('settings.tickets.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            showDialog.value = false;
            toast({
                title: 'Success!',
                description: 'Ticket has been created'
            });
        },
        onError: () => {
            toast({
                title: 'Validation Failed',
                description: 'Please check your form fields.',
                variant: 'destructive'
            });
        }
    });
};
</script>

<template>
    <Dialog v-model:open="showDialog">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[650px]">
            <DialogHeader>
                <DialogTitle>Open New Ticket</DialogTitle>
                <DialogDescription> Make changes to your profile here. Click save when you're done.</DialogDescription>
            </DialogHeader>
            <form id="create-ticket-form" @submit.prevent="submit" encType="multipart/form-data">
                <div class="grid gap-4 space-y-8 py-4">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="subject"> Ticket Subject </Label>
                        <span class="col-span-3">
                            <Input id="subject" v-model="form.subject" />
                            <!-- input error -->
                            <span v-if="form.errors.subject" class="mt-1 text-xs text-red-500">
                                {{ form.errors.subject }}
                            </span>
                        </span>
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="username"> Your Question or Issue about? </Label>
                        <span class="col-span-3">
                            <Select v-model="form.type">
                                <SelectTrigger class="col-span-3">
                                    <SelectValue placeholder="Asking about?" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <template v-for="(key, value, index) in ticketTypes" :key="index">
                                            <SelectItem :value="value"> {{ key }}</SelectItem>
                                        </template>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="form.errors.type" class="mt-1 text-xs text-red-500">
                                {{ form.errors.type }}
                            </span>
                        </span>
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="message"> Ticket Content </Label>
                        <span class="col-span-3">
                            <Textarea v-model="form.message" id="message" placeholder="Type your message here..."
                                      class="col-span-3" />
                            <span v-if="form.errors.message" class="mt-1 text-xs text-red-500">
                                {{ form.errors.message }}
                            </span>
                        </span>
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="attachments"> Attachments </Label>
                        <span class="col-span-3">
                            <Input id="attachments" type="file" multiple @change="handleFileUpload" />
                            <span v-if="form.errors.attachments" class="mt-1 text-xs text-red-500">
                                {{ form.errors.attachments }}
                            </span>
                        </span>
                    </div>
                </div>
            </form>
            <DialogFooter>
                <Button type="submit" form="create-ticket-form"> Save changes</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<style scoped></style>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

const props = defineProps({
    ticket: Object,
});

const form = useForm({
    message: ''
});

/**
 * ===============
 * Methods
 * ===============
 */

const submit = () => {
    form.put(route('tickets.reply', props.ticket.data.id));
};

</script>

<template>
    <!-- reply form -->
    <form class="mt-4" @submit.prevent="submit" v-if="!ticket.data.closed">
        <div class="grid gap-2">
            <Label for="name">Send Reply:</Label>
            <Textarea v-model="form.message" maxlength="500" placeholder="Type your message here" />
            <InputError class="mt-2" :message="form.errors.message" />
        </div>
        <Button type="submit" class="mt-4 w-full cursor-pointer" :disabled="form.processing">Reply</Button>
    </form>
</template>

<style scoped>

</style>

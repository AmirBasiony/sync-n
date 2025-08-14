<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner'
import { MessageSquareOff } from 'lucide-vue-next';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
});
const loading = ref(false);

const confirmClose = () => {
    if (confirm('Are you sure you want to close this ticket?')) {
        closeTicket();
    }
};

const form = useForm({
    status: 3,
});

const closeTicket = () => {
    form.put(route('tickets.close', props.ticket.data.id), {
        onSuccess: () => {
            toast({
                title: 'Ticket closed',
                description: 'Ticket has been closed successfully.',
                variant: 'success'
            });
        },
        onError: () => {
            toast({
                title: 'Error',
                description: 'Something went wrong!',
                variant: 'destructive'
            });
        }
    });
};

</script>

<template>
    <form @click.prevent="confirmClose">
        <Button
            :disabled="form.processing"
            variant="destructive"
            class="mt-4 cursor-pointer transition-all duration-200 hover:bg-destructive/90 hover:dark:bg-destructive/90"
        >
            <!-- Add spinner when form processing -->
            <span v-if="form.processing">
                <svg class="mr-2 h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            <MessageSquareOff class="mr-2 h-4 w-4" />
            Close Ticket
        </Button>
    </form>
</template>

<style scoped></style>

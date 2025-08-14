<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner'
import { RefreshCcw } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
});


const confirmReopen = () => {
    if (confirm('Are you sure you want to re-open this ticket?')) {
        reopenTicket();
    }
};

const form = useForm({
    status: 4,
});

const reopenTicket = () => {
    form.put(route('settings.tickets.reopen', props.ticket.data.id), {
        onSuccess: () => {
            toast({
                title: 'Ticket closed',
                description: 'Ticket has been closed successfully.',
                variant: 'success'
            });
            // reset form
            form.reset();
        },
        onError: () => {
            toast({
                title: 'Error',
                description: 'Something went wrong!',
                variant: 'destructive'
            });
            // reset form
            form.reset();
        }
    });
};

</script>

<template>
    <form @click.prevent="confirmReopen">
        <Button
            :disabled="form.processing"
            variant="secondary"
            class="mt-4 cursor-pointer hover:bg-secondary/80 hover:dark:bg-secondary/80 transition-all duration-200">

            <RefreshCcw class="mr-2 h-4 w-4" />
            <!-- Add spinner when form processing -->
            <span v-if="form.processing">
                <svg class="mr-2 h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            Reopen Ticket
        </Button>
    </form>
</template>

<style scoped></style>

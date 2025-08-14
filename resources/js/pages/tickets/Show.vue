<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import CloseTicket from '@/components/tickets/CloseTicket.vue';
import TicketMainContent from '@/components/tickets/TicketMainContent.vue';
import TicketReplies from '@/components/tickets/TicketReplies.vue';
import TicketReplyForm from '@/components/tickets/TicketReplyForm.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AlertCircle } from 'lucide-vue-next';

const props = defineProps({
    ticket: Object,
});
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: `Ticket #${ticket.data.ticket_number}`, href: '/tickets' }]">
        <Head :title="`Help Center - Ticket #${ticket.data.ticket_number}`" />
        <div class="m-8">
            <section class="flex flex-row items-center justify-between">
                <HeadingSmall
                    :title="`Help Center - Ticket #${ticket.data.ticket_number}`"
                    :description="`Ticket #${ticket.data.ticket_number} discussion - SyncN user help center.`"
                />

                <CloseTicket v-if="!ticket.data.closed" :ticket="ticket" class="mb-4" />
            </section>

            <section v-if="ticket.data.closed">
                <Alert variant="destructive" class="my-4">
                    <AlertCircle class="h-4 w-4" />
                    <AlertTitle>Alert!</AlertTitle>
                    <AlertDescription> This ticket is closed. You cannot reply to this ticket.</AlertDescription>
                </Alert>
            </section>

            <TicketMainContent :ticket="ticket" />
            <TicketReplies :ticket="ticket" />
            <TicketReplyForm :ticket="ticket" />
        </div>
    </AppLayout>
</template>

<style scoped></style>

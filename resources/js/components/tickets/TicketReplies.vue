<script setup lang="ts">
const props = defineProps({
    ticket: Object,
});
</script>

<template>
    <section v-if="ticket.data.replies.length === 0" class="mt-8 w-full rounded-md border border-gray-100 p-6 dark:border-neutral-800">
        <h3 class="text-lg font-semibold dark:text-white">Ticket replies:</h3>
        <p class="mt-2 dark:text-white">No replies yet</p>
    </section>

    <section v-else class="mt-8 max-h-96 w-full overflow-y-auto rounded-md border border-gray-100 p-6 dark:border-neutral-800">
        <!-- User replies -->
        <div
            v-for="reply in ticket.data.replies"
            :key="reply.id"
            class="mb-4 flex flex-row"
            :class="{ 'justify-end': reply.type === 2, 'justify-start': reply.type !== 2 }"
        >
            <div
                class="flex max-w-xs flex-col rounded-md p-4 sm:max-w-md md:max-w-[500px]"
                :class="{
                    'bg-blue-500 text-white': reply.type === 2,
                    'bg-neutral-300 text-black dark:bg-neutral-800 dark:text-white': reply.type !== 2,
                }"
            >
                <span class="text-sm font-semibold">
                    {{ reply.type === 2 ? 'You' : 'Customer' }}
                </span>
                <p class="mt-2 whitespace-pre-wrap">
                    {{ reply.message }}
                </p>
                <span class="mt-2 text-xs opacity-75">{{ reply.created_at }}</span>
            </div>
        </div>
    </section>
</template>

<style scoped></style>

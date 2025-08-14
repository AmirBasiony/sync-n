<script setup lang="ts">
import FsLightbox from "fslightbox-vue";
import { Badge } from '@/components/ui/badge';
import { ref } from 'vue';

const props = defineProps({
    ticket: Object,
});

const toggler = ref(false);

// const attachements = () => {
//     return props.ticket.data.attachments.map((attachment) => {
//         return {
//             src: attachment.path,
//             width: 800,
//             height: 600
//         };
//     });
// };

const imageUrl = (path) => {
    return window.location.origin + '/' + path;
};

</script>

<template>
    <section class="w-full rounded-md border border-gray-100 p-6 dark:border-neutral-800">
        <!-- Here ticket title and initial message with attachments -->
        <span class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold dark:text-white">{{ ticket.data.subject }}</h2>
                    <span>
                        <span class="mr-4 text-sm font-semibold dark:text-white">Issue type:</span>
                        <Badge> {{ ticket.data.type }} </Badge></span
                    >
                </span>
        <p class="mt-2 dark:text-white">{{ ticket.data.initial_message }}</p>

<!--        <template v-if="ticket.data.attachments.length > 0">-->
<!--            <div v-for="attachment in ticket.data.attachments" :key="attachment.id" class="mt-4">-->
<!--                <img class="w-[250px] h-[150px] cursor-pointer" @click.prevent="toggler = !toggler" :src="imageUrl(attachment.path)" :alt="attachment.name" />-->
<!--            </div>-->
<!--            <FsLightbox-->
<!--                :toggler="toggler"-->
<!--                :sources="ticket.data.attachments.map((attachment) => imageUrl(attachment.path))"-->
<!--            />-->
<!--        </template>-->
    </section>
</template>

<style scoped>

</style>

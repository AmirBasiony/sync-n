<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import adminLogo from "@/public/adminLogo.svg"

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
  <img :src="adminLogo" alt="Admin Logo"/>
</template>

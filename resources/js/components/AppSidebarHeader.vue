<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { onMounted } from 'vue';
import useTheme from '@/composables/useTheme';
import type { BreadcrumbItemType } from '@/types';

import { useColorMode } from '@vueuse/core';
import ToggleTheme from '@/components/ToggleTheme.vue';
const { theme, setTheme } = useTheme(); // Use the composable
onMounted(() => {
    let mode = localStorage.getItem('theme') ?? 'light';
    useColorMode().value = mode;
});
withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="flex justify-between h-16 shrink-0 items-center border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
    <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div>
            <a href="#" @click.prevent="setTheme(theme === 'light' ? 'dark' : 'light')">
                <ToggleTheme />
            </a>
        </div>
    </header>
</template>

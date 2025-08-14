<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

// Accept props from parent component. This component expects an array of navigation items.
const props = defineProps<{ items: NavItem[] }>();

// Get the current page context from Inertia (used to determine active link)
const page = usePage();

// Keep track of which dropdown (by index) is currently open
const openIndex = ref<number | null>(null);

// Toggle dropdown open/closed when a parent menu item is clicked
function toggleDropdown(index: number) {
    openIndex.value = openIndex.value === index ? null : index;
}

// On component mount, check if any subitem matches the current page URL.
// If so, open the corresponding parent dropdown by default.
onMounted(() => {
    props.items.forEach((item, index) => {
        if (item.subitems?.some((subitem) => page.url.startsWith(getPath(subitem.href)))) {
            openIndex.value = index;
        }
    });
});

// Helper function to extract just the pathname from a full URL.
// Ensures comparison works even if item.href is an absolute URL.
function getPath(href: string): string {
    try {
        return new URL(href).pathname;
    } catch {
        return href; // If it's already a relative path, just return it.
    }
}
</script>

<template>
    <!-- Loop through all nav items -->
    <template v-for="(item, index) in props.items" :key="index">
        <SidebarGroup class="px-2 py-0">
            <!-- Show group label if available (like 'Settings' or 'Management') -->
            <SidebarGroupLabel v-if="item.label" class="px-3 py-2 text-xs tracking-wide text-muted-foreground uppercase pb-0">
                {{ item.label }}
            </SidebarGroupLabel>

            <SidebarMenu>
                <!-- Dropdown menu item (has subitems) -->
                <SidebarMenuItem v-if="item.subitems">
                    <!-- Parent button that toggles dropdown -->
                    <SidebarMenuButton @click="toggleDropdown(index)" class="mb-1 flex w-full items-center justify-between">
                        <div class="flex items-center">
                            <!-- Show icon and title -->
                            <component :is="item.icon" class="mr-2 h-4 w-4" />
                            {{ item.title }}
                        </div>

                        <!-- Arrow icon that rotates if this dropdown is open -->
                        <ChevronRight class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-90': openIndex === index }" />
                    </SidebarMenuButton>

                    <!-- subitems menu shown only when dropdown is open -->
                    <SidebarMenu v-show="openIndex === index" class="pl-0">
                        <!-- Loop through subitem items -->
                        <SidebarMenuItem v-for="(subitem, subitemIndex) in item.subitems" :key="subitemIndex">
                            <!-- Mark subitem as active if current page matches -->
                            <Link :href="subitem.href" class="flex items-center">
                                <SidebarMenuButton as-subitem :is-active="page.url.endsWith(getPath(subitem.href))" class="cursor-pointer">
                                <component :is="subitem.icon" class="mr-2 h-4 w-4" />
                                {{ subitem.title }}
                                </SidebarMenuButton>
                            </Link>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarMenuItem>

                <!-- Regular menu item (no subitems) -->
                <SidebarMenuItem v-else>
                    <!-- Mark as active if current page matches this item -->
                    <SidebarMenuButton as-subitem :is-active="page.url.endsWith(getPath(item.href))">
                        <Link :href="item.href" class="flex items-center">
                            <component :is="item.icon" class="mr-2 h-4 w-4" />
                            {{ item.title }}
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroup>
    </template>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button/index';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Link } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/themes/dark.css';
import { SquarePlus } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

const search = ref('');
const createdAt = ref('');
const isUsed = ref(null);

const props = withDefaults(
    defineProps<{
        createRoute?: string | boolean;
        onFilterChange: (filters: { search: string; created_at: string; is_used: null }) => void;
        showUsedFilter?: boolean;
        showCreatedAtFilter?: boolean;
        showSearch?: boolean;
        showCreateButton?: boolean;
    }>(),
    {
        showSearch: true,
        showCreatedAtFilter: true,
        showUsedFilter: true,
        showCreateButton: true,
    },
);

// ref to the input element
const createdAtInput = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (createdAtInput.value) {
        flatpickr(createdAtInput.value, {
            dateFormat: 'd-m-Y',
            onChange: (selectedDates, dateStr) => {
                createdAt.value = dateStr;
            },
            allowInput: true,
        });
    }
});

function clearAllFilters() {
    search.value = '';
    createdAt.value = '';
    isUsed.value = null;
}

const emitFilter = useDebounceFn(() => {
    props.onFilterChange({
        search: search.value,
        created_at: createdAt.value,
        is_used: isUsed.value,
    });
}, 300);

watch([search, createdAt, isUsed], emitFilter);
</script>

<template>
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between">
        <!-- Left: Filters -->
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search Input -->
            <div class="flex flex-col" v-if="props.showSearch">
                <Label for="search" class="pb-1">Search</Label>
                <Input id="search" v-model="search" placeholder="Search..." />
            </div>

            <!-- Created At Filter -->
            <div class="flex flex-col" v-if="props.showCreatedAtFilter">
                <Label for="created_at" class="pb-1">Created At</Label>
                <input
                    id="created_at"
                    ref="createdAtInput"
                    autocomplete="off"
                    v-model="createdAt"
                    placeholder="Select date"
                    class="h-9 w-[150px] cursor-pointer rounded border px-3 py-2 dark:bg-background dark:text-white"
                />
            </div>

            <!-- Used Filter -->
            <div class="flex flex-col" v-if="props.showUsedFilter">
                <Label for="used" class="pb-1">Used</Label>
                <Select v-model="isUsed">
                    <SelectTrigger id="used" class="w-[120px]">
                        <SelectValue placeholder="All" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="null">All</SelectItem>
                        <SelectItem value="true">Used</SelectItem>
                        <SelectItem value="false">Unused</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Clear Button -->
            <div class="flex h-9 flex-col md:mt-4">
                <Button variant="outline" @click="clearAllFilters">Reset All</Button>
            </div>
        </div>

        <!-- Right: Create Button -->
        <div class="mr-50 md:mt-4" v-if="props.createRoute">
            <Link :href="props.createRoute"
                ><Button><SquarePlus />Create</Button></Link
            >
        </div>
    </div>
</template>

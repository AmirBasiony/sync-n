<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'

import AppLayout from '@/layouts/AppLayout.vue'
import TableToolbar from '@/components/TableToolbar.vue'
import Pagination from '@/components/Pagination.vue'

import { Button } from '@/components/ui/button'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import {
  Table, TableBody, TableCell, TableCaption, TableHead, TableHeader, TableRow
} from '@/components/ui/table'
import {
  Select, SelectContent, SelectItem, SelectTrigger, SelectValue
} from '@/components/ui/select'
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip'

import { Eye, Trash2, Pencil } from 'lucide-vue-next'
import { useConfirmDelete } from '@/composables/useConfirmDelete'
import type { BreadcrumbItem } from '@/types'

// Type Definition

const props = defineProps<{
  widgets: {
    data: any[],
    links: any[],
    total: number,
    per_page: number
  },
  types: Array<{ _id: string; name: string }>,
  filters: { search?: string; type_id?: string; created_at?: string }
 }>() 

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Widgets', href: route('subtypes.index') },
]

const search = ref(props.filters?.search ?? '')
const selectedType = ref(props.filters?.type_id ?? '')
const createdAt = ref(props.filters?.created_at ?? '')

function onFilterChange(toolbarFilters: any) {
  const clean = Object.fromEntries(
    Object.entries(toolbarFilters || {}).filter(([_, v]) => v !== '' && v !== null)
  ) as any
  if (selectedType.value) clean.type_id = selectedType.value
  if (createdAt.value) clean.created_at = createdAt.value
  router.get(route('subtypes.index'), clean, {
    preserveState: true,
    replace: true,
    preserveScroll: true,
  })
}

function applyFilters() {
  onFilterChange({ search: search.value?.trim() || '', created_at: createdAt.value || '' })
}

function resetAll() {
  search.value = ''
  selectedType.value = ''
  createdAt.value = ''
  applyFilters()
}

const showAttr = ref(false)
const selectedWidget = ref<any | null>(null)

function openAttributes(w: any) {
  selectedWidget.value = w
  showAttr.value = true
}

const widgetName = computed(() => {
  const w = selectedWidget.value
  if (!w) return ''
  return typeof w.name === 'object' ? (w.name.en || w.name.ar || 'Widget') : (w.name || 'Widget')
})

const widgetId = computed(() => {
  const w = selectedWidget.value
  if (!w) return ''
  return String(w.id || w._id || '')
})

const { confirmDelete } = useConfirmDelete()
function deleteWidget(id?: string) {
  if (!id) return
  confirmDelete('subtypes.destroy', id)
}

function goToEdit(w: any) {
  const id = String(w.id || w._id)
  router.visit(route('subtypes.edit', id))
}
</script>

<template>
  <Head title="Widgets" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <TableToolbar :create-route="route('subtypes.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />

      <div class="mt-4">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>ID</TableHead>
              <TableHead>Name</TableHead>
              <TableHead>Type</TableHead>
              <TableHead class="w-[180px]">Actions</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="w in widgets.data" :key="(w.id || w._id) as any">
              <TableCell class="font-mono text-xs">
                {{ String(w.id || w._id) }}
              </TableCell>

              <TableCell class="font-medium">
                {{ typeof w.name === 'object' ? (w.name.en || w.name.ar || 'Widget') : (w.name || 'Widget') }}
              </TableCell>

              <TableCell>
                <Badge variant="secondary">{{ w.type?.name || '—' }}</Badge>
              </TableCell>

              <TableCell class="flex gap-2">
                <TooltipProvider>
                  <Tooltip>
                    <TooltipTrigger as-child>
                      <Button size="icon" variant="outline" @click="openAttributes(w)">
                        <Eye class="w-4 h-4" />
                      </Button>
                    </TooltipTrigger>
                    <TooltipContent>Attributes</TooltipContent>
                  </Tooltip>

                  <Tooltip>
                    <TooltipTrigger as-child>
                      <Button size="sm" variant="outline" :as="'a'" :href="route('subtypes.edit', String(w.id || w._id))"
>
                        <Pencil class="w-4 h-4 mr-1" /> 
                      </Button>
                    </TooltipTrigger>
                  </Tooltip>

                  <Tooltip>
                    <TooltipTrigger as-child>
                      <Button size="icon" variant="ghost" @click="deleteWidget(String(w.id || w._id))">
                        <Trash2 class="w-4 h-4" />
                      </Button>
                    </TooltipTrigger>
                    <TooltipContent>Delete</TooltipContent>
                  </Tooltip>
                </TooltipProvider>
              </TableCell>
            </TableRow>
          </TableBody>

          <TableCaption>
            <div v-if="widgets.total > widgets.per_page" class="row align-items-center mt-4">
              <div class="ml-auto">
                <Pagination :pagination="widgets.links" />
              </div>
            </div>
          </TableCaption>
        </Table>
      </div>
    </div>

    <Dialog v-model:open="showAttr">
      <DialogContent class="max-w-xl">
        <DialogHeader>
          <DialogTitle>Widget Attributes</DialogTitle>
          <DialogDescription>
            <span class="font-medium">{{ widgetName }}</span>
            <span class="block text-xs text-muted-foreground">ID: {{ widgetId }}</span>
          </DialogDescription>
        </DialogHeader>

        <div v-if="selectedWidget?.attributes && Object.keys(selectedWidget.attributes).length" class="space-y-3">
          <Separator />
          <ScrollArea class="h-[320px] pr-2">
            <div class="space-y-2">
              <div v-for="(val, key) in selectedWidget!.attributes" :key="key" class="text-sm">
                <span class="font-semibold">{{ key }}</span>:
                <span class="text-muted-foreground">
                  {{ typeof val === 'object' ? JSON.stringify(val) : String(val) }}
                </span>
              </div>
            </div>
          </ScrollArea>
        </div>

        <div v-else class="text-sm text-muted-foreground py-2">
          No attributes found.
        </div>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'

import AppLayout from '@/layouts/AppLayout.vue'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Card, CardContent } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import type { BreadcrumbItem } from '@/subtypes'

import { Trash2 } from 'lucide-vue-next'

const props = defineProps<{
  widget: {
    _id: string
    name_en: string
    type_id: string
    attributes: Record<string, string> | { key: string; value: string }[]
  },
  types: { _id: string; name: string | { en?: string; ar?: string } }[]
}>()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Widgets / Edit' },
]

// 🛠️ تأكد إن الـ attributes بتتحول array لو جاية object
const normalizeAttributes = (attrs: any): { key: string; value: string }[] => {
  if (Array.isArray(attrs)) return attrs
  if (typeof attrs === 'object' && attrs !== null) {
    return Object.entries(attrs).map(([key, value]) => ({
      key: String(key),
      value: String(value),
    }))
  }
  return []
}

const form = useForm({
  name_en: props.widget.name_en || '',
  type_id: props.widget.type_id || '',
  attributes: normalizeAttributes(props.widget.attributes)
})

function addRow() {
  form.attributes.push({ key: '', value: '' })
}

function deleteRow(index: number) {
  form.attributes.splice(index, 1)
}

function submit() {
  form.attributes = form.attributes.filter(attr => attr.key && attr.value)
  form.put(route('subtypes.update', props.widget._id))
}
</script>

<template>
  <Head title="Edit Widget" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="w-full px-8 py-6 space-y-6">
      <form @submit.prevent="submit" class="space-y-6">
        <div class="space-y-2">
          <Label for="name_en">Name (EN)</Label>
          <Input id="name_en" v-model="form.name_en" placeholder="Enter English name" />
        </div>

        <div class="space-y-2">
          <Label for="type_id">Type</Label>
          <Select v-model="form.type_id">
            <SelectTrigger id="type_id" class="w-full">
              <SelectValue placeholder="Select Type" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="type in types"
                :key="type._id"
                :value="type._id"
              >
                {{ typeof type.name === 'object' ? (type.name.en || type.name.ar) : type.name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <Separator />

        <Card class="w-full">
          <CardContent class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold">Attributes</h2>
              <Button type="button" size="sm" @click="addRow">+ Add Row</Button>
            </div>

            <div
              v-for="(attr, index) in form.attributes"
              :key="index"
              class="grid grid-cols-12 gap-3 items-center mb-3"
            >
              <div class="col-span-5">
                <Input v-model="attr.key" placeholder="Key" />
              </div>
              <div class="col-span-5">
                <Input v-model="attr.value" placeholder="Value" />
              </div>
               <div class="col-span-2 text-right">
                <Button type="button" variant="ghost" size="icon" @click="deleteRow(index)">
                  <Trash2 class="w-4 h-4" />
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>

        <div class="text-right">
          <Button type="submit" :disabled="form.processing">Update Widget</Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

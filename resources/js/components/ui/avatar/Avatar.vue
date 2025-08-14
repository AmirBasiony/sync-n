<script setup lang="ts">
import { AvatarRoot, AvatarImage, AvatarFallback } from 'reka-ui'
import { cn } from '@/lib/utils'
import { ref, computed, watch } from 'vue'
import type { HTMLAttributes } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps<{
  src?: string | null
  alt?: string
  class?: HTMLAttributes['class']
}>()

const imageErrored = ref(false)
const page = usePage();
const auth = computed(() => page.props.auth);

watch(() => props.src, () => {
  imageErrored.value = false
})

const fallbackLetter = computed(() => {
  return auth.value.user.name?.trim()?.charAt(0)?.toUpperCase() ?? 'A'
})
</script>

<template>
  <AvatarRoot
    data-slot="avatar"
    :class="cn('relative flex size-8 shrink-0 overflow-hidden rounded-full', props.class)"
  >
    <AvatarImage
      v-if="props.src && !imageErrored"
      :src="props.src"
      :alt="props.alt ?? 'avatar'"
      @error="imageErrored = true"
    />
    <AvatarFallback class="bg-muted flex size-full items-center justify-center rounded-lg text-black dark:text-white">
      {{ fallbackLetter }}
    </AvatarFallback>
  </AvatarRoot>
</template>

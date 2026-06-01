<script setup>
import { FolderOpenIcon } from '@heroicons/vue/24/outline'

defineProps({
    title:       { type: String, default: 'Sin resultados' },
    description: { type: String, default: '' },
    icon:        { type: [Object, Function], default: null },
})
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background: var(--bg-surface-2)">
            <component
                :is="icon ?? FolderOpenIcon"
                class="w-6 h-6"
                style="color: var(--text-muted)"
            />
        </div>
        <h3 class="text-sm font-semibold mb-1" style="color: var(--text-primary)">{{ title }}</h3>
        <p v-if="description" class="text-sm max-w-xs" style="color: var(--text-muted)">{{ description }}</p>
        <slot />
    </div>
</template>

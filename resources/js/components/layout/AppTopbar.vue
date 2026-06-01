<script setup>
import { Bars3Icon, MoonIcon, SunIcon } from '@heroicons/vue/24/outline'
import { useUiStore } from '../../stores/ui'

defineProps({
    title: { type: String, default: '' },
})

const ui = useUiStore()
</script>

<template>
    <header
        class="fixed top-0 right-0 z-20 flex items-center gap-3 px-5 transition-all duration-250"
        style="left: 0; height: 60px; background: var(--bg-surface); border-bottom: 1px solid var(--border-subtle)"
    >
        <!-- Hamburger (mobile only) -->
        <button
            type="button"
            class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
            style="color: var(--text-muted)"
            aria-label="Abrir menú"
            @click="ui.toggleSidebar()"
            @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
            @mouseleave="$event.currentTarget.style.background = ''"
        >
            <Bars3Icon class="w-5 h-5" />
        </button>

        <!-- Desktop sidebar spacer -->
        <div class="hidden lg:block shrink-0" style="width: 240px" />
        <div class="hidden lg:block w-px h-5 shrink-0" style="background: var(--border-subtle)" />

        <div class="flex-1" />

        <!-- Theme toggle -->
        <button
            type="button"
            class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
            :title="ui.theme === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
            style="color: var(--text-muted)"
            @click="ui.toggleTheme()"
            @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'; $event.currentTarget.style.color = 'var(--text-primary)'"
            @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
        >
            <MoonIcon v-if="ui.theme === 'light'" class="w-4.5 h-4.5" />
            <SunIcon  v-else                       class="w-4.5 h-4.5" />
        </button>
    </header>
</template>

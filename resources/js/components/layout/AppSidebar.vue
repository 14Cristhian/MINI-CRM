<script setup>
import { computed } from 'vue'
import {
    Squares2X2Icon,
    UsersIcon,
    ArrowRightStartOnRectangleIcon,
} from '@heroicons/vue/24/outline'
import { useUiStore } from '../../stores/ui'

defineProps({
    user: { type: Object, default: null },
})

const ui = useUiStore()
const currentPath = window.location.pathname

const NAV = [
    { label: 'Dashboard', href: '/dashboard', match: ['/dashboard', '/'],  icon: Squares2X2Icon },
    { label: 'Clientes',  href: '/clients',   match: ['/clients'],         icon: UsersIcon },
]

function isActive(item) {
    return item.match.some((m) =>
        m === '/' ? currentPath === m : currentPath.startsWith(m)
    )
}
</script>

<template>
    <!-- Mobile overlay -->
    <Transition name="overlay">
        <div
            v-if="ui.sidebarOpen"
            class="fixed inset-0 z-30 lg:hidden"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(2px)"
            @click="ui.toggleSidebar()"
        />
    </Transition>

    <aside
        :class="[
            'fixed inset-y-0 left-0 z-40 flex flex-col w-60 border-r transition-transform duration-250 ease-in-out',
            ui.sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        ]"
        style="background: var(--bg-surface); border-color: var(--border-subtle)"
    >
        <!-- Logo -->
        <div
            class="flex items-center gap-3 px-5 shrink-0"
            style="height: 60px; border-bottom: 1px solid var(--border-subtle)"
        >
            <div
                class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                style="background: linear-gradient(135deg, #6366f1, #4f46e5)"
            >
                <UsersIcon class="w-4 h-4 text-white" />
            </div>
            <div>
                <p class="text-sm font-bold leading-none" style="color: var(--text-primary)">Mini CRM</p>
                <p class="text-[11px] mt-0.5" style="color: var(--text-muted)">Gestión de clientes</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            <p class="px-2 mb-3 text-[10px] font-semibold uppercase tracking-[0.1em]" style="color: var(--text-muted)">
                General
            </p>

            <a
                v-for="item in NAV"
                :key="item.href"
                :href="item.href"
                class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
                :style="isActive(item)
                    ? 'background: var(--color-brand-50); color: var(--color-brand-700)'
                    : 'color: var(--text-secondary)'"
                @mouseover="!isActive(item) && ($event.currentTarget.style.background = 'var(--bg-surface-2)', $event.currentTarget.style.color = 'var(--text-primary)')"
                @mouseleave="!isActive(item) && ($event.currentTarget.style.background = '', $event.currentTarget.style.color = 'var(--text-secondary)')"
            >
                <span
                    class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                    :style="isActive(item)
                        ? 'background: var(--color-brand-100); color: var(--color-brand-600)'
                        : 'background: var(--bg-surface-2); color: var(--text-muted)'"
                >
                    <component :is="item.icon" class="w-4 h-4" />
                </span>

                {{ item.label }}

                <span
                    v-if="isActive(item)"
                    class="ml-auto w-1.5 h-1.5 rounded-full shrink-0"
                    style="background: var(--color-brand-500)"
                />
            </a>
        </nav>

        <!-- User section -->
        <div class="shrink-0 px-3 py-4" style="border-top: 1px solid var(--border-subtle)">
            <div
                v-if="user"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl"
                style="background: var(--bg-surface-2)"
            >
                <div
                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shrink-0 text-white"
                    style="background: linear-gradient(135deg, var(--color-brand-400), var(--color-brand-600))"
                >
                    {{ user.name?.charAt(0)?.toUpperCase() }}
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate leading-none mb-0.5" style="color: var(--text-primary)">
                        {{ user.name }}
                    </p>
                    <p class="text-[11px] truncate" style="color: var(--text-muted)">Administrador</p>
                </div>

                <form method="POST" :action="user.logoutUrl">
                    <input type="hidden" name="_token" :value="user.csrf">
                    <button
                        type="submit"
                        title="Cerrar sesión"
                        class="w-7 h-7 rounded-lg flex items-center justify-center transition-all"
                        style="color: var(--text-muted)"
                        @mouseover="$event.currentTarget.style.background = 'var(--color-error-bg)'; $event.currentTarget.style.color = 'var(--color-error)'"
                        @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
                    >
                        <ArrowRightStartOnRectangleIcon class="w-4 h-4" />
                    </button>
                </form>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.overlay-enter-active { transition: opacity 200ms ease; }
.overlay-leave-active { transition: opacity 180ms ease; }
.overlay-enter-from, .overlay-leave-to { opacity: 0; }
</style>

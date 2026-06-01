<script setup>
import { onMounted, onUnmounted } from 'vue'
import { XMarkIcon } from '@heroicons/vue/20/solid'
import BaseButton from './BaseButton.vue'

const props = defineProps({
    show:     { type: Boolean,  required: true },
    title:    { type: String,   default: '' },
    size:     { type: String,   default: 'md' },
    closable: { type: Boolean,  default: true },
})

const emit = defineEmits(['close'])

const SIZES = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-2xl',
}

function onKey(e) {
    if (e.key === 'Escape' && props.closable) emit('close')
}

onMounted(() => document.addEventListener('keydown', onKey))
onUnmounted(() => document.removeEventListener('keydown', onKey))
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
                role="dialog"
                aria-modal="true"
            >
                <div
                    class="absolute inset-0"
                    style="background: var(--bg-overlay)"
                    @click="closable && emit('close')"
                />

                <div
                    :class="[
                        'relative w-full rounded-2xl flex flex-col',
                        'border',
                        SIZES[size] ?? SIZES.md,
                    ]"
                    style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-xl); max-height: calc(100vh - 2rem)"
                >
                    <div
                        v-if="title || closable"
                        class="flex items-center justify-between px-6 py-4 border-b"
                        style="border-color: var(--border-subtle)"
                    >
                        <h2 v-if="title" class="text-base font-semibold" style="color: var(--text-primary)">
                            {{ title }}
                        </h2>
                        <button
                            v-if="closable"
                            type="button"
                            class="ml-auto p-1 rounded-md transition-colors"
                            style="color: var(--text-muted)"
                            aria-label="Cerrar"
                            @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'; $event.currentTarget.style.color = 'var(--text-primary)'"
                            @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
                            @click="emit('close')"
                        >
                            <XMarkIcon class="w-4 h-4" />
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-5">
                        <slot />
                    </div>

                    <div
                        v-if="$slots.footer"
                        class="px-6 py-4 border-t flex justify-end gap-2"
                        style="border-color: var(--border-subtle)"
                    >
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active { transition: all 220ms cubic-bezier(.22,.68,0,1.2); }
.modal-leave-active { transition: all 180ms ease-in; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(.96) translateY(4px); }
</style>

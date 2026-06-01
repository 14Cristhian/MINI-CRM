<script setup>
defineProps({
    show:    { type: Boolean, required: true },
    title:   { type: String, default: 'Confirmar acción' },
    message: { type: String, required: true },
    loading: { type: Boolean, default: false },
})

const emit = defineEmits(['confirm', 'cancel'])
</script>

<template>
    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                @click.self="emit('cancel')"
            >
                <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ title }}</h3>
                    <p class="text-sm text-gray-600 mb-6">{{ message }}</p>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            :disabled="loading"
                            class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50"
                            @click="emit('cancel')"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            :disabled="loading"
                            class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50"
                            @click="emit('confirm')"
                        >
                            {{ loading ? 'Eliminando...' : 'Eliminar' }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>

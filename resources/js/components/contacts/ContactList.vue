<script setup>
import { ref } from 'vue'
import { StarIcon, PencilSquareIcon, TrashIcon, PlusIcon, PhoneIcon } from '@heroicons/vue/20/solid'
import { useContactStore } from '../../stores/contacts'
import { useToastStore } from '../../stores/toast'
import { useConfirm } from '../../composables/useConfirm'
import ContactForm from './ContactForm.vue'
import AppModal from '../ui/AppModal.vue'
import BaseButton from '../ui/BaseButton.vue'
import EmptyState from '../ui/EmptyState.vue'

const props = defineProps({
    clientId: { type: Number, required: true },
})

const store          = useContactStore()
const toast          = useToastStore()
const confirm        = useConfirm()
const showForm       = ref(false)
const editingContact = ref(null)

function openCreate() {
    editingContact.value = null
    showForm.value = true
}

function openEdit(contact) {
    editingContact.value = contact
    showForm.value = true
}

function onSaved() {
    showForm.value = false
    editingContact.value = null
}

async function handleDelete(contact) {
    await confirm.open({
        title:   'Eliminar contacto',
        message: `¿Eliminar a "${contact.name}"? Esta acción no se puede deshacer.`,
        onConfirm: async () => {
            await store.deleteContact(props.clientId, contact.id)
            toast.success(`"${contact.name}" eliminado correctamente.`)
        },
    })
}
</script>

<template>
    <div
        class="rounded-2xl border overflow-hidden"
        style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color: var(--border-subtle)">
            <div>
                <h2 class="text-sm font-semibold" style="color: var(--text-primary)">Contactos</h2>
                <p class="text-xs mt-0.5" style="color: var(--text-muted)">{{ store.contacts.length }} registros</p>
            </div>
            <BaseButton size="sm" @click="openCreate">
                <PlusIcon class="w-3.5 h-3.5" />
                Agregar
            </BaseButton>
        </div>

        <!-- Inline form -->
        <div v-if="showForm" class="p-4 border-b" style="border-color: var(--border-subtle); background: var(--bg-surface-2)">
            <ContactForm
                :client-id="clientId"
                :contact="editingContact"
                @saved="onSaved"
                @cancel="showForm = false; editingContact = null"
            />
        </div>

        <!-- Loading skeleton -->
        <div v-if="store.loading && !store.contacts.length" class="divide-y" style="border-color: var(--border-subtle)">
            <div
                v-for="i in 3"
                :key="i"
                class="flex items-center gap-3 px-5 py-3.5"
                :style="{ opacity: 1 - (i - 1) * 0.2 }"
            >
                <div class="skeleton w-9 h-9 rounded-full shrink-0" />
                <div class="flex flex-col gap-1.5 flex-1">
                    <div class="skeleton h-3 rounded" :style="{ width: `${100 + i * 30}px` }" />
                    <div class="skeleton h-2.5 rounded" :style="{ width: `${80 + i * 20}px` }" />
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <EmptyState
            v-else-if="!store.contacts.length && !showForm"
            title="Sin contactos"
            description="Agrega el primer contacto para este cliente."
        />

        <!-- Contact list -->
        <ul v-else class="divide-y" style="border-color: var(--border-subtle)">
            <li
                v-for="contact in store.contacts"
                :key="contact.id"
                class="group flex items-center gap-3 px-5 py-3.5 transition-colors"
                @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                @mouseleave="$event.currentTarget.style.background = ''"
            >
                <!-- Avatar -->
                <div
                    class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                    :style="contact.is_primary
                        ? 'background: var(--color-brand-100); color: var(--color-brand-700)'
                        : 'background: var(--bg-surface-3); color: var(--text-muted)'"
                >
                    {{ contact.name?.charAt(0)?.toUpperCase() }}
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-sm font-medium" style="color: var(--text-primary)">{{ contact.name }}</span>
                        <span
                            v-if="contact.is_primary"
                            class="inline-flex items-center gap-1 text-[10px] font-semibold px-1.5 py-0.5 rounded-md"
                            style="background: var(--color-brand-50); color: var(--color-brand-700)"
                        >
                            <StarIcon class="w-2.5 h-2.5" />
                            Primario
                        </span>
                    </div>
                    <p class="text-xs truncate" style="color: var(--text-muted)">
                        {{ contact.email }}
                        <span v-if="contact.position"> · {{ contact.position }}</span>
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                    <button
                        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                        title="Editar"
                        style="color: var(--text-muted)"
                        @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-3)'; $event.currentTarget.style.color = 'var(--text-primary)'"
                        @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
                        @click="openEdit(contact)"
                    >
                        <PencilSquareIcon class="w-3.5 h-3.5" />
                    </button>
                    <button
                        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                        title="Eliminar"
                        style="color: var(--text-muted)"
                        @mouseover="$event.currentTarget.style.background = 'var(--color-error-bg)'; $event.currentTarget.style.color = 'var(--color-error)'"
                        @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
                        @click="handleDelete(contact)"
                    >
                        <TrashIcon class="w-3.5 h-3.5" />
                    </button>
                </div>
            </li>
        </ul>
    </div>

    <!-- Confirm modal -->
    <AppModal
        :show="confirm.show.value"
        :title="confirm.meta.value.title ?? 'Confirmar'"
        size="sm"
        @close="confirm.cancel()"
    >
        <p class="text-sm" style="color: var(--text-secondary)">{{ confirm.meta.value.message }}</p>
        <template #footer>
            <BaseButton variant="secondary" size="sm" @click="confirm.cancel()">Cancelar</BaseButton>
            <BaseButton variant="danger" size="sm" :loading="confirm.loading.value" @click="confirm.confirm()">
                Eliminar
            </BaseButton>
        </template>
    </AppModal>
</template>

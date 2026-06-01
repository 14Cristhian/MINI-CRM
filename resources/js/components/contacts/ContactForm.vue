<script setup>
import { watch } from 'vue'
import { useContactStore } from '../../stores/contacts'
import { useToastStore } from '../../stores/toast'
import { useForm } from '../../composables/useForm'
import BaseInput from '../ui/BaseInput.vue'
import BaseButton from '../ui/BaseButton.vue'

const props = defineProps({
    clientId: { type: Number, required: true },
    contact:  { type: Object, default: null },
})

const emit    = defineEmits(['saved', 'cancel'])
const store   = useContactStore()
const toast   = useToastStore()

const { form, errors, loading, fill, reset, submit } = useForm({
    name:       '',
    email:      '',
    phone:      '',
    position:   '',
    is_primary: false,
})

watch(() => props.contact, (c) => {
    if (c) fill(c)
    else reset()
}, { immediate: true })

async function handleSubmit() {
    try {
        if (props.contact) {
            await submit(() => store.updateContact(props.clientId, props.contact.id, form))
            toast.success('Contacto actualizado correctamente.')
        } else {
            await submit(() => store.createContact(props.clientId, form))
            toast.success('Contacto agregado correctamente.')
            reset()
        }
        emit('saved')
    } catch {
        toast.error('Revisa los campos e intenta de nuevo.')
    }
}
</script>

<template>
    <div
        class="rounded-xl border border-[var(--border-default)] bg-[var(--bg-surface-2)] p-4"
        style="box-shadow: var(--shadow-xs)"
    >
        <h3 class="text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider mb-4">
            {{ contact ? 'Editar contacto' : 'Nuevo contacto' }}
        </h3>

        <form class="space-y-3" @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <BaseInput
                    v-model="form.name"
                    label="Nombre"
                    placeholder="Nombre completo"
                    :error="errors.name?.[0]"
                    required
                />
                <BaseInput
                    v-model="form.email"
                    label="Email"
                    type="email"
                    placeholder="correo@empresa.com"
                    :error="errors.email?.[0]"
                    required
                />
                <BaseInput
                    v-model="form.phone"
                    label="Teléfono"
                    type="tel"
                    placeholder="+57 300 000 0000"
                    :error="errors.phone?.[0]"
                />
                <BaseInput
                    v-model="form.position"
                    label="Cargo"
                    placeholder="Ej. Gerente Comercial"
                    :error="errors.position?.[0]"
                />
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <input
                    v-model="form.is_primary"
                    type="checkbox"
                    class="h-4 w-4 rounded text-[var(--color-brand-600)] border-[var(--border-default)]"
                >
                <span class="text-sm text-[var(--text-secondary)]">
                    Establecer como contacto primario
                </span>
            </label>

            <div class="flex justify-end gap-2 pt-1">
                <BaseButton variant="ghost" size="sm" type="button" @click="emit('cancel')">
                    Cancelar
                </BaseButton>
                <BaseButton size="sm" type="submit" :loading="loading">
                    {{ contact ? 'Actualizar' : 'Agregar contacto' }}
                </BaseButton>
            </div>
        </form>
    </div>
</template>

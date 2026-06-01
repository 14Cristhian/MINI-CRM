<script setup>
import { onMounted } from 'vue'
import { useClientStore } from '../../stores/clients'
import { useToastStore } from '../../stores/toast'
import { useForm } from '../../composables/useForm'
import BaseInput from '../ui/BaseInput.vue'
import BaseSelect from '../ui/BaseSelect.vue'
import BaseButton from '../ui/BaseButton.vue'

const props = defineProps({
    clientId: { type: Number, default: null },
})

const store = useClientStore()
const toast = useToastStore()

const STATUS_OPTIONS = [
    { value: 'prospecto', label: 'Prospecto' },
    { value: 'activo',    label: 'Activo' },
    { value: 'inactivo',  label: 'Inactivo' },
]

const { form, errors, loading, fill, submit } = useForm({
    name:    '',
    email:   '',
    phone:   '',
    company: '',
    status:  'prospecto',
    notes:   '',
})

onMounted(async () => {
    if (props.clientId) {
        await store.fetchClient(props.clientId)
        if (store.client) fill(store.client)
    }
})

async function handleSubmit() {
    try {
        if (props.clientId) {
            await submit(() => store.updateClient(props.clientId, form))
            toast.success('Cliente actualizado correctamente.')
        } else {
            const res = await submit(() => store.createClient(form))
            toast.success('Cliente creado correctamente.')
            window.location.href = `/clients/${res.data.id}`
        }
    } catch {
        toast.error('Revisa los campos e intenta de nuevo.')
    }
}
</script>

<template>
    <div class="max-w-2xl">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm mb-6">
            <a href="/clients" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                Clientes
            </a>
            <span class="text-[var(--border-default)]">/</span>
            <span class="text-[var(--text-primary)] font-medium">
                {{ clientId ? 'Editar cliente' : 'Nuevo cliente' }}
            </span>
        </nav>

        <h1 class="text-xl font-bold text-[var(--text-primary)] mb-6">
            {{ clientId ? 'Editar cliente' : 'Nuevo cliente' }}
        </h1>

        <div
            class="bg-[var(--bg-surface)] rounded-2xl border border-[var(--border-subtle)] p-6"
            style="box-shadow: var(--shadow-sm)"
        >
            <form class="space-y-5" @submit.prevent="handleSubmit">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <BaseInput
                        v-model="form.name"
                        label="Nombre"
                        placeholder="Ej. Juan Pérez"
                        :error="errors.name?.[0]"
                        required
                    />
                    <BaseInput
                        v-model="form.company"
                        label="Empresa"
                        placeholder="Ej. Acme Corp"
                        :error="errors.company?.[0]"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                </div>

                <BaseSelect
                    v-model="form.status"
                    label="Estado"
                    :options="STATUS_OPTIONS"
                    :error="errors.status?.[0]"
                    required
                />

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-[var(--text-secondary)]">Notas</label>
                    <textarea
                        v-model="form.notes"
                        rows="3"
                        placeholder="Información adicional sobre el cliente..."
                        class="w-full text-sm rounded-lg px-3 py-2 border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] placeholder:text-[var(--text-placeholder)] focus:outline-none focus:ring-[3px] focus:ring-[rgba(99,102,241,.2)] focus:border-[var(--color-brand-500)] transition-all resize-none"
                    />
                </div>

                <div class="flex justify-end gap-2.5 pt-2 border-t border-[var(--border-subtle)]">
                    <a href="/clients">
                        <BaseButton variant="secondary">Cancelar</BaseButton>
                    </a>
                    <BaseButton type="submit" :loading="loading">
                        {{ loading ? 'Guardando…' : (clientId ? 'Actualizar' : 'Crear cliente') }}
                    </BaseButton>
                </div>
            </form>
        </div>
    </div>
</template>

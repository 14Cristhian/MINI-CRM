<script setup>
import { onMounted } from 'vue'
import {
    ChevronRightIcon,
    PencilSquareIcon,
    EnvelopeIcon,
    PhoneIcon,
    UserIcon,
    CalendarDaysIcon,
    DocumentTextIcon,
} from '@heroicons/vue/20/solid'
import { useClientStore }  from '../../stores/clients'
import { useContactStore } from '../../stores/contacts'
import StatusBadge  from '../ui/StatusBadge.vue'
import ContactList  from '../contacts/ContactList.vue'
import AlertMessage from '../ui/AlertMessage.vue'

const props = defineProps({
    clientId: { type: Number, required: true },
})

const clientStore  = useClientStore()
const contactStore = useContactStore()

onMounted(async () => {
    await clientStore.fetchClient(props.clientId)
    await contactStore.fetchContacts(props.clientId)
})
</script>

<template>
    <div>
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-1.5 text-sm mb-6" style="color: var(--text-muted)">
            <a
                href="/clients"
                class="transition-colors hover:underline"
                style="color: var(--text-muted)"
                @mouseover="$event.currentTarget.style.color = 'var(--text-primary)'"
                @mouseleave="$event.currentTarget.style.color = 'var(--text-muted)'"
            >
                Clientes
            </a>
            <ChevronRightIcon class="w-3.5 h-3.5 shrink-0" />
            <span v-if="clientStore.client" style="color: var(--text-primary)">{{ clientStore.client.name }}</span>
            <div v-else class="skeleton h-3.5 rounded w-24" />
        </nav>

        <AlertMessage v-if="clientStore.error" :message="clientStore.error" />

        <!-- Loading skeleton -->
        <div v-if="clientStore.loading">
            <div
                class="rounded-2xl border p-6 mb-6"
                style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
            >
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="skeleton w-14 h-14 rounded-2xl shrink-0" />
                        <div class="flex flex-col gap-2">
                            <div class="skeleton h-5 rounded w-40" />
                            <div class="skeleton h-3.5 rounded w-24" />
                        </div>
                    </div>
                    <div class="skeleton h-8 rounded-lg w-28" />
                </div>
                <div class="border-t pt-5 grid grid-cols-1 sm:grid-cols-3 gap-4" style="border-color: var(--border-subtle)">
                    <div v-for="i in 3" :key="i" class="flex flex-col gap-1.5">
                        <div class="skeleton h-2.5 rounded w-16" />
                        <div class="skeleton h-3.5 rounded w-32" />
                    </div>
                </div>
            </div>
            <div class="skeleton h-48 rounded-2xl" />
        </div>

        <template v-else-if="clientStore.client">
            <!-- Client header card -->
            <div
                class="rounded-2xl border p-6 mb-6"
                style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
            >
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-5">
                    <!-- Identity -->
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-bold shrink-0"
                            style="background: var(--color-brand-50); color: var(--color-brand-700)"
                        >
                            {{ clientStore.client.name?.charAt(0)?.toUpperCase() }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap mb-1">
                                <h1 class="text-xl font-bold" style="color: var(--text-primary)">
                                    {{ clientStore.client.name }}
                                </h1>
                                <StatusBadge :status="clientStore.client.status" />
                            </div>
                            <p class="text-sm font-medium" style="color: var(--text-secondary)">
                                {{ clientStore.client.company }}
                            </p>
                        </div>
                    </div>

                    <!-- Edit button -->
                    <a :href="`/clients/${clientId}/edit`">
                        <button
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium border transition-colors"
                            style="color: var(--text-secondary); border-color: var(--border-default)"
                            @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                            @mouseleave="$event.currentTarget.style.background = ''"
                        >
                            <PencilSquareIcon class="w-4 h-4" />
                            Editar cliente
                        </button>
                    </a>
                </div>

                <!-- Contact fields -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-5 border-t" style="border-color: var(--border-subtle)">
                    <div class="flex items-start gap-2.5">
                        <div
                            class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5"
                            style="background: var(--bg-surface-2)"
                        >
                            <EnvelopeIcon class="w-3.5 h-3.5" style="color: var(--text-muted)" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium mb-0.5" style="color: var(--text-muted)">Email</p>
                            <p class="text-sm truncate" style="color: var(--text-primary)">
                                {{ clientStore.client.email ?? '—' }}
                            </p>
                        </div>
                    </div>

                    <div v-if="clientStore.client.phone" class="flex items-start gap-2.5">
                        <div
                            class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5"
                            style="background: var(--bg-surface-2)"
                        >
                            <PhoneIcon class="w-3.5 h-3.5" style="color: var(--text-muted)" />
                        </div>
                        <div>
                            <p class="text-xs font-medium mb-0.5" style="color: var(--text-muted)">Teléfono</p>
                            <p class="text-sm" style="color: var(--text-primary)">{{ clientStore.client.phone }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5">
                        <div
                            class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5"
                            style="background: var(--bg-surface-2)"
                        >
                            <CalendarDaysIcon class="w-3.5 h-3.5" style="color: var(--text-muted)" />
                        </div>
                        <div>
                            <p class="text-xs font-medium mb-0.5" style="color: var(--text-muted)">Registrado</p>
                            <p class="text-sm" style="color: var(--text-primary)">
                                {{ new Date(clientStore.client.created_at).toLocaleDateString('es-CO', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div
                    v-if="clientStore.client.notes"
                    class="mt-5 pt-5 border-t"
                    style="border-color: var(--border-subtle)"
                >
                    <div class="flex items-center gap-2 mb-2">
                        <DocumentTextIcon class="w-3.5 h-3.5" style="color: var(--text-muted)" />
                        <span class="text-xs font-medium" style="color: var(--text-muted)">Notas</span>
                    </div>
                    <p class="text-sm whitespace-pre-line" style="color: var(--text-secondary)">
                        {{ clientStore.client.notes }}
                    </p>
                </div>
            </div>

            <!-- Contacts -->
            <ContactList :client-id="clientId" />
        </template>
    </div>
</template>

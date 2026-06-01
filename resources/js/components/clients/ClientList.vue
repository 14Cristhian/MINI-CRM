<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import {
    MagnifyingGlassIcon,
    XMarkIcon,
    PlusIcon,
    ArrowDownTrayIcon,
    TableCellsIcon,
    DocumentTextIcon,
    ChevronDownIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ChevronUpDownIcon,
    ChevronUpIcon,
    ArrowPathIcon,
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
} from '@heroicons/vue/20/solid'
import { useClientsList, useClientMutations } from '../../composables/useClientsQuery'
import { useToastStore } from '../../stores/toast'
import { useConfirm }    from '../../composables/useConfirm'
import StatusBadge    from '../ui/StatusBadge.vue'
import SkeletonLoader from '../ui/SkeletonLoader.vue'
import EmptyState     from '../ui/EmptyState.vue'
import BaseInput      from '../ui/BaseInput.vue'
import BaseSelect     from '../ui/BaseSelect.vue'
import BaseButton     from '../ui/BaseButton.vue'
import AppModal       from '../ui/AppModal.vue'

// ── State ───────────────────────────────────────────────────
const {
    searchInput, statusFilter, debouncedSearch,
    clients, meta, isLoading, isFetching, sortBy, sortDir,
    setSearch, setStatus, setSort, clearFilters, goToPage,
} = useClientsList()

const { remove } = useClientMutations()
const toast      = useToastStore()
const confirm    = useConfirm()

const STATUS_OPTIONS = [
    { value: 'activo',    label: 'Activo' },
    { value: 'inactivo',  label: 'Inactivo' },
    { value: 'prospecto', label: 'Prospecto' },
]

// ── Computed ────────────────────────────────────────────────
const hasFilters  = computed(() => searchInput.value || statusFilter.value)
const pages       = computed(() => Array.from({ length: meta.value.last_page }, (_, i) => i + 1))
const currentPage = computed(() => meta.value.current_page ?? 1)

// ── Sort helpers ────────────────────────────────────────────
const COLUMNS = [
    { key: 'name',           label: 'Cliente',   sortable: true },
    { key: 'email',          label: 'Email',     sortable: false },
    { key: 'status',         label: 'Estado',    sortable: true },
    { key: 'contacts_count', label: 'Contactos', sortable: true },
]

function sortIcon(col) {
    if (sortBy.value !== col) return ChevronUpDownIcon
    return sortDir.value === 'asc' ? ChevronUpIcon : ChevronDownIcon
}

// ── Export dropdown ─────────────────────────────────────────
const exportOpen    = ref(false)
const exportMenuRef = ref(null)

function buildExportUrl(format) {
    const params = new URLSearchParams()
    if (debouncedSearch.value) params.set('search', debouncedSearch.value)
    if (statusFilter.value)    params.set('status', statusFilter.value)
    const qs = params.toString()
    return `/clients/export/${format}${qs ? '?' + qs : ''}`
}

function exportAs(format) {
    window.location.href = buildExportUrl(format)
    exportOpen.value = false
}

function onClickOutside(e) {
    if (exportMenuRef.value && !exportMenuRef.value.contains(e.target)) {
        exportOpen.value = false
    }
}

onMounted(()  => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))

// ── Delete ──────────────────────────────────────────────────
async function handleDelete(client) {
    await confirm.open({
        title:   'Eliminar cliente',
        message: `¿Eliminar a "${client.name}"? También se eliminarán todos sus contactos.`,
        onConfirm: async () => {
            await remove.mutateAsync(client.id)
            toast.success(`"${client.name}" eliminado correctamente.`)
        },
    })
}
</script>

<template>
    <div>
        <!-- ── Header ───────────────────────────────────── -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold" style="color: var(--text-primary)">Clientes</h1>
                <p class="text-xs mt-0.5" style="color: var(--text-muted)">
                    <span v-if="isFetching && !isLoading" class="inline-flex items-center gap-1">
                        <ArrowPathIcon class="w-3 h-3 animate-spin" />
                        Actualizando…
                    </span>
                    <span v-else>{{ meta.total ?? 0 }} clientes en total</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <!-- Export -->
                <div ref="exportMenuRef" class="relative">
                    <BaseButton variant="secondary" @click.stop="exportOpen = !exportOpen">
                        <ArrowDownTrayIcon class="w-4 h-4" />
                        Exportar
                        <ChevronDownIcon
                            class="w-3 h-3 transition-transform duration-150"
                            :class="exportOpen ? 'rotate-180' : ''"
                        />
                    </BaseButton>

                    <Transition name="dropdown">
                        <div
                            v-if="exportOpen"
                            class="absolute right-0 mt-1.5 w-44 rounded-xl border py-1 z-20 overflow-hidden"
                            style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-md)"
                        >
                            <button
                                class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm transition-colors"
                                style="color: var(--text-secondary)"
                                @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                                @mouseleave="$event.currentTarget.style.background = ''"
                                @click="exportAs('excel')"
                            >
                                <TableCellsIcon class="w-4 h-4 shrink-0" style="color: #16a34a" />
                                Excel (.xlsx)
                            </button>
                            <button
                                class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm transition-colors"
                                style="color: var(--text-secondary)"
                                @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                                @mouseleave="$event.currentTarget.style.background = ''"
                                @click="exportAs('pdf')"
                            >
                                <DocumentTextIcon class="w-4 h-4 shrink-0" style="color: #dc2626" />
                                PDF
                            </button>
                        </div>
                    </Transition>
                </div>

                <!-- New client -->
                <a href="/clients/create">
                    <BaseButton>
                        <PlusIcon class="w-4 h-4" />
                        Nuevo cliente
                    </BaseButton>
                </a>
            </div>
        </div>

        <!-- ── Filters ──────────────────────────────────── -->
        <div class="flex flex-col sm:flex-row gap-2.5 mb-5">
            <div class="flex-1 relative">
                <BaseInput
                    :model-value="searchInput"
                    placeholder="Buscar por nombre, empresa o email…"
                    @update:model-value="setSearch"
                >
                    <template #icon>
                        <MagnifyingGlassIcon class="w-4 h-4" />
                    </template>
                </BaseInput>
                <span v-if="isFetching" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <ArrowPathIcon class="w-3.5 h-3.5 animate-spin" style="color: var(--text-muted)" />
                </span>
            </div>

            <BaseSelect
                :model-value="statusFilter"
                :options="STATUS_OPTIONS"
                placeholder="Todos los estados"
                class="sm:w-48"
                @update:model-value="setStatus"
            />

            <Transition name="fade-fast">
                <BaseButton v-if="hasFilters" variant="ghost" @click="clearFilters">
                    <XMarkIcon class="w-3.5 h-3.5" />
                    Limpiar
                </BaseButton>
            </Transition>
        </div>

        <!-- ── Table ────────────────────────────────────── -->
        <div
            class="rounded-2xl border overflow-hidden"
            style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
        >
            <!-- Initial skeleton -->
            <SkeletonLoader v-if="isLoading" :rows="8" />

            <template v-else-if="clients.length">
                <!-- Refetch progress bar -->
                <div v-if="isFetching" class="h-0.5 w-full overflow-hidden" style="background: var(--bg-surface-3)">
                    <div class="h-full animate-pulse w-3/5" style="background: var(--color-brand-500)" />
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-subtle); background: var(--bg-surface-2)">
                                <!-- Sortable columns -->
                                <th
                                    v-for="col in COLUMNS"
                                    :key="col.key"
                                    class="px-5 py-3 text-left"
                                    :class="[
                                        col.key === 'email'          ? 'hidden md:table-cell' : '',
                                        col.key === 'contacts_count' ? 'hidden sm:table-cell text-center' : '',
                                    ]"
                                >
                                    <button
                                        v-if="col.sortable"
                                        class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider transition-colors"
                                        :style="sortBy === col.key
                                            ? 'color: var(--color-brand-600)'
                                            : 'color: var(--text-muted)'"
                                        @click="setSort(col.key)"
                                    >
                                        {{ col.label }}
                                        <component
                                            :is="sortIcon(col.key)"
                                            class="w-3.5 h-3.5 shrink-0"
                                        />
                                    </button>
                                    <span
                                        v-else
                                        class="text-xs font-semibold uppercase tracking-wider"
                                        style="color: var(--text-muted)"
                                    >
                                        {{ col.label }}
                                    </span>
                                </th>
                                <!-- Actions column -->
                                <th class="px-4 py-3 w-32 text-right">
                                    <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted)">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="c in clients"
                                :key="c.id"
                                class="transition-colors"
                                style="border-bottom: 1px solid var(--border-subtle)"
                                @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                                @mouseleave="$event.currentTarget.style.background = ''"
                            >
                                <!-- Client -->
                                <td class="px-5 py-3.5">
                                    <a :href="`/clients/${c.id}`" class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                            style="background: var(--color-brand-50); color: var(--color-brand-700)"
                                        >
                                            {{ c.name?.charAt(0) }}
                                        </div>
                                        <div>
                                            <p class="font-medium leading-none mb-1" style="color: var(--text-primary)">{{ c.name }}</p>
                                            <p class="text-xs" style="color: var(--text-muted)">{{ c.company }}</p>
                                        </div>
                                    </a>
                                </td>

                                <!-- Email -->
                                <td class="px-5 py-3.5 text-xs hidden md:table-cell" style="color: var(--text-secondary)">
                                    {{ c.email }}
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-3.5">
                                    <StatusBadge :status="c.status" />
                                </td>

                                <!-- Contacts count -->
                                <td class="px-5 py-3.5 text-center hidden sm:table-cell">
                                    <span class="text-sm font-semibold" style="color: var(--text-secondary)">{{ c.contacts_count }}</span>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a :href="`/clients/${c.id}`">
                                            <button
                                                class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                                                title="Ver detalle"
                                                style="color: var(--text-muted)"
                                                @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-3)'; $event.currentTarget.style.color = 'var(--text-primary)'"
                                                @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
                                            >
                                                <EyeIcon class="w-3.5 h-3.5" />
                                            </button>
                                        </a>
                                        <a :href="`/clients/${c.id}/edit`">
                                            <button
                                                class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                                                title="Editar"
                                                style="color: var(--text-muted)"
                                                @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-3)'; $event.currentTarget.style.color = 'var(--color-brand-600)'"
                                                @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
                                            >
                                                <PencilSquareIcon class="w-3.5 h-3.5" />
                                            </button>
                                        </a>
                                        <button
                                            class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                                            title="Eliminar"
                                            style="color: var(--text-muted)"
                                            @mouseover="$event.currentTarget.style.background = 'var(--color-error-bg)'; $event.currentTarget.style.color = 'var(--color-error)'"
                                            @mouseleave="$event.currentTarget.style.background = ''; $event.currentTarget.style.color = 'var(--text-muted)'"
                                            @click="handleDelete(c)"
                                        >
                                            <TrashIcon class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ── Pagination ─────────────────────── -->
                <div
                    v-if="meta.last_page > 1"
                    class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3.5"
                    style="border-top: 1px solid var(--border-subtle)"
                >
                    <p class="text-xs" style="color: var(--text-muted)">
                        Página
                        <strong style="color: var(--text-primary)">{{ currentPage }}</strong>
                        de
                        <strong style="color: var(--text-primary)">{{ meta.last_page }}</strong>
                        &nbsp;·&nbsp;
                        <strong style="color: var(--text-primary)">{{ meta.total }}</strong> registros
                    </p>

                    <div class="flex items-center gap-1 flex-wrap justify-center">
                        <!-- Prev -->
                        <button
                            :disabled="currentPage === 1"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors disabled:opacity-30"
                            style="color: var(--text-secondary)"
                            @click="goToPage(currentPage - 1)"
                            @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                            @mouseleave="$event.currentTarget.style.background = ''"
                        >
                            <ChevronLeftIcon class="w-4 h-4" />
                        </button>

                        <!-- Pages with ellipsis -->
                        <template v-for="p in pages" :key="p">
                            <template v-if="p === 1 || p === meta.last_page || Math.abs(p - currentPage) <= 1">
                                <button
                                    class="w-8 h-8 rounded-lg text-xs font-medium transition-all"
                                    :style="p === currentPage
                                        ? 'background: var(--color-brand-600); color: white'
                                        : 'color: var(--text-secondary)'"
                                    @click="goToPage(p)"
                                    @mouseover="p !== currentPage && ($event.currentTarget.style.background = 'var(--bg-surface-2)')"
                                    @mouseleave="p !== currentPage && ($event.currentTarget.style.background = '')"
                                >
                                    {{ p }}
                                </button>
                            </template>
                            <span
                                v-else-if="p === 2 && currentPage > 3"
                                class="w-8 h-8 flex items-center justify-center text-xs"
                                style="color: var(--text-muted)"
                            >…</span>
                            <span
                                v-else-if="p === meta.last_page - 1 && currentPage < meta.last_page - 2"
                                class="w-8 h-8 flex items-center justify-center text-xs"
                                style="color: var(--text-muted)"
                            >…</span>
                        </template>

                        <!-- Next -->
                        <button
                            :disabled="currentPage === meta.last_page"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors disabled:opacity-30"
                            style="color: var(--text-secondary)"
                            @click="goToPage(currentPage + 1)"
                            @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                            @mouseleave="$event.currentTarget.style.background = ''"
                        >
                            <ChevronRightIcon class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </template>

            <!-- ── Empty state ──────────────────────── -->
            <EmptyState
                v-else
                :title="hasFilters ? 'Sin resultados' : 'Sin clientes'"
                :description="hasFilters
                    ? 'Ningún cliente coincide con tu búsqueda.'
                    : 'Agrega tu primer cliente para comenzar.'"
            >
                <div class="flex gap-2 mt-4">
                    <button
                        v-if="hasFilters"
                        class="text-sm px-3 py-1.5 rounded-lg border transition-colors"
                        style="color: var(--text-secondary); border-color: var(--border-default)"
                        @click="clearFilters"
                    >
                        Limpiar filtros
                    </button>
                    <a v-else href="/clients/create">
                        <BaseButton size="sm">
                            <PlusIcon class="w-3.5 h-3.5" />
                            Nuevo cliente
                        </BaseButton>
                    </a>
                </div>
            </EmptyState>
        </div>

        <!-- ── Confirm modal ─────────────────────────── -->
        <AppModal
            :show="confirm.show.value"
            :title="confirm.meta.value.title ?? 'Confirmar'"
            size="sm"
            @close="confirm.cancel()"
        >
            <p class="text-sm" style="color: var(--text-secondary)">
                {{ confirm.meta.value.message }}
            </p>
            <template #footer>
                <BaseButton variant="secondary" size="sm" @click="confirm.cancel()">Cancelar</BaseButton>
                <BaseButton variant="danger" size="sm" :loading="confirm.loading.value" @click="confirm.confirm()">
                    Eliminar
                </BaseButton>
            </template>
        </AppModal>
    </div>
</template>

<style scoped>
.fade-fast-enter-active, .fade-fast-leave-active { transition: opacity 150ms ease, transform 150ms ease; }
.fade-fast-enter-from, .fade-fast-leave-to { opacity: 0; transform: scale(0.9); }

.dropdown-enter-active, .dropdown-leave-active { transition: opacity 120ms ease, transform 120ms ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px) scale(0.97); }
</style>

<script setup>
import { ref, onMounted, computed } from 'vue'
import {
    UsersIcon,
    CheckCircleIcon,
    SparklesIcon,
    PhoneArrowUpRightIcon,
    PlusIcon,
    ChevronRightIcon,
    ArrowTrendingUpIcon,
} from '@heroicons/vue/20/solid'
import http from '../../axios'
import StatCard    from '../ui/StatCard.vue'
import StatusBadge from '../ui/StatusBadge.vue'

const loading = ref(true)
const stats   = ref({ total: 0, activo: 0, inactivo: 0, prospecto: 0, contacts: 0 })
const recent  = ref([])

const conversionRate = computed(() =>
    stats.value.total > 0
        ? Math.round((stats.value.activo / stats.value.total) * 100)
        : 0
)

const DISTRIBUTION = [
    { label: 'Activos',    key: 'activo',    color: 'var(--color-success)' },
    { label: 'Prospectos', key: 'prospecto', color: 'var(--color-warning)' },
    { label: 'Inactivos',  key: 'inactivo',  color: 'var(--text-muted)' },
]

onMounted(async () => {
    try {
        const [statsRes, recentRes] = await Promise.all([
            http.get('/api/clients/stats'),
            http.get('/api/clients', { params: { per_page: 5, sort_by: 'created_at', sort_dir: 'desc' } }),
        ])
        stats.value  = statsRes.data.data
        recent.value = recentRes.data.data ?? []
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <div>
        <!-- Page header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold" style="color: var(--text-primary)">Dashboard</h1>
            <p class="text-sm mt-0.5" style="color: var(--text-muted)">Vista general de tu CRM</p>
        </div>

        <!-- KPI cards -->
        <div v-if="loading" class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div
                v-for="i in 4" :key="i"
                class="rounded-2xl border p-5"
                style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
            >
                <div class="skeleton h-2.5 rounded w-20 mb-4" />
                <div class="skeleton h-8 rounded w-14" />
            </div>
        </div>

        <div v-else class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <StatCard label="Total clientes" :value="stats.total"    :icon="UsersIcon"              color="brand"   />
            <StatCard label="Activos"         :value="stats.activo"   :icon="CheckCircleIcon"        color="success" />
            <StatCard label="Prospectos"      :value="stats.prospecto" :icon="SparklesIcon"          color="warning" />
            <StatCard label="Contactos"       :value="stats.contacts" :icon="PhoneArrowUpRightIcon"  color="info"    />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent clients -->
            <div
                class="lg:col-span-2 rounded-2xl border overflow-hidden"
                style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
            >
                <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color: var(--border-subtle)">
                    <div>
                        <h2 class="text-sm font-semibold" style="color: var(--text-primary)">Clientes recientes</h2>
                        <p class="text-xs mt-0.5" style="color: var(--text-muted)">Últimas incorporaciones</p>
                    </div>
                    <a
                        href="/clients"
                        class="inline-flex items-center gap-1 text-xs font-semibold transition-colors"
                        style="color: var(--color-brand-600)"
                    >
                        Ver todos
                        <ChevronRightIcon class="w-3.5 h-3.5" />
                    </a>
                </div>

                <!-- Skeleton list -->
                <div v-if="loading" class="divide-y" style="border-color: var(--border-subtle)">
                    <div
                        v-for="i in 5" :key="i"
                        class="flex items-center gap-3 px-5 py-3.5"
                        :style="{ opacity: 1 - (i - 1) * 0.12 }"
                    >
                        <div class="skeleton w-8 h-8 rounded-full shrink-0" />
                        <div class="flex-1 flex flex-col gap-1.5">
                            <div class="skeleton h-3 rounded" :style="{ width: `${90 + i * 20}px` }" />
                            <div class="skeleton h-2.5 rounded" :style="{ width: `${60 + i * 15}px` }" />
                        </div>
                        <div class="skeleton h-5 rounded-full w-16" />
                    </div>
                </div>

                <div v-else class="divide-y" style="border-color: var(--border-subtle)">
                    <a
                        v-for="c in recent"
                        :key="c.id"
                        :href="`/clients/${c.id}`"
                        class="flex items-center gap-3 px-5 py-3.5 transition-colors"
                        style="color: inherit; text-decoration: none"
                        @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                        @mouseleave="$event.currentTarget.style.background = ''"
                    >
                        <div
                            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                            style="background: var(--color-brand-50); color: var(--color-brand-700)"
                        >
                            {{ c.name?.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text-primary)">{{ c.name }}</p>
                            <p class="text-xs truncate" style="color: var(--text-muted)">{{ c.company }}</p>
                        </div>
                        <StatusBadge :status="c.status" />
                        <ChevronRightIcon class="w-3.5 h-3.5 shrink-0" style="color: var(--text-muted)" />
                    </a>

                    <div
                        v-if="!recent.length"
                        class="px-5 py-10 text-center text-sm"
                        style="color: var(--text-muted)"
                    >
                        Sin clientes aún
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="flex flex-col gap-5">

                <!-- Distribution -->
                <div
                    class="rounded-2xl border p-5"
                    style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
                >
                    <h2 class="text-sm font-semibold mb-4" style="color: var(--text-primary)">Distribución</h2>

                    <div v-if="loading" class="space-y-4">
                        <div v-for="i in 3" :key="i" class="space-y-1.5">
                            <div class="skeleton h-2.5 rounded w-20" />
                            <div class="skeleton h-1.5 rounded w-full" />
                        </div>
                    </div>

                    <div v-else class="space-y-4">
                        <div v-for="item in DISTRIBUTION" :key="item.key">
                            <div class="flex justify-between text-xs mb-1.5">
                                <span style="color: var(--text-secondary)">{{ item.label }}</span>
                                <span class="font-semibold" style="color: var(--text-primary)">
                                    {{ stats[item.key] }}
                                    <span class="font-normal" style="color: var(--text-muted)">
                                        ({{ stats.total ? Math.round((stats[item.key] / stats.total) * 100) : 0 }}%)
                                    </span>
                                </span>
                            </div>
                            <div class="h-1.5 rounded-full overflow-hidden" style="background: var(--bg-surface-3)">
                                <div
                                    class="h-full rounded-full transition-all duration-700"
                                    :style="{
                                        width: stats.total ? `${(stats[item.key] / stats.total) * 100}%` : '0%',
                                        background: item.color,
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conversion rate -->
                <div
                    class="rounded-2xl border p-5"
                    style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
                >
                    <div class="flex items-center gap-2 mb-2">
                        <ArrowTrendingUpIcon class="w-4 h-4" style="color: var(--color-success)" />
                        <h2 class="text-sm font-semibold" style="color: var(--text-primary)">Conversión</h2>
                    </div>
                    <p class="text-4xl font-bold mt-1" style="color: var(--color-success)">
                        <span v-if="loading" class="skeleton inline-block h-10 w-16 rounded" />
                        <template v-else>{{ conversionRate }}%</template>
                    </p>
                    <p class="text-xs mt-1.5" style="color: var(--text-muted)">Prospectos convertidos a activos</p>
                </div>

                <!-- Quick actions -->
                <div
                    class="rounded-2xl border p-5"
                    style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
                >
                    <h2 class="text-sm font-semibold mb-3" style="color: var(--text-primary)">Acciones rápidas</h2>
                    <div class="flex flex-col gap-2">
                        <a
                            href="/clients/create"
                            class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors"
                            style="background: var(--color-brand-600)"
                            @mouseover="$event.currentTarget.style.background = 'var(--color-brand-700)'"
                            @mouseleave="$event.currentTarget.style.background = 'var(--color-brand-600)'"
                        >
                            <PlusIcon class="w-4 h-4 shrink-0" />
                            Nuevo cliente
                        </a>
                        <a
                            href="/clients"
                            class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors"
                            style="color: var(--text-secondary)"
                            @mouseover="$event.currentTarget.style.background = 'var(--bg-surface-2)'"
                            @mouseleave="$event.currentTarget.style.background = ''"
                        >
                            <UsersIcon class="w-4 h-4 shrink-0" style="color: var(--text-muted)" />
                            Ver todos los clientes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
